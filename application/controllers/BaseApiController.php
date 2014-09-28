<?php
/**
 * Created by PhpStorm.
 * User: Шаповал
 * Date: 14.09.14
 * Time: 13:08
 */

abstract class BaseApiController  {
    const CACHE_DIR = "cache";

    protected $data = array();

    /** @var  FrontApiController $_fc */
    protected $_fc;

    public function __construct()
    {
        $this->_fc = FrontApiController::getInstance();
    }

    protected function send($status = 200)
    {
        header("HTTP/1.1 " . $status . " " . $this->requestStatus($status));
        header('Content-Type: application/json; charset=utf-8');
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
        $this->saveData();
        return json_encode($this->data,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    }

    private function requestStatus($code) {
        $status = array(
            200 => 'OK',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
        );
        return ($status[$code])?$status[$code]:$status[500];
    }

    private function saveData()
    {
        $fileName = md5($this->_fc->getQuery()).".cache";
        if(!file_exists(ROOT.DIRECTORY_SEPARATOR.self::CACHE_DIR.DIRECTORY_SEPARATOR.$fileName))
        {
            $data = serialize($this->data);
            file_put_contents(ROOT.DIRECTORY_SEPARATOR.self::CACHE_DIR.DIRECTORY_SEPARATOR.$fileName,$data);
        }
    }

    public static function getDataFromCache($query)
    {
        $fileName = md5($query).".cache";
        if(file_exists(ROOT.DIRECTORY_SEPARATOR.self::CACHE_DIR.DIRECTORY_SEPARATOR.$fileName))
        {
            $data = unserialize(file_get_contents(ROOT.DIRECTORY_SEPARATOR.self::CACHE_DIR.DIRECTORY_SEPARATOR.$fileName));
            return $data;
        }
        return null;
    }

} 