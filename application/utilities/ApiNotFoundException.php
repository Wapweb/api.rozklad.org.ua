<?php
/**
 * Created by PhpStorm.
 * User: Шаповал
 * Date: 27.12.2014
 * Time: 23:58
 */

class ApiNotFoundException extends Exception{
    // Переопределим исключение так, что параметр message станет обязательным
    public function __construct($message, $code = 0, Exception $previous = null) {
        $status = 404;
        header("HTTP/1.1 " . $status . " OK" );
        header('Content-Type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
        $data = json_encode(['statusCode'=>$status,'timeStamp'=>time(),'message'=>$message],JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        $message = $data;

        // убедитесь, что все передаваемые параметры верны
        parent::__construct($message, $code, $previous);
    }
} 