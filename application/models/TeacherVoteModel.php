<?php
/**
 * Created by PhpStorm.
 * User: Шаповал
 * Date: 20.02.2015
 * Time: 15:19
 */

class TeacherVoteModel extends Model {
    protected  static $_tableName = "teacher_rating";
    protected static $_primaryKey = "rating_id";

    public $ratingId;
    public $teacherId;
    public $ratingMark1;
    public $ratingMark2;
    public $ratingMark3;
    public $ratingMark4;
    public $ratingMarkAvg;
    public $count;

    public $userIp;
    public $userAgent;
    public  $userDatetime;

    public function __construct()
    {

    }

    /**
     * @return bool
     */
    public function canVote()
    {
        /** @var PDO $db */
        $db = Registry::get("db");

        $sth = $db->prepare("SELECT COUNT(*) FROM ".static::$_tableName."
            WHERE teacher_id = :teacher_id
                AND user_ip = :user_ip
                    AND user_agent = :browser
        ");
        $sth->bindParam(":teacher_id",$this->teacherId);
        $sth->bindParam(":user_ip",$this->userIp);
        $sth->bindParam(":browser",$this->userAgent);

        $res = $sth->execute();
        $count = 0;
        if($res)
            $count = $sth->fetchColumn();

        return $count > 0 ? false : true;
    }

    public function toArray()
    {
        return array(
            "rating_id"=>$this->getId(),
            "teacher_id"=>$this->teacherId,
            "mark_knowledge_subject"=>$this->ratingMark1,
            "mark_exactingess"=>$this->ratingMark2,
            "mark_relation_to_the_student"=>$this->ratingMark3,
            "mark_sense_of_humor"=>$this->ratingMark4,
            "mark_avg"=>$this->ratingMarkAvg,
            "user_ip"=>long2ip($this->userIp),
            "user_datetime"=>$this->userDatetime
        );
    }

    public function unpack($data)
    {
        $this->setId($data["rating_id"]);
        $this->ratingId = $data["rating_id"];
        $this->teacherId = $data["teacher_id"];
        $this->ratingMark1 = $data["rating_mark_1"];
        $this->ratingMark2 = $data["rating_mark_2"];
        $this->ratingMark3 = $data["rating_mark_3"];
        $this->ratingMark4 = $data["rating_mark_4"];
        $this->ratingMarkAvg = $data["rating_mark_avg"];
        $this->userIp = $data["user_ip"];
        $this->userAgent = $data["user_agent"];
        $this->userDatetime = $data["user_datetime"];
    }

    public function pack()
    {
        $data = array();
        $data["rating_id"] = $this->ratingId;
        $data["teacher_id"] = $this->teacherId;
        $data["rating_mark_1"] = $this->ratingMark1;
        $data["rating_mark_2"] = $this->ratingMark2;
        $data["rating_mark_3"] = $this->ratingMark3;
        $data["rating_mark_4"] = $this->ratingMark4;
        $data["rating_mark_avg"] = $this->ratingMarkAvg;
        $data["user_ip"] = $this->userIp;
        $data["user_agent"] = $this->userAgent;
        $data["user_datetime"] = $this->userDatetime;

        return $data;
    }

    public static function getVotesCountFromTeacher(TeacherModel $teacher)
    {
        /** @var PDO $db */
        $db = Registry::get("db");

        $sth = $db->prepare("SELECT COUNT(*) FROM ".static::$_tableName."
            WHERE teacher_id = :teacher_id
        ");
        $sth->bindParam(":teacher_id",$teacher->teacher_id);

        $res = $sth->execute();
        $count = 0;
        if($res)
            $count = $sth->fetchColumn();

        return $count;
    }
} 