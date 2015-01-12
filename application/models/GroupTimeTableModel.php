<?php
/**
 * Created by PhpStorm.
 * User: Шаповал
 * Date: 28.12.2014
 * Time: 14:48
 */

class GroupTimeTableModel {
    public $group;
    public $nearest_lesson;

    public $weeks = array();

    public $current_week;

    public function __construct($group_name)
    {
        $this->group = GroupModel::getByName($group_name);
        $week_number = date("W");
        $this->current_week = $week_number%2 ==0 ? 1 : 2;
        $this->initialize();
    }

    private function initialize()
    {
        $lessons = LessonModel::getAllByGroupName($this->group['group_id']);

        $this->weeks[1] = new WeekModel(1);
        $this->weeks[2] = new WeekModel(2);

        /** @var LessonModel $lesson */
        foreach($lessons as $lesson)
        {
           // $week = $this->weeks[$lesson['lesson_week']];
           // $ls = $week->days[($lesson['day_number'])]->lessons;
            //$ls[] = $lesson;
           // $lesson = $day->lessons[$lesson['lesson_number']];
            $a =1;
            $this->weeks[($lesson['lesson_week'])]
                ->days[($lesson['day_number'])]
                ->lessons[] = $lesson;
        }
    }

    public function toArray()
    {
        return array(
            'group' => $this->group,
            'weeks' => $this->weeks
        );
    }
} 