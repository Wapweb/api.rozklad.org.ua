<?php
/**
 * Created by PhpStorm.
 * User: Шаповал
 * Date: 27.12.2014
 * Time: 23:58
 */

class ApiException extends Exception{
    public function __construct($message, $code = 404, Exception $previous = null) {
        $status = $code;
        header("HTTP/1.1 " . $status . " ".BaseApiV2Controller::requestStatus($status) );
        header('Content-Type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Credentials: true');
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
        $data = json_encode(['statusCode'=>$status,'timeStamp'=>time(),'message'=>$message],JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        $message = $data;

        parent::__construct($message, $code, $previous);
    }
} 