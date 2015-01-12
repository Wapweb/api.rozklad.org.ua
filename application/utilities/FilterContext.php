<?php
/**
 * Created by PhpStorm.
 * User: Шаповал
 * Date: 12.01.2015
 * Time: 0:08
 */

class FilterContext {
    /** @var  FilterStrategy */
    private $f_filterStrategy;

    public function __construct(FilterStrategy $strategy)
    {
        $this->f_filterStrategy = $strategy;
    }

    /**
     * @param array $data
     * @param array $allow_filters
     * @throws ApiException
     * @return string
     */
    public function buildCondition(array $data, array $allow_filters = array())
    {
        return $this->f_filterStrategy->buildCondition($data,$allow_filters);
    }
} 