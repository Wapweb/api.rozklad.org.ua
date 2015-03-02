<?php
/**
 * Created by PhpStorm.
 * User: Шаповал
 * Date: 12.01.2015
 * Time: 16:42
 */

class Utilities {
    public static function checkFilters($check_filters,$allow_filters)
    {
        //check filters

        if($allow_filters != null && $check_filters != null)
        {
            if(is_array($allow_filters) && is_array($check_filters))
            {
                foreach($check_filters as $key => $value)
                {
                    if(!isset($allow_filters[$key]))
                    {
                        throw new ApiException("Bad Request! Invalid filter or search parameters!",400);
                    }
                }
            }
        }
    }

    public static function hideModelPropertiesFilter($filter_name,array $toArray)
    {
        $hideFilter = Registry::get($filter_name);

        if($hideFilter != null)
        {
            foreach($hideFilter as $key=>$value)
            {
                if(isset($toArray[$key]))
                    unset($toArray[$key]);
            }
        }

        return $toArray;
    }

    public static function getRealIp() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';

        return $ipaddress;
    }

    public static function getUserAgent()
    {
        return isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : "";
    }

    public static function inRange($value,$min,$max)
    {
        return $value >= $min && $value <= $max;
    }

} 