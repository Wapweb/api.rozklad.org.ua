<?php
/**
 * Created by PhpStorm.
 * User: Шаповал
 * Date: 14.09.14
 * Time: 13:27
 */

class GroupModel {
    public static $_db_name = "`group`";

    public $group_id;
    public $group_full_name;
    public $group_prefix;
    public $group_okr;
    public $group_type;
    public $group_url;
	
	/*public static $replace = array(
		'b'=>'в',
		'B'=>'в',
		'i'=>'і',
		'I'=>'і',
		'O'=>'о',
		'o'=>'о',
		'C'=>'с',
		'c'=>'с',
		'T'=>'т',
		't'=>'т',
		'P'=>'р',
		'p'=>'р',
		'A'=>'а',
		'a'=>'а',
		'Y'=>'у',
		'y'=>'у',
		'H'=>'н',
		'h'=>'н',
		'K'=>'к',
		'k'=>'к',
		'X'=>'х',
		'x'=>'х',
		'M'=>'М',
		'm'=>'м',
		'p'=>'п',
		'P'=>'п',
		'L'=>'л',
		'l'=>'л',
	);*/
	
    public static $replace = array(	
        "а"=>"a",
        "б"=>"b",
        "в"=>"v",
        "г"=>"g",
        "д"=>"d",
        "е"=>"e",
        "ж"=>"zh",
        "з"=>"z",
        "й"=>"y",
        "к"=>"k",
        "л"=>"l",
        "м"=>"m",
        "н"=>"n",
        "о"=>"o",
        "п"=>"p",
        "р"=>"r",
        "с"=>"s",
        "т"=>"t",
        "у"=>"u",
        "ф"=>"f",
        "х"=>"kh",
        "ц"=>"ts",
        "ч"=>"ch",
        "ш"=>"sh",
        "щ"=>"shch",
        "і"=>"i",
        "ю"=>"yu",
        "я"=>"ya",
    );

    public function __construct()
    {}


    public static function  getAll($offset = 0, $limit = 100)
    {
        /** @var PDO $db */
        $db = Registry::get('db');

        $count = $db->query("
            SELECT COUNT(*) FROM ".GroupModel::$_db_name." 
        ")->fetchColumn();

        $offset = abs(intval($offset));
        $limit = abs(intval($limit));
        if($limit < 1) $limit = 1;
        if($limit > 100) $limit = 100;

        $result = array();

        $query = $db->query("
            SELECT * FROM ".GroupModel::$_db_name." LIMIT $offset , $limit
        ");
        while($data = $query->fetch(PDO::FETCH_ASSOC))
        {
            $groupModel = new GroupModel();
            $groupModel->group_full_name = $data["group_full_name"];
            $groupModel->group_id = $data["group_id"];
            $groupModel->group_prefix = $data["group_prefix"];
            $groupModel->group_okr = $data["group_okr"];
            $groupModel->group_url = $data["group_url"];
            $groupModel->group_type = $data["group_type"];
            $result['data'][] = $groupModel->toArray();
        }
        $result['meta']['total_count'] = $count;
        $result['meta']['offset'] = $offset;
        $result['meta']['limit'] = $limit;

        return $result;
    }


    /**
     * @param $group_name
     * @return GroupModel|null
     */
    public static function getByName($group_name)
    {
		$group_name = mb_strtolower($group_name,"UTF-8");
		$group_name = str_replace(array_values(GroupModel::$replace), array_keys(GroupModel::$replace), $group_name);
	
        /** @var PDO $db */
        $db = Registry::get('db');
        $count = $db->query("
            SELECT COUNT(*) FROM ".GroupModel::$_db_name."
                WHERE group_full_name = ".$db->quote($group_name)." OR group_id = ".$db->quote($group_name)."
        ")->fetchColumn();

        if($count > 0)
        {
            $query = $db->query("
                SELECT * FROM ".GroupModel::$_db_name."
                    WHERE group_full_name = ".$db->quote($group_name)." OR group_id = ".$db->quote($group_name)."
            ");
            $data =  $query->fetch(PDO::FETCH_ASSOC);
            $groupModel = new GroupModel();
            $groupModel->group_full_name = $data["group_full_name"];
            $groupModel->group_id = $data["group_id"];
            $groupModel->group_prefix = $data["group_prefix"];
            $groupModel->group_okr = $data["group_okr"];
            $groupModel->group_url = $data["group_url"];
            $groupModel->group_type = $data["group_type"];
            return $groupModel->toArray();
        }
        else
        {
            throw new Exception("404 - Not found");
        }
    }

    /**
     * @param $group_name
     * @return GroupModel[]|array
     */
    public static function searchByName($group_name)
    {
		$group_name = trim($group_name);
		
		if(empty($group_name))
		{
			throw new Exception("404 - Not found");
		}
		
		$group_name = mb_strtolower($group_name,"UTF-8");
		$group_name = str_replace(array_values(GroupModel::$replace), array_keys(GroupModel::$replace), $group_name);
	
        /** @var PDO $db */
        $db = Registry::get('db');
        $count = $db->query("
            SELECT COUNT(*) FROM ".GroupModel::$_db_name."
                WHERE group_full_name LIKE ".$db->quote($group_name."%")."
        ")->fetchColumn();

        $result = array();
        if($count > 0)
        {
            $query = $db->query("
                SELECT * FROM ".GroupModel::$_db_name."
                    WHERE group_full_name LIKE ".$db->quote($group_name."%")."
            ");
            while($data = $query->fetch(PDO::FETCH_ASSOC))
            {
                $groupModel = new GroupModel();
                $groupModel->group_full_name = $data["group_full_name"];
                $groupModel->group_id = $data["group_id"];
                $groupModel->group_prefix = $data["group_prefix"];
                $groupModel->group_okr = $data["group_okr"];
                $groupModel->group_url = $data["group_url"];
                $groupModel->group_type = $data["group_type"];
                $result[] = $groupModel->toArray();
            }
        }
        else
        {
            throw new Exception("404 - Not found");
        }

        return $result;
    }

    public function toArray()
    {
        return array(
            "group_id" => $this->group_id,
            "group_full_name" => $this->group_full_name,
            "group_prefix" => $this->group_prefix,
            "group_okr" => $this->group_okr,
            "group_type" => $this->group_type,
            "group_url" => $this->group_url
        );
    }

} 