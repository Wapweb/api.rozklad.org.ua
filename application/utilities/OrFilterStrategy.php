<?php
/**
 * Created by PhpStorm.
 * User: Шаповал
 * Date: 11.01.2015
 * Time: 23:49
 */

class OrFilterStrategy implements FilterStrategy {
    use AndConditionTrait;

    /**
     * @param array $data
     * @param array $allow_filters
     * @throws ApiException
     * @return string
     */
    public function buildCondition(array $data, $allow_filters = array())
    {
        /** @var PDO $db */
        $db = Registry::get('db');
        $conditions = [];

        //OR
        $conditions[] = " AND (";
        foreach($data as $key => $value)
        {
            $andConditionsData = array();
            foreach($value as $k=>$v)
            {
                if(!is_array($v))
                {
                    $andConditionsData[$k] = $v;
                }
            }
            $andCondition = $this->buildAndCondition($andConditionsData,$allow_filters);
            $andCondition = "($andCondition)";

            if(count($conditions) == 1)
            {
                $conditions[] = $andCondition;
            }
            else
            {
                $conditions[] = "OR $andCondition";
            }
        }
        $conditions[] = ")";


        return implode(" ",$conditions);
    }
} 