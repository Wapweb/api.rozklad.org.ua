<?php
/**
 * Created by PhpStorm.
 * User: Шаповал
 * Date: 28.12.2014
 * Time: 14:57
 */

class DayModel {
    public $day_number;
    public $day_name;

    /** @var  array $lessons */
    public $lessons = array();

    public function __construct($dayNumber,$dayName)
    {
        $this->day_number = $dayNumber;
        $this->day_name  = $dayName;
    }

    public function toArray()
    {
        return array(
            'day_name' => $this->day_name,
            'day_number' => $this->day_number,
            'lessons' => $this->lessons
        );
    }
} 