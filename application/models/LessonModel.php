<?php
/**
 * Created by PhpStorm.
 * User: Шаповал
 * Date: 14.09.14
 * Time: 14:29
 */

class LessonModel {
    public static $_db_name = "`lesson`";

    public $lesson_id;
    public $group_id;
    public $day_number;
    public $day_name;
    public $lesson_number;
    public $lesson_name;
    public $lesson_room;
    public $lesson_type;
    public $teacher_name;
    public $lesson_week;
    public $time_start;
    public $time_end;
    public $rate;

    public function __construct()
    {

    }

    /**
     * @param $group_name
     * @return LessonModel[]|array
     */
    public static function getAllByGroupName($group_name,$cond = "")
    {
		$group_name = mb_strtolower($group_name,"UTF-8");
		$group_name = str_replace(array_values(GroupModel::$replace), array_keys(GroupModel::$replace), $group_name);
	
        /** @var PDO $db */
        $db = Registry::get('db');

        $group_id = $db->query("
            SELECT group_id FROM ".GroupModel::$_db_name."
                WHERE (group_full_name = ".$db->quote($group_name)." OR group_id = ".$db->quote($group_name).")
        ")->fetchColumn();

        $count = $db->query("
            SELECT COUNT(*) FROM ".LessonModel::$_db_name."
                WHERE (group_id = '$group_id' OR group_id = ".$db->quote($group_name).") $cond
        ")->fetchColumn();
		
		$query = "
            SELECT COUNT(*) FROM ".LessonModel::$_db_name."
                WHERE (group_id = '$group_id' OR group_id = ".$db->quote($group_name).") $cond
        ";

        $result = array();
        if($count > 0)
        {
            $query = $db->query("
                SELECT * FROM ".LessonModel::$_db_name."
                    WHERE (group_id  = '$group_id' OR group_id = ".$db->quote($group_name).") $cond ORDER BY lesson_week,day_number ASC
            ");
            while($data = $query->fetch(PDO::FETCH_ASSOC))
            {
                $lessonModel = new LessonModel();
                $lessonModel->lesson_id = $data["lesson_id"];
                $lessonModel->group_id = $data["group_id"];
                $lessonModel->day_number = $data["day_number"];
                $lessonModel->day_name = $data["day_name"];
                $lessonModel->lesson_name = $data["lesson_name"];
                $lessonModel->lesson_number = $data["lesson_number"];
                $lessonModel->lesson_room = $data["lesson_room"];
                $lessonModel->lesson_type = $data["lesson_type"];
                $lessonModel->teacher_name = $data["teacher_name"];
                $lessonModel->lesson_week = $data["lesson_week"];
                $lessonModel->time_start = $data["time_start"];
                $lessonModel->time_end = $data["time_end"];
                $lessonModel->rate = $data["rate"];
                $result[] = $lessonModel->toArray();
            }
        }
        else
        {
            throw new Exception("404 - Not found");
        }

        return $result;
    }
	
	public function toArray()
    {
        return array(
            "lesson_id" => $this->lesson_id,
            "group_id" => $this->group_id,
            "day_number" => $this->day_number,
            "day_name" => $this->day_name,
            "lesson_name" => $this->lesson_name,
            "lesson_number" => $this->lesson_number,
			"lesson_room" => $this->lesson_room,
			"lesson_type" => $this->lesson_type,
			"teacher_name" => $this->teacher_name,
			"lesson_week" => $this->lesson_week,
			"time_start" => $this->time_start,
			"time_end" => $this->time_end,
			"rate" => $this->rate
        );
    }
} 