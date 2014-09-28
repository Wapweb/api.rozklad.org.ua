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

    private static $_instance;

    public static function getInstance() {
        if(!(self::$_instance instanceOf self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct() {
        $request = $_SERVER['REQUEST_URI'];
        $this->_query = $request;

        $splits = explode('/',trim($request,'/'));
        $this->_controller = !empty($splits[0]) ? ucfirst($splits[0]).'ApiController': 'V1ApiController';


        $this->_action = !empty($splits[1]) ? $splits[1].'Action' : 'indexAction';


        if(!empty($splits[2])) {
            for($i=2;$i<count($splits);$i++) $this->_params[] = $splits[$i];
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
} 