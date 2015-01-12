<?php
/**
 * Created by PhpStorm.
 * User: Шаповал
 * Date: 28.12.2014
 * Time: 14:55
 */

class WeekModel {
    public $week_number;

    /** @var  array $days */
    public $days = array();

    public function __construct($weekNumber)
    {
        $this->week_number = $weekNumber;

        $this->days[1] = new DayModel(1,"Понеділок");
        $this->days[2] = new DayModel(2, "Вівторок");
        $this->days[3] = new DayModel(3, "Середа");
        $this->days[4] = new DayModel(4, "Четвер");
        $this->days[5] = new DayModel(5, "П’ятниця");
        $this->days[6] = new DayModel(6, "Субота");


    }

    public function toArray()
    {
        $days = array();
        /** @var DayModel $day */
        foreach($this->days as $day)
            $days[$day->day_number] = $day->toArray();

        return array(
            'week_number'=>$this->week_number,
            'days' =>$days
        );
    }
} 