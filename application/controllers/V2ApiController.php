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
        $allowFilters = ['offset'=>true,'limit'=>true,'showAll'=> true,'showProperties'=>true,'simpleResult'=>true];
        $allowSearch = ['query'=>true];

        $params = $this->_fc->getParams();
        $filter = $this->_fc->getFilter();
        $search = $this->_fc->getSearch();

        // check filters
        Utilities::checkFilters($filter,$allowFilters);
        Utilities::checkFilters($search,$allowSearch);

        if(isset($params[0]))
        {
            if($search == null)
                $this->data = GroupModel::getByNameOrId($params[0]);
        }
        else
        {
            if($search == null)
            {
                $showProperties = null;
                $hideProperties = null;
                $hidePropertyNames = false;

                if(isset($filter["showAll"]))
                {
                    $offset = null;
                    $limit = null;
                    //$showProperties = ['group_full_name'=>true];
                    $showProperties = is_array($filter["showProperties"]) ? $filter["showProperties"] : [];
                    $hidePropertyNames = isset($filter["simpleResult"]) ? $filter["simpleResult"] : false;
                }
                else
                {
                    $offset = isset($filter['offset']) ? abs(intval($filter['offset'])) : 0;
                    $limit = isset($filter['limit']) ? abs(intval( $filter['limit'])) : 100;

                    if($limit < 1) $limit = 1;
                    if($limit > 100) $limit = 100;
                }


                $data = GroupModel::getAll($offset,$limit,$showProperties, $hideProperties, $hidePropertyNames);
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
        $allowFilters = ['offset'=>true,'limit'=>true,'showAll'=> true,'showProperties'=>true,'simpleResult'=>true];
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
                $showProperties = null;
                $hideProperties = null;
                $hidePropertyNames = false;

                if(isset($filter["showAll"]))
                {
                    $offset = null;
                    $limit = null;
                    //$showProperties = ['group_full_name'=>true];
                    $showProperties = is_array($filter["showProperties"]) ? $filter["showProperties"] : [];
                    $hidePropertyNames = isset($filter["simpleResult"]) ? $filter["simpleResult"] : false;
                }
                else
                {
                    $offset = isset($filter['offset']) ? abs(intval($filter['offset'])) : 0;
                    $limit = isset($filter['limit']) ? abs(intval( $filter['limit'])) : 100;

                    if($limit < 1) $limit = 1;
                    if($limit > 100) $limit = 100;
                }

                $data = TeacherModel::getAll($offset,$limit,$showProperties,$hideProperties,$hidePropertyNames);
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
     * url GET /v2/teachers/{teacher_name|teacher_id}/canvote
     * @api
     * @param array $data
     * @return string
     * @throws ApiException
     */
    public function teachers_canvoteRelationAction($data)
    {
        $teacherIdOrName = urldecode($data["teachers"]);

        /** @var TeacherModel $teacher */
        $teacher = TeacherModel::getByNameOrId($teacherIdOrName,true);

        $vote = new TeacherVoteModel();
        $vote->teacherId = $teacher->teacher_id;
        $vote->userIp = ip2long(Utilities::getRealIp());
        $vote->userAgent = Utilities::getUserAgent();

        $result = $vote->canVote();

        $this->data = $result;
        return $this->send(200,Cache::NoCache);
    }

    /**
     * @param array $data
     * @return string
     * @throws ApiException
     */
    public function teachers_ratingRelationAction($data)
    {
        $teacherIdOrName = urldecode($data["teachers"]);

        /** @var TeacherModel $teacher */
        $teacher = TeacherModel::getByNameOrId($teacherIdOrName,true);
        $rating = TeacherRatingModel::getTeacherAvgRatings($teacher->teacher_id);
        $this->data = $rating->toArray();

        return $this->send(200,Cache::NoCache);
    }


    /**
     * url POST /v2/teachers/{teacher_name|teacher_id}/vote
     * parameters: mark_knowledge_subject,mark_exactingess,mark_relation_to_the_student,mark_sense_of_humor
     * @api
     * @param array $data
     * @return string
     * @throws ApiException
     */
    public function teachers_voteRelationAction($data)
    {
        if(isset($_SERVER["HTTP_ACCESS_CONTROL_REQUEST_METHOD"]))
        {
            if($_SERVER["HTTP_ACCESS_CONTROL_REQUEST_METHOD" != "POST"])
            {
                throw new ApiException("Bad request method (POST only)!",400);
            }
        }

        if(isset($_SERVER["REQUEST_METHOD"]) || isset($_SERVER["HTTP_ACCESS_CONTROL_REQUEST_METHOD"]))
        {
            if($_SERVER["REQUEST_METHOD"] != "POST")
            {
                throw new ApiException("Bad request method (POST only)!",400);
            }
        }


        $mark1 = isset($_REQUEST["mark_knowledge_subject"]) ? floatval($_REQUEST["mark_knowledge_subject"]) : 0;
        $mark2 = isset($_REQUEST["mark_exactingness"]) ? floatval($_REQUEST["mark_exactingness"]) : 0;
        $mark3 = isset($_REQUEST["mark_relation_to_the_student"]) ? floatval($_REQUEST["mark_relation_to_the_student"]) : 0;
        $mark4 = isset($_REQUEST["mark_sense_of_humor"]) ? floatval($_REQUEST["mark_sense_of_humor"]) : 0;

        if(
            !Utilities::inRange($mark1,1,5) ||
            !Utilities::inRange($mark2,1,5) ||
            !Utilities::inRange($mark3,1,5) ||
            !Utilities::inRange($mark4,1,5)
        )
        {
            throw new ApiException("Invalid parameters: all marks values must be between 1 and 5",400);
        }

        $teacherIdOrName = urldecode($data["teachers"]);

        /** @var TeacherModel $teacher */
        $teacher = TeacherModel::getByNameOrId($teacherIdOrName,true);

        $vote = new TeacherVoteModel();
        $vote->teacherId = $teacher->teacher_id;
        $vote->ratingMark1 = $mark1;
        $vote->ratingMark2 = $mark2;
        $vote->ratingMark3 = $mark3;
        $vote->ratingMark4 = $mark4;
        $vote->userIp = ip2long(Utilities::getRealIp());
        $vote->userAgent = Utilities::getUserAgent();
        $vote->ratingMarkAvg = round(($mark1+$mark3)/2,2);
        $vote->userDatetime = time();

        if(!$vote->canVote())
        {
            throw new ApiException("You have already voted!",400);
        }

        // add new vote
        $vote->save();

        //update teacher rating
        $teacher->updateRating($vote);
        $teacher->save();

        $this->message = "Created";
        $result = array();
        $result["teacher"] = $teacher->toArray();
        $result["vote"] = $vote->toArray();
        $result["rating"] = TeacherRatingModel::getTeacherAvgRatings($teacher->teacher_id)->toArray();
        $this->data = $result;

        return $this->send(201,Cache::NoCache);
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