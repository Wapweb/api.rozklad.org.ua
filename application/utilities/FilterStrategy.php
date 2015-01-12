<?php
/**
 * Created by PhpStorm.
 * User: Шаповал
 * Date: 11.01.2015
 * Time: 23:47
 */

interface FilterStrategy {
    function buildCondition(array $data, $allow_filters = array());
} 