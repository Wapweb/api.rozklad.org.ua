<?php
/**
 * Created by PhpStorm.
 * User: Шаповал
 * Date: 28.12.2014
 * Time: 2:11
 */

class TeacherModel extends Model {
    const TABLE = "`teacher`";
    const RELATION_TABLE = "`teacher_lesson`";
    const PRIMARY_KEY = "`teacher_id`";

    protected  static $_tableName = "teacher";
    protected static $_primaryKey = "teacher_id";

    public $teacher_id;
    public $teacher_name;
    public $teacher_full_name;
    public $teacher_short_name;
    public $teacher_url;
    public $teacher_rating;

    /**
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public static function getAll($offset = null, $limit = null, array $showProperties = null, array $hideProperties = null, $hidePropertyNames = false)
    {
        /** @var PDO $db */
        $db = Registry::get('db');

        $count = $db->query("
            SELECT COUNT(*) FROM ".TeacherModel::TABLE."
        ")->fetchColumn();

        $result = array();

        $query = $db->query("
            SELECT * FROM ".TeacherModel::TABLE." ".($offset !== null && $limit !== null ? "LIMIT $offset , $limit" : "")."
        ");
        while($data = $query->fetch(PDO::FETCH_ASSOC))
        {
            $teacherModel = new TeacherModel();
            $teacherModel->unpack($data);

            $result['data'][] = $teacherModel->toArray($showProperties,$hideProperties,$hidePropertyNames);
        }
        $result['meta']['total_count'] = $count;
        $result['meta']['offset'] = $offset;
        $result['meta']['limit'] = $limit;

        return $result;
    }

    /**
     * @param int|string $teacher_name
     * @return array
     * @throws ApiException
     */
    public static function getByNameOrId($teacher_name,$return_obj=false)
    {
        $teacher_name = mb_strtolower($teacher_name,"UTF-8");
        $teacher_name = urldecode($teacher_name);

        /** @var PDO $db */
        $db = Registry::get('db');
        $count = $db->query("
            SELECT COUNT(*) FROM ".TeacherModel::TABLE."
                WHERE teacher_name = ".$db->quote($teacher_name)."
                    OR teacher_full_name_lesson = ".$db->quote($teacher_name)."
                        OR teacher_id = ".$db->quote($teacher_name)."
                            OR teacher_short_name_lesson = ".$db->quote($teacher_name)."
        ")->fetchColumn();

        if($count > 0)
        {
            $query = $db->query("
                SELECT * FROM ".TeacherModel::TABLE."
                    WHERE teacher_name = ".$db->quote($teacher_name)."
                        OR teacher_full_name_lesson = ".$db->quote($teacher_name)."
                            OR teacher_id = ".$db->quote($teacher_name)."
                                OR teacher_short_name_lesson = ".$db->quote($teacher_name)." LIMIT 1
            ");
            $data =  $query->fetch(PDO::FETCH_ASSOC);
            $teacherModel = new TeacherModel();
            $teacherModel->unpack($data);
        }
        else
        {
            throw new ApiException("Teacher not found");
        }

        return $return_obj ? $teacherModel : $teacherModel->toArray();
    }

    /**
     * @param string $teacher_name
     * @return array
     * @throws ApiException
     */
    public static function searchByName($teacher_name)
    {
        $result = array();

        /** @var PDO $db */
        $db = Registry::get('db');

        $count = $db->query("
            SELECT COUNT(*) FROM ".TeacherModel::TABLE."
                WHERE `teacher_name` LIKE ".$db->quote($teacher_name."%")."
        ")->fetchColumn();

        if($count)
        {
            $query = $db->query("
            SELECT * FROM ".TeacherModel::TABLE."
                WHERE `teacher_name` LIKE ".$db->quote($teacher_name."%")."
            ");
            while($data = $query->fetch(PDO::FETCH_ASSOC))
            {
                $teacherModel = new TeacherModel();
                $teacherModel->unpack($data);
                $result[] = $teacherModel->toArray();
            }
        }
        else
        {
            throw new ApiException("Teachers not found. Query:".htmlspecialchars($teacher_name));
        }

        return $result;
    }

    /**
     * @param string|int $group_name
     * @return array
     * @throws ApiException
     */
    public static function getAllByGroupNameOrGroupId($group_name)
    {
        $group_name = mb_strtolower($group_name,"UTF-8");
        $group_name = str_replace(array_values(GroupModel::$replace), array_keys(GroupModel::$replace), $group_name);

        /** @var PDO $db */
        $db = Registry::get('db');

        $group_id = $db->query("
            SELECT group_id FROM ".GroupModel::TABLE."
                WHERE (group_full_name = ".$db->quote($group_name)." OR group_id = ".$db->quote($group_name).")
        ")->fetchColumn();

        $result = array();
        if($group_id)
        {
            $query = "
                SELECT t1.* FROM ".TeacherModel::TABLE." as t1
                    JOIN ".TeacherModel::RELATION_TABLE." as t2 USING(".TeacherModel::PRIMARY_KEY.")
                        JOIN ".LessonModel::TABLE."  as t3 USING (".LessonModel::PRIMARY_KEY.")
                            JOIN ".GroupModel::TABLE." as t4 USING(".GroupModel::PRIMARY_KEY.")
                                WHERE t4.group_id = '".abs(intval($group_id))."'
                                    GROUP BY t1.".TeacherModel::PRIMARY_KEY."
            ";

            $query = $db->query($query);
            while($data = $query->fetch((PDO::FETCH_ASSOC)))
            {
                $teacherModel = new TeacherModel();
                $teacherModel->unpack($data);
                $result[] = $teacherModel->toArray();
            }
        }
        else
        {
            throw new ApiException("Teachers not found: undefined group");
        }

        //$res = self::teachersDuplicateFilter($result);
        return $result;
    }

    /**
     * @param int $lesson_id
     * @return array
     */
    public static function getAllByLessonId($lesson_id)
    {
        $result = array();

        /** @var PDO $db */
        $db = Registry::get('db');

        $query = $db->query("
            SELECT * FROM ".TeacherModel::TABLE." as t1
                JOIN ".TeacherModel::RELATION_TABLE." as t2 USING(".TeacherModel::PRIMARY_KEY.")
                    WHERE t2.".LessonModel::PRIMARY_KEY." = '".abs(intval($lesson_id))."'
        ");

        while($data = $query->fetch(PDO::FETCH_ASSOC))
        {
            $teacherModel = new TeacherModel();
            $teacherModel->unpack($data);
            $result[] = $teacherModel->toArray();
        }

        return $result;
    }

    public static function teachersDuplicateFilter(array $teachers)
    {
        $teachers_uniq = array();
        foreach($teachers as $teacher)
        {
            $teacher["teacher_name"] = preg_replace('/(.*?) \((.*?)\) (.*?)/U','${1}',$teacher["teacher_name"]);
            if(!isset($teachers_uniq[$teacher["teacher_name"]]))
            {
                $teachers_uniq[$teacher["teacher_name"]] = $teacher;
            }
        }

        $teachers_uniq_invert = array();
        foreach($teachers_uniq as $teacher)
            $teachers_uniq_invert[] = $teacher;

        unset($teachers_uniq);

        return $teachers_uniq_invert;
    }

    public function updateRating(TeacherVoteModel $newVote)
    {
        //countOfVotes include newVote
        $countOfVotes = TeacherVoteModel::getVotesCountFromTeacher($this);
        $newRating = (($countOfVotes-1)*$this->teacher_rating + $newVote->ratingMarkAvg)/($countOfVotes);

        $this->teacher_rating = round($newRating,3);
    }

    public  function toArray(array $showProperties = null, array $hideProperties = null, $hidePropertyNames = false)
    {
        $result = [];
        if($showProperties != null)
        {
            $properties = get_object_vars($this);
            foreach($properties as $name => $value)
            {
                if(isset($showProperties[$name]))
                {
                    $result[$name] = $value;
                }
            }

            if(!count($result))
            {
                throw new ApiException("Bad Request! Invalid showProperties filter", 400);
            }

            return $hidePropertyNames ? count($result) == 1 ? current($result) : $result : $result;
        }

        return array(
            'teacher_id' => $this->teacher_id,
            'teacher_name' => $this->teacher_name,
            'teacher_full_name'=>$this->teacher_full_name,
            'teacher_short_name'=>$this->teacher_short_name,
            'teacher_url' => $this->teacher_url,
            'teacher_rating'=>$this->teacher_rating
        );
    }

    public  function unpack($data)
    {
        $this->setId($data["teacher_id"]);
        $this->teacher_id = $data["teacher_id"];
        $this->teacher_name = $data["teacher_name"];
        $this->teacher_full_name = $data["teacher_full_name_lesson"];
        $this->teacher_short_name = $data["teacher_short_name_lesson"];
        $this->teacher_url = $data["teacher_url"];
        $this->teacher_rating = $data["teacher_rating"];
    }

    public function pack()
    {
        $data = array();
        $data["teacher_id"] = $this->teacher_id;
        $data["teacher_name"] = $this->teacher_name;
        $data["teacher_full_name_lesson"] = $this->teacher_full_name;
        $data["teacher_short_name_lesson"] = $this->teacher_short_name;
        $data["teacher_url"] = $this->teacher_url;
        $data["teacher_rating"] = $this->teacher_rating;

        return $data;
    }
} 