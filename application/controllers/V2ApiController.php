<?php
/**
 * Created by PhpStorm.
 * User: Шаповал
 * Date: 27.12.2014
 * Time: 22:23
 */

class V2ApiController extends BaseApiV2Controller {

    public function indexAction()
    {
        $view = new View();
        return $view->render("v2/index.php");
    }

    public function doc_teachersAction()
    {
        $view = new View();
        return $view->render("v2/doc_teachers.php");
    }

    public function doc_otherAction()
    {
        $view = new View();
        return $view->render("v2/doc_other.php");
    }

    public function doc_groupsAction()
    {
        $view = new View();
        return $view->render("v2/doc_groups.php");
    }

    /**
     * url GET /v2/weeks
     * @api
     * @return string
     */
    public function weeksAction()
    {
        $week_number = date("W");
        $this->data = $week_number%2 ==0 ? 1 : 2;
        return $this->send(200,Cache::NoCache);
    }

    /**
     * url GET /v2/groups
     * @api
     * @return string
     * @throws ApiException
     */
    public function groupsAction()
    {
        $allowFilters = ['offset'=>true,'limit'=>true];
        $allowSearch = ['query'=>true];

        $params = $this->_fc->getParams();
        $filter = $this->_fc->getFilter();
        $search = $this->_fc->getSearch();

        // check filter
        if($filter != null)
        {
            Utilities::checkFilters($filter,$allowFilters);
        }

        if($search != null)
        {
            Utilities::checkFilters($search,$allowSearch);
        }

        if(isset($params[0]))
        {
            if($search == null)
                $this->data = GroupModel::getByNameOrId($params[0]);
        }
        else
        {
            if($search == null)
            {
                $data = GroupModel::getAll($filter['offset'],$filter['limit']);
                $this->data = $data['data'];
                $this->meta = $data['meta'];
            }
            else
            {
                $search['query'] = str_replace('%','',$search['query']);
                $search['query'] = urldecode($search['query']);
                $this->data = GroupModel::searchByName($search['query']);
            }
        }

        return $this->send(200);
    }

    /**
     * url GET /v2/teachers
     * @api
     * @return string
     * @throws ApiException
     */
    public function teachersAction()
    {
        $allowFilters = ['offset'=>true,'limit'=>true];
        $allowSearch = ['query'=>true];

        $params = $this->_fc->getParams();
        $filter = $this->_fc->getFilter();
        $search = $this->_fc->getSearch();

        // check filter
        if($filter != null)
        {
            Utilities::checkFilters($filter,$allowFilters);
        }

        if($search != null)
        {
            Utilities::checkFilters($search,$allowSearch);
        }

        if(isset($params[0]))
        {
            if($search == null)
                $this->data = TeacherModel::getByNameOrId($params[0]);
        }
        else
        {
            if($search == null)
            {
                $data = TeacherModel::getAll($filter['offset'],$filter['limit']);
                $this->data = $data['data'];
                $this->meta = $data['meta'];
            }
            else
            {
                if(mb_strlen($search['query'],"UTF-8") < 3)
                {
                    $this->message = "Bad Request! Search query must be less than 2 symbols";
                    return $this->send(400,Cache::NoCache);
                }
                $search['query'] = urldecode($search['query']);
                $this->data = TeacherModel::searchByName($search['query']);
            }
        }

        return $this->send(200);
    }




    /**
     * url GET /v2/teachers/{teacher_name|teacher_id}/lessons
     * @api
     * @param array $data
     * @return string
     * @throws ApiException
     */
    public function teachers_lessonsRelationAction($data)
    {
        $data['teachers'] = urldecode($data['teachers']);
        $response = LessonModel::getAllByTeacherNameOrTeacherId($data['teachers']);
        $this->data = $response;
        return $this->send(200);
    }

    /**
     * url GET /v2/groups/{group_name|group_id}/timetable
     * @api
     * @param $data
     * @return string
     */
    public function groups_timetableRelationAction($data)
    {
        $data['groups'] = urldecode($data['groups']);
        $response = new TimetableModel($data['groups']);
        $this->data = $response->toArray();

        return $this->send(200);
    }


    /**
     * url GET /v2/groups/{group_name|group_id}/teachers
     * @api
     * @param $data
     * @return string
     * @throws ApiException
     */
    public function groups_teachersRelationAction($data)
    {
        $allowFilters = ['duplicateTeachersFilter'=>true];
        $filter = $this->_fc->getFilter();

        $group_name = $data["groups"];
        $group_name = urldecode($group_name);
        $teachers = TeacherModel::getAllByGroupNameOrGroupId($group_name);

        if($filter != null)
        {
            Utilities::checkFilters($filter,$allowFilters);
            $teachers = TeacherModel::teachersDuplicateFilter($teachers);
        }

        $this->data = $teachers;

        return $this->send(200);
    }

    /**
     * @api
     * @param $data
     * @return string
     * @throws ApiException
     */
    public function groups_lessonsRelationAction($data)
    {
        $filter = $this->_fc->getFilter();

        $condition = "";

        if($filter != null)
        {
            $allowFilters = [
                'day_number'=>true,
                'day_name'=>true,
                'lesson_number'=>true,
                'lesson_week'=>true,
                'lesson_type'=>true,
                'rate'=>true
            ];

            $filterContext = $this->_fc->getArrayDepth($filter) == 1
                ?
                new FilterContext(new AndFilterStrategy())
                :
                new FilterContext(new OrFilterStrategy());

            $condition = $filterContext->buildCondition($filter,$allowFilters);
        }

        $data["groups"] = urldecode($data["groups"]);
        $response = LessonModel::getAllByGroupNameOrGroupId($data['groups'],$condition);
        $this->debugInfo = "duplicateTeachersFilter is enable";
        $this->data = $response;

        return $this->send(200);
    }
} 