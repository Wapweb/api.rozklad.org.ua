<?php
/**
 * Created by PhpStorm.
 * User: Шаповал
 * Date: 27.02.2015
 * Time: 18:06
 */

class TeacherRatingModel {
    protected  static $_tableName = "teacher_rating";
    protected static $_primaryKey = "rating_id";

    public $rating_avg_mark1 = 0;
    public $rating_avg_mark2 = 0;
    public $rating_avg_mark3 = 0;
    public $rating_avg_mark4 = 0;
    public $mark_count = 0;

    /**
     * @param int $teacher_id
     * @return TeacherRatingModel
     */
    public static function getTeacherAvgRatings($teacher_id)
    {
        $teacher_id = abs(intval($teacher_id));

        /** @var PDO $db */
        $db = Registry::get('db');

        $count = $db->query("
            SELECT COUNT(*) FROM ".self::$_tableName." WHERE ".TeacherModel::PRIMARY_KEY." = '$teacher_id'
        ")->fetchColumn();

        $rating = new TeacherRatingModel();

        if($count > 0)
        {
            $rating->mark_count = $count;
            $data = $db->query("
                SELECT AVG(rating_mark_1) as rating_avg_1,
                  AVG(rating_mark_2) as rating_avg_2,
                    AVG(rating_mark_3) as rating_avg_3,
                      AVG(rating_mark_4) as rating_avg_4
                        FROM ".self::$_tableName."
                          WHERE ".TeacherModel::PRIMARY_KEY." = '$teacher_id'
            ")->fetch(PDO::FETCH_ASSOC);

            $rating->rating_avg_mark1 = $data["rating_avg_1"];
            $rating->rating_avg_mark2 = $data["rating_avg_2"];
            $rating->rating_avg_mark3 = $data["rating_avg_3"];
            $rating->rating_avg_mark4 = $data["rating_avg_4"];
        }

        return $rating;
    }

    public function toArray()
    {
        return array(
            "mark_avg_knowledge_subject"=> $this->rating_avg_mark2,
            "mark_avg_exactingness"=> $this->rating_avg_mark1,
            "mark_avg_relation_to_the_student"=> $this->rating_avg_mark3,
            "mark_avg_sense_of_humor"=> $this->rating_avg_mark4,
            "mark_count"=> $this->mark_count,
        );
    }
} 