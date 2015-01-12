<?php
/**
 * Created by PhpStorm.
 * User: Шаповал
 * Date: 28.12.2014
 * Time: 14:48
 */

class TimetableModel {
    public $group;
    public $nearest_lesson;
    public $weeks = array();

    public function __construct($group_name)
    {
        $this->group = GroupModel::getByNameOrId($group_name);
        $this->initialize();
    }

    /**
     * @return void
     * @throws ApiException
     */
    private function initialize()
    {
        $lessons = LessonModel::getAllByGroupNameOrGroupId($this->group['group_id']);

        $this->weeks[1] = new WeekModel(1);
        $this->weeks[2] = new WeekModel(2);


        foreach($lessons as $lesson)
        {
            $this->weeks[($lesson['lesson_week'])]
                ->days[($lesson['day_number'])]
                ->lessons[] = $lesson;
        }
    }

    /**
     * @return int
     */
    private function getCurrentWeek()
    {
        $week_number = date("W");
        return $week_number%2 == 0 ? 1 : 2;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $weeks = array();

        /** @var WeekModel $week */
        foreach($this->weeks as $week)
            $weeks[$week->week_number] = $week->toArray();

        return array(
            'group' => $this->group,
            'weeks' => $weeks
        );
    }
} 