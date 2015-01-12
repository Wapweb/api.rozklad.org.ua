<?php
/**
 * Created by PhpStorm.
 * User: Шаповал
 * Date: 28.12.2014
 * Time: 2:11
 */

class TeacherModel {
    const TABLE = "`teacher`";
    const RELATION_TABLE = "`teacher_lesson`";
    const PRIMARY_KEY = "`teacher_id`";

    public $teacher_id;
    public $teacher_name;
    public $teacher_url;

    /**
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public static function getAll($offset = 0, $limit = 100)
    {
        /** @var PDO $db */
        $db = Registry::get('db');

        $count = $db->query("
            SELECT COUNT(*) FROM ".TeacherModel::TABLE."
        ")->fetchColumn();

        $offset = isset($offset) ? abs(intval($offset)) : 0;
        $limit = isset($limit) ? abs(intval($limit)) : 100;
        if($limit < 1) $limit = 1;
        if($limit > 100) $limit = 100;

        $result = array();

        $query = $db->query("
            SELECT * FROM ".TeacherModel::TABLE." LIMIT $offset , $limit
        ");
        while($data = $query->fetch(PDO::FETCH_ASSOC))
        {
            $teacherModel = new TeacherModel();
            $teacherModel->teacher_id = $data['teacher_id'];
            $teacherModel->teacher_name = $data['teacher_name'];
            $teacherModel->teacher_url = $data['teacher_url'];

            $result['data'][] = $teacherModel->toArray();
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
    public static function getByNameOrId($teacher_name)
    {
        $teacher_name = mb_strtolower($teacher_name,"UTF-8");
        $teacher_name = urldecode($teacher_name);

        /** @var PDO $db */
        $db = Registry::get('db');
        $count = $db->query("
            SELECT COUNT(*) FROM ".TeacherModel::TABLE."
                WHERE teacher_name = ".$db->quote($teacher_name)." OR teacher_id = ".$db->quote($teacher_name)."
        ")->fetchColumn();

        if($count > 0)
        {
            $query = $db->query("
                SELECT * FROM ".TeacherModel::TABLE."
                    WHERE teacher_name = ".$db->quote($teacher_name)." OR teacher_id = ".$db->quote($teacher_name)."
            ");
            $data =  $query->fetch(PDO::FETCH_ASSOC);
            $teacherModel = new TeacherModel();
            $teacherModel->teacher_id = $data['teacher_id'];
            $teacherModel->teacher_name = $data['teacher_name'];
            $teacherModel->teacher_url = $data['teacher_url'];
        }
        else
        {
            throw new ApiException("Teacher not found");
        }

        return $teacherModel->toArray();
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
                $teacherModel->teacher_id = $data['teacher_id'];
                $teacherModel->teacher_name = $data['teacher_name'];
                $teacherModel->teacher_url = $data['teacher_url'];
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
                $teacherModel->teacher_id = $data['teacher_id'];
                $teacherModel->teacher_name = $data['teacher_name'];
                $teacherModel->teacher_url = $data['teacher_url'];
                $result[] = $teacherModel->toArray();
            }
        }
        else
        {
            throw new ApiException("Teachers not found: undefined group");
        }

        $res = self::teachersDuplicateFilter($result);
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
            $teacherModel->teacher_id = $data['teacher_id'];
            $teacherModel->teacher_name = $data['teacher_name'];
            $teacherModel->teacher_url = $data['teacher_url'];
            $result[] = $teacherModel->toArray();
        }

        return $result;
    }

    public  function toArray()
    {
        return array(
            'teacher_id' => $this->teacher_id,
            'teacher_name' => $this->teacher_name,
            'teacher_url' => $this->teacher_url
        );
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
} 