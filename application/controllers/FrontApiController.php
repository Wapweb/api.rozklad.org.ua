<?php
/**
 * Created by PhpStorm.
 * User: Шаповал
 * Date: 14.09.14
 * Time: 12:53
 */

class FrontApiController {

    protected $_controller;
    protected $_action;
    protected $_params;
    protected $_query;
    protected $_actionParameter;
    protected $_filter;
    protected $_search;
    protected $_filterType;

    private static $_instance;

    public static function getInstance() {
        if(!(self::$_instance instanceOf self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function getArrayDepth(array $array) {
        $max_depth = 1;

        foreach ($array as $value) {
            if (is_array($value)) {
                $depth = $this->getArrayDepth($value) + 1;

                if ($depth > $max_depth) {
                    $max_depth = $depth;
                }
            }
        }

        return $max_depth;
    }

    private function __construct() {
        $request = $_SERVER['REQUEST_URI'];
        $this->_query = $request;
        $request = parse_url($request,PHP_URL_PATH);

        if(isset($_GET["filter"]))
        {

            $filter = str_replace("'",'"',$_GET["filter"]);
            $filter = urldecode($filter);
            $this->_filter = json_decode($filter,true,3);
        }

        if(isset($_GET['search']))
        {
            $search = str_replace("'",'"',$_GET["search"]);
            $search = urldecode($search);
            $this->_search = json_decode($search,true,3);

        }
        //$this->_search = isset($_GET['search']) ? json_decode($_GET['search'],true) : null;
        $this->_filterType = isset($_GET['filter_type']) ? $_GET['filter_type'] : "and";
        $this->_filterType = $this->_filterType == "and" ? "and" : "or";

        $splits = explode('/',trim($request,'/'));
        $this->_controller = !empty($splits[0]) ? ucfirst($splits[0]).'ApiController': 'V2ApiController';


        $this->_action = !empty($splits[1]) ? $splits[1].'Action' : 'indexAction';

        $startParams = 2;
        if(isset($splits[3])) {
            $parameters = [];
            $relationAction = '';
            for($i = 3; $i < count($splits); $i++)
            {
                if($i%2 != 0)
                {
                    $relationAction = $splits[$i].'RelationAction';
                    $startParams = $i+1;
                }

                if(($i-2)%2 !=0)
                {
                    $parameters[$splits[$i-2]] = $splits[$i-1];
                }
            }
            $this->_action = $splits[1]."_".$relationAction;
            $this->_actionParameter = $parameters;

            //$this->_action  = $splits[3].'RelationAction';
            //$this->_actionParameter = $splits[2];
        }


        if(!empty($splits[$startParams])) {
            for($i=$startParams;$i<count($splits);$i++) $this->_params[] = $splits[$i];
        }
    }


    public function processApi() {
        if(!file_exists(ROOT.DIRECTORY_SEPARATOR.'application/controllers/'.$this->_controller.'.php')) {
            throw new Exception('ApiController not found');
        }

        if(class_exists($this->_controller)) {
            $rc = new ReflectionClass($this->_controller);
            if($rc->hasMethod($this->_action)) {
                $controller = $rc->newInstance();
                $method = $rc->getMethod($this->_action);

                if(isset($this->_actionParameter))
                    return $method->invoke($controller,$this->_actionParameter);

                return $method->invoke($controller);
            } else {
                throw new Exception('Wrong Action');
            }
        } else {
            throw new Exception('Wrong Controller');
        }
    }

    public function getParams()
    {
        return $this->_params;
    }

    public function getQuery()
    {
        return $this->_query;
    }

    public function getFilter()
    {
        return $this->_filter;
    }

    public function getSearch()
    {
        return $this->_search;
    }

    public function getFilterType()
    {
        return $this->_filterType;
    }
} 