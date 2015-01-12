<?php
/**
 * Created by PhpStorm.
 * User: Шаповал
 * Date: 12.01.2015
 * Time: 1:13
 */

class VersionApiController extends BaseApiV2Controller {
    public function indexAction()
    {
        $this->data = array('currentVersion'=>"v2","previousVersion"=>"v1");
        return $this->send(200,Cache::NoCache);
    }
} 