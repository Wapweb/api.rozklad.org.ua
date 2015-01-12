<?php
/**
 * Created by PhpStorm.
 * User: Шаповал
 * Date: 11.01.2015
 * Time: 23:49
 */

class AndFilterStrategy implements FilterStrategy{
    use AndConditionTrait;

    /**
     * @param array $data
     * @param array $allow_filters
     * @throws ApiException
     * @return string
     */
    public function buildCondition(array $data, $allow_filters = array())
    {
        $condition = $this->buildAndCondition($data, $allow_filters);

        return "AND ".$condition;
    }
} 