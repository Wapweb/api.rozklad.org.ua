<?php
/**
 * Created by PhpStorm.
 * User: Шаповал
 * Date: 12.01.2015
 * Time: 0:18
 */

trait AndConditionTrait {

    /**
     * @param array $data
     * @param array $allow_filters
     * @return string
     * @throws ApiException
     */
    public function buildAndCondition(array $data,$allow_filters = array())
    {
        $this->checkFilters($data,$allow_filters);

        /** @var PDO $db */
        $db = Registry::get('db');

        $conditions = [];

        // AND
        if(count($conditions) == 1)
        {
            foreach($data as $key => $value)
            {
                $conditions[] = "$key = ".$db->quote($value)."";
            }
        }
        else
        {
            foreach($data as $key => $value)
            {
                $conditions[] = "$key = ".$db->quote($value)." ".(next($data) != null ? 'AND' : '');
            }
        }

        return implode(" ",$conditions);
    }

    /**
     * @param array $check_filters
     * @param array $allow_filters
     * @throws ApiException
     * @return void
     */
    private function checkFilters($check_filters,$allow_filters)
    {
        Utilities::checkFilters($check_filters,$allow_filters);
    }
} 