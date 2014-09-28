<?php
/**
 * Created by PhpStorm.
 * User: Шаповал
 * Date: 14.09.14
 * Time: 13:10
 */

class V1ApiController extends BaseApiController {

    public function indexAction()
    {
        $view = new View();
        return $view->render("index.php");
    }

    public function weeksAction()
    {
        $week_number = date("W");
        $this->data = $week_number%2 ==0 ? 1 : 2;
        return $this->send(200);
    }

    public function groupsAction()
    {
        $params = $this->_fc->getParams();
        $group_name = isset($params[0]) ? $params[0] : "";
        $group_name = urldecode($group_name);
		

        $lessons  = isset($params[1]) ? $params[1] : "";
        $methodName = "groups".ucfirst($lessons);
        if(method_exists($this,$methodName))
        {
            return $this->$methodName($group_name);
        }

        if(isset($_GET["q"]))
        {
            return $this->searchGroups(urldecode($_GET["q"]));
        }

        if((isset($_GET["offset"]) && isset($_GET['limit'])) || !$group_name)
        {
            $offset = isset($_GET['offset']) ? $_GET['offset'] : 0;
            $limit = isset($_GET['limit']) ? $_GET['limit'] : 100;
            return $this->getAllGroups($offset,$limit);
        }

        $groupModel = GroupModel::getByName($group_name);
        if($groupModel != null)
        {
            $this->data = $groupModel;
        }

        return $this->send(200);
    }

    private function searchGroups($searchValue)
    {
        $group_name = urldecode($searchValue);
        $this->data = GroupModel::searchByName($group_name);
        return $this->send(200);
    }

    private function getAllGroups($offset,$limit)
    {
        $this->data = GroupModel::getAll($offset,$limit);
        return $this->send(200);
    }

    private function groupsLessons($groupName)
    {
        $cond = "";
        if(isset($_GET["week"]))
        {
            $week = abs(intval($_GET["week"]));
            if($week > 2)
            {
                throw new Exception("404 - Not Found");
            }
            $cond.= " AND lesson_week = $week";
        }
        if(isset($_GET["day"]))
        {
            $day = abs(intval($_GET["day"]));
            if($day > 7)
            {
                throw new Exception("404 - Not Found");
            }
            $cond.= " AND day_number = $day";
        }
        $this->data = LessonModel::getAllByGroupName($groupName,$cond);
        return $this->send(200);
    }
} 