<?php
/**
 * Created by PhpStorm.
 * User: Шаповал
 * Date: 11.01.2015
 * Time: 22:59
 */

abstract class BaseApiV2Controller  {
    const CACHE_DIR = "cache";

    protected $data = array();
    protected $message = 'Ok';
    protected $meta;
    protected $debugInfo = null;

    /** @var  FrontApiController $_fc */
    protected $_fc;

    public function __construct()
    {
        $this->_fc = FrontApiController::getInstance();
    }

    protected function send($status = 200, $isCache = Cache::CanCache)
    {
        header("HTTP/1.1 " . $status . " " . self::requestStatus($status));
        header('Content-Type: application/json; charset=utf-8');
        header('Access-Control-Allow-Credentials: true');
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
        $response = [];
        $response['statusCode'] = $status;
        $response['timeStamp'] = time();
        $response['message'] = $this->message;
        $response['debugInfo'] = $this->debugInfo;
        $response['meta'] = $this->meta;
        $response['data'] = $this->data;

        //if($isCache == Cache::CanCache)
           // $this->saveData($response);


        return json_encode($response,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    }

    public static function requestStatus($code) {
        $status = array(
            200 => 'OK',
            400 => 'Bad Request',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
            201 => 'Created'
        );
        return ($status[$code])?$status[$code]:$status[500];
    }

    private function saveData($data)
    {
        $fileName = md5($this->_fc->getQuery()).".cache";
        if(!file_exists(ROOT.DIRECTORY_SEPARATOR.self::CACHE_DIR.DIRECTORY_SEPARATOR.$fileName))
        {
            $data = serialize($data);
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