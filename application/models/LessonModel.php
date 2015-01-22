<?php
/**
 * Created by PhpStorm.
 * User: Шаповал
 * Date: 14.09.14
 * Time: 14:29
 */

class LessonModel extends Model {
    const TABLE = "`lesson`";
    const RELATION_TABLE = "`teacher_lesson`";
    const PRIMARY_KEY = "`lesson_id`";

    public $lesson_id;
    public $group_id;
    public $day_number;
    public $day_name;
    public $lesson_number;
    public $lesson_name;
    public $lesson_full_name;
    public $lesson_room;
    public $lesson_type;
    public $teacher_name;
    public $lesson_week;
    public $time_start;
    public $time_end;
    public $rate;

    public $teachers = array();
    public $rooms = array();

    /**
     * @param string|int $group_name
     * @param string $cond
     * @return LessonModel[]|array
     */
    public static function getAllByGroupNameOrGroupId($group_name,$cond = "")
    {
		$group_name = mb_strtolower($group_name,"UTF-8");
		$group_name = str_replace(array_values(GroupModel::$replace), array_keys(GroupModel::$replace), $group_name);
	
        /** @var PDO $db */
        $db = Registry::get('db');

        $group_id = $db->query("
            SELECT group_id FROM ".GroupModel::TABLE."
                WHERE (group_full_name = ".$db->quote($group_name)." OR group_id = ".$db->quote($group_name).")
        ")->fetchColumn();

        $count = $db->query("
            SELECT COUNT(*) FROM ".LessonModel::TABLE."
                WHERE (group_id = '$group_id' OR group_id = ".$db->quote($group_name).") $cond
        ")->fetchColumn();

        $result = array();
        if($count > 0)
        {
            $query = $db->query("
                SELECT * FROM ".LessonModel::TABLE."
                    WHERE (group_id  = '$group_id' OR group_id = ".$db->quote($group_name).") $cond ORDER BY lesson_week,day_number ASC
            ");
            while($data = $query->fetch(PDO::FETCH_ASSOC))
            {
                $lessonModel = new LessonModel();
                $lessonModel->unpack($data);
                $lessonModel->teachers = TeacherModel::getAllByLessonId($lessonModel->lesson_id);
                //enable duplicate filter
                $lessonModel->teachers = TeacherModel::teachersDuplicateFilter($lessonModel->teachers);
                //load rooms
                $lessonModel->rooms = RoomModel::getAllByLessonId($lessonModel->lesson_id);

                $result[] = $lessonModel->toArray();
            }
        }
        else
        {
            throw new ApiException("Lessons not found");
        }

        return $result;
    }

    /**
     * @param string|int $teacher_name
     * @param string $cond
     * @return array
     * @throws ApiException
     */
    public static function getAllByTeacherNameOrTeacherId($teacher_name,$cond="")
    {
        $result = array();

        //hide some properties
        $hideFilter = array('group_id'=>true);
        Registry::set('LessonModelHideFilter',$hideFilter);

        $teacher_name = urldecode(trim($teacher_name));

        /** @var PDO $db */
        $db = Registry::get('db');

        $teacher_id = $db->query("
            SELECT teacher_id FROM ".TeacherModel::TABLE."
                WHERE (teacher_name = ".$db->quote($teacher_name)." OR teacher_id = ".$db->quote($teacher_name).")
        ")->fetchColumn();

        $count = 0;
        if($teacher_id)
        {
            $count = $db->query("
            SELECT COUNT(*) FROM ".LessonModel::TABLE." AS t1
                JOIN ".TeacherModel::RELATION_TABLE." as t2 USING(".LessonModel::PRIMARY_KEY.")
                    WHERE t2.".TeacherModel::PRIMARY_KEY." = '".abs(intval($teacher_id))."' $cond
            ")->fetchColumn();
        }

        if($count > 0)
        {
            $query = $db->query("
               SELECT *
                   FROM ".LessonModel::TABLE." AS t1
                    JOIN ".TeacherModel::RELATION_TABLE." as t2 USING(".LessonModel::PRIMARY_KEY.")
                        WHERE t2.".TeacherModel::PRIMARY_KEY." = '".abs(intval($teacher_id))."' $cond
                            GROUP BY t1.day_number,t1.lesson_number,t1.lesson_week
                                ORDER BY t1.lesson_week,t1.day_number,t1.lesson_number ASC
            ");
            while($data = $query->fetch(PDO::FETCH_ASSOC))
            {
                $lessonModel = new LessonModel();
                $lessonModel->unpack($data);
                //$lessonModel->teachers = TeacherModel::getAllByLessonId($lessonModel->lesson_id);
                //load rooms
                $lessonModel->rooms = RoomModel::getAllByLessonId($lessonModel->lesson_id);

                //load groups
                $q = $db->query("
                    SELECT t1.* FROM ".GroupModel::TABLE." as t1
                        JOIN ".LessonModel::TABLE." as t2 USING(".GroupModel::PRIMARY_KEY.")
                            WHERE t2.day_number = '".$lessonModel->day_number."'
                            AND t2.lesson_number = '".$lessonModel->lesson_number."'
                            AND t2.teacher_name = ".$db->quote($lessonModel->teacher_name)."
                            AND t2.lesson_week = '".$lessonModel->lesson_week."'
                ");
                $groupsRes = array();
                while($groupsData = $q->fetch(PDO::FETCH_ASSOC))
                {
                    $group = new GroupModel();
                    $group->unpack($groupsData);
                    $groupsRes[] = $group->toArray();
                }
                $m = $lessonModel->toArray();
                $m["groups"] = $groupsRes;

                $result[] = $m;
            }
        }
        else
        {
            throw new ApiException("Lessons not found");
        }

        return $result;
    }
	
	public function toArray()
    {
        $toArray = array(
            "lesson_id" => $this->lesson_id,
            "group_id" => $this->group_id,
            "day_number" => $this->day_number,
            "day_name" => $this->day_name,
            "lesson_name" => $this->lesson_name,
            "lesson_full_name"=>$this->lesson_full_name,
            "lesson_number" => $this->lesson_number,
            "lesson_room" => $this->lesson_room,
            "lesson_type" => $this->lesson_type,
            "teacher_name" => $this->teacher_name,
            "lesson_week" => $this->lesson_week,
            "time_start" => $this->time_start,
            "time_end" => $this->time_end,
            "rate" => $this->rate,
            "teachers" => $this->teachers,
            "rooms"=>$this->rooms
        );
        return Utilities::hideModelPropertiesFilter('LessonModelHideFilter',$toArray);
    }

    public function unpack($data)
    {
        $this->lesson_id = $data["lesson_id"];
        $this->group_id = $data["group_id"];
        $this->day_number = $data["day_number"];
        $this->day_name = $data["day_name"];
        $this->lesson_name = $data["lesson_name"];
        $this->lesson_full_name = $data["lesson_full_name"];
        $this->lesson_number = $data["lesson_number"];
        $this->lesson_room = $data["lesson_room"];
        $this->lesson_type = $data["lesson_type"];
        $this->teacher_name = $data["teacher_name"];
        $this->lesson_week = $data["lesson_week"];
        $this->time_start = $data["time_start"];
        $this->time_end = $data["time_end"];
        $this->rate = $data["rate"];
    }
} 