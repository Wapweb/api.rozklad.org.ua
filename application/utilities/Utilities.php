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

} 