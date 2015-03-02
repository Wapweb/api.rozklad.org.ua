<?php
/**
 * Created by PhpStorm.
 * User: Шаповал
 * Date: 22.01.2015
 * Time: 1:53
 */

abstract class Model {
    /** @var PDO $_db */
    protected  static $_db;
    protected  static $_tableName;
    protected static $_primaryKey;
    private $_id;

    public  abstract function unpack($data);
    protected abstract  function pack();

    public function __construct() {
        static::$_db = Registry::get("db");
    }

    public function save() {
        $id = static::getId();
        $data = static::pack();
        $query = "";

        if($id > 0)
            $query = "UPDATE ".static::$_tableName." SET";
        else
            $query = "INSERT INTO ".static::$_tableName." SET";

        $parameters = array();
        $count = count($data);
        $i=0;
        foreach($data as $key => $value) {
            $i++;
            if($key != static::$_primaryKey) {
                $query.= " `{$key}` = :{$key}";
                if($i < $count) $query.= ",";
                $parameters[":$key"] = $value;
            }
        }

        if($id) {
            $query.= " WHERE ".static::$_primaryKey." = :".static::$_primaryKey;
            $parameters[":".static::$_primaryKey] = $data[static::$_primaryKey];
        }

        $sth = self::$_db->prepare($query);
        $result = $sth->execute($parameters);
        if($result === false) {
            $arr = $sth->errorInfo();
            throw new Exception($arr[2]);
        }
        if(!$id)
            $this->setId(self::$_db->lastInsertId());

        return true;
    }

    public function getId() {
        return $this->_id;
    }
    public function setId($id) {
        $this->_id = (int)$id;
    }
} 