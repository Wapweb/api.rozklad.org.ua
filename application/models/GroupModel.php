<?php
/**
 * Created by PhpStorm.
 * User: Шаповал
 * Date: 14.09.14
 * Time: 13:27
 */

class GroupModel extends Model {
    const TABLE = "`group`";
    const PRIMARY_KEY = "`group_id`";

    public $group_id;
    public $group_full_name;
    public $group_prefix;
    public $group_okr;
    public $group_type;
    public $group_url;

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

    /**
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public static function getAll($offset = 0, $limit = 100)
    {
        /** @var PDO $db */
        $db = Registry::get('db');

        $count = $db->query("
            SELECT COUNT(*) FROM ".GroupModel::TABLE."
        ")->fetchColumn();

        $offset = isset($offset) ? abs(intval($offset)) : 0;
        $limit = isset($limit) ? abs(intval($limit)) : 100;
        if($limit < 1) $limit = 1;
        if($limit > 100) $limit = 100;

        $result = array();

        $query = $db->query("
            SELECT * FROM ".GroupModel::TABLE." LIMIT $offset , $limit
        ");
        while($data = $query->fetch(PDO::FETCH_ASSOC))
        {
            $groupModel = new GroupModel();
            $groupModel->unpack($data);
            $result['data'][] = $groupModel->toArray();
        }
        $result['meta']['total_count'] = $count;
        $result['meta']['offset'] = $offset;
        $result['meta']['limit'] = $limit;

        return $result;
    }


    /**
     * @param string|int $group_name
     * @return array
     * @throws ApiException
     */
    public static function getByNameOrId($group_name)
    {
		$group_name = mb_strtolower($group_name,"UTF-8");
        $group_name = urldecode($group_name);
		$group_name = str_replace(array_values(GroupModel::$replace), array_keys(GroupModel::$replace), $group_name);
	
        /** @var PDO $db */
        $db = Registry::get('db');
        $count = $db->query("
            SELECT COUNT(*) FROM ".GroupModel::TABLE."
                WHERE group_full_name = ".$db->quote($group_name)." OR group_id = ".$db->quote($group_name)."
        ")->fetchColumn();

        if($count > 0)
        {
            $query = $db->query("
                SELECT * FROM ".GroupModel::TABLE."
                    WHERE group_full_name = ".$db->quote($group_name)." OR group_id = ".$db->quote($group_name)."
            ");
            $data =  $query->fetch(PDO::FETCH_ASSOC);
            $groupModel = new GroupModel();
            $groupModel->unpack($data);
            return $groupModel->toArray();
        }
        else
        {
            throw new ApiException("Group not found");
        }
    }

    /**
     * @param string $group_name
     * @return array
     * @throws ApiException
     */
    public static function searchByName($group_name)
    {
		$group_name = trim($group_name);
		
		if(empty($group_name))
		{
			throw new ApiException("Group not found");
		}
		
		$group_name = mb_strtolower($group_name,"UTF-8");
		$group_name = str_replace(array_values(GroupModel::$replace), array_keys(GroupModel::$replace), $group_name);
	
        /** @var PDO $db */
        $db = Registry::get('db');
        $count = $db->query("
            SELECT COUNT(*) FROM ".GroupModel::TABLE."
                WHERE group_full_name LIKE ".$db->quote($group_name."%")."
        ")->fetchColumn();

        $result = array();
        if($count > 0)
        {
            $query = $db->query("
                SELECT * FROM ".GroupModel::TABLE."
                    WHERE group_full_name LIKE ".$db->quote($group_name."%")."
            ");
            while($data = $query->fetch(PDO::FETCH_ASSOC))
            {
                $groupModel = new GroupModel();
                $groupModel->unpack($data);
                $result[] = $groupModel->toArray();
            }
        }
        else
        {
            throw new ApiException("Group not found");
        }

        return $result;
    }

    /**
     * @param string $query
     * @return array
     * @throws ApiException
     */
    public static function searchByQuery($query)
    {
        $result = array();

        /** @var PDO $db */
        $db = Registry::get('db');

        $query = $db->query($query);
        while($data = $query->fetch(PDO::FETCH_ASSOC))
        {
            $groupModel = new GroupModel();
            $groupModel->unpack($data);
            $result[] = $groupModel->toArray();
        }

        if(!count($result))
            throw new ApiException("Groups not found");

        return $result;
    }

    public function toArray()
    {
        return array(
            "group_id" => abs(intval($this->group_id)),
            "group_full_name" => $this->group_full_name,
            "group_prefix" => $this->group_prefix,
            "group_okr" => $this->group_okr,
            "group_type" => $this->group_type,
            "group_url" => $this->group_url
        );
    }

    public function unpack($data)
    {
        $this->group_full_name = $data["group_full_name"];
        $this->group_id = $data["group_id"];
        $this->group_prefix = $data["group_prefix"];
        $this->group_okr = $data["group_okr"];
        $this->group_url = $data["group_url"];
        $this->group_type = $data["group_type"];
    }

} 