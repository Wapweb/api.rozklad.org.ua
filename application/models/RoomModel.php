<?php
/**
 * Created by PhpStorm.
 * User: Шаповал
 * Date: 22.01.2015
 * Time: 1:33
 */

class RoomModel extends Model {
    const TABLE = "`room`";
    const RELATION_TABLE = "`room_lesson`";
    const PRIMARY_KEY = "`room_id`";

    public $room_id;
    public $room_name;
    public $room_latitude;
    public $room_longitude;

    /**
     * @param $lesson_id
     * @return array
     */
    public static function getAllByLessonId($lesson_id)
    {
        $lesson_id = abs(intval($lesson_id));

        /** @var PDO $db */
        $db = Registry::get('db');

        $result = array();

        $sth = $db->query("
            SELECT t1.* FROM ".RoomModel::TABLE." as t1
                JOIN ".RoomModel::RELATION_TABLE." as t2 USING(".RoomModel::PRIMARY_KEY.")
                    JOIN ".LessonModel::TABLE." as t3 USING(".LessonModel::PRIMARY_KEY.")
                        WHERE t3.".LessonModel::PRIMARY_KEY." = '$lesson_id'
        ");

        while($data = $sth->fetch(PDO::FETCH_ASSOC))
        {
            $room = new RoomModel();
            $room->unpack($data);
            $result[] = $room->toArray();
        }

        return $result;
    }

    public function toArray()
    {
        return array(
            "room_id"=>$this->room_id,
            "room_name"=>$this->room_name,
            "room_latitude"=>$this->room_latitude,
            "room_longitude"=>$this->room_longitude
        );
    }

    /**
     * unpack array data to Model's properties
     * @param $data
     */
    public function unpack($data)
    {
        $this->room_id = $data["room_id"];
        $this->room_name = $data["room_name"];
        $this->room_longitude = $data["room_longitude"];
        $this->room_latitude = $data["room_latitude"];
    }


} 