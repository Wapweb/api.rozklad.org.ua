<?php
/**
 * Created by PhpStorm.
 * User: Шаповал
 * Date: 28.12.2014
 * Time: 1:11
 */

class FilterConditionBuilder {

    public static function build($filters,$type="and",$table = "")
    {
        /** @var PDO $db */
        $db = Registry::get('db');
        $conditions = [];
        if($type == "or")
        {
            //OR
            $conditions[] = " AND (";
            foreach($filters as $key => $value)
            {
                if(count($conditions) == 1)
                    $conditions[] = ($table ? "$table." : "")."$key = ".$db->quote($value)."";
                else
                    $conditions[] = "OR ".($table ? "$table." : "")."$key = ".$db->quote($value)."";
            }
            $conditions[] = ")";
        }
        else
        {
            // AND
            foreach($filters as $key => $value)
            {
                $conditions[] = "AND ".($table ? "$table." : "")."$key = ".$db->quote($value)."";
            }
        }

        return implode(" ",$conditions);
    }
} 