<?php

/**
 * Created by PhpStorm.
 * User: George Gomez
 * Date: 3/30/2017
 * Time: 9:57 AM
 */
class Database
{
    private $host;
    private $db;
    private $user;
    private $pass;

    private $dbh;
    private $error;

    private $stmt;
    private $cmd;

    public function __construct()
    {
        $this->_setCred();
        $dsn = "mysql:host=".$this->host.";	";
        $options = [
            PDO::ATTR_PERSISTENT    => true,
            PDO::ATTR_ERRMODE       => PDO::ERRMODE_EXCEPTION
        ];
        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
            $this->stmt = $this->dbh->prepare("use ".$this->db);
            $this->stmt->execute();
        }catch(PDOException $e) {
            $this->error = $e->getMessage();
            var_dump($this->error);
        };
    }

    /**
     * @param array $db
     * Set Database Credentials
     */
    private function _setCred()
    {
        $this->host = HOST;
        $this->db = DATABASE;
        $this->user = USER;
        $this->pass = PASS;
    }

    public function checkColumnExists($table, $column)
    {
        $this->stmt = $this->dbh->prepare("
            SHOW COLUMNS FROM {$table}
            LIKE '{$column}'
        ");
        $this->stmt->execute();
        $rowCount = $this->stmt->rowCount();
        return ($rowCount > 0) ? true : false;
    }

    /**
     * @param $sql
     * @param array $array
     * @param int $fetchMode
     * @return array
     */
    public function select($sql, $array = array(), $fetchMode = PDO::FETCH_ASSOC)
    {
        $this->stmt = $this->dbh->prepare($sql);
        foreach ($array as $key => $value) {
            $this->stmt->bindValue(":$key", $value);
        }
        $this->stmt->execute();
        $rows = $this->stmt->fetchAll($fetchMode);
        if (count($rows) === 1)
        {
            return $rows[0];
        }
        else
        {
            return $rows;
        }
    }

    /**
     * @param $table
     * @param $where
     * @param array $array
     * @return $this
     */
    public function update($table, $where, $array = array())
    {
        ksort($array);
        $setn = array();
        foreach($array as $key => $value){
            $setn[] = $key.' = :'.$key;
        }
        $set = implode(", ", $setn);
        if($where === "all")
        {
            $this->stmt = $this->dbh->prepare("update $table set $set");
        }else{
            $this->stmt = $this->dbh->prepare("UPDATE $table SET $set WHERE $where");
        }
        foreach ($array as $key => $value) {
            $this->stmt->bindValue(":$key", $value);
        }
        return $this->stmt->execute();
    }

    /**
     * @param $table
     * @param array $array
     */
    public function updateAllRows($table, $array = [])
    {
        ksort($array);
        $setn = [];
        foreach($array as $key => $value){
            $setn[] = $key.' = :'.$key;
        }
        $set = implode(", ", $setn);
        $this->stmt = $this->dbh->prepare("update $table set $set");
        foreach ($array as $key => $value) {
            $this->stmt->bindValue(":$key", $value);
        }
        return $this->stmt->execute();
    }

    /**
     * @param $table
     * @param $where
     * @param array $array
     */
    public function updateTwo($table, $where, $array = array())
    {
        ksort($array);
        ksort($where);

        $setn = array();
        foreach($array as $key => $value){
            $setn[] = $key.' = :'.$key;
        }
        foreach($where as $key => $value)
        {
            $whereNew[] = $key.' = :'.$key.' ';
        }
        $set = implode(", ", $setn);
        $whereNew = implode('AND ', $whereNew);

        $finArray = array_merge($array, $where);;

        $this->stmt = $this->dbh->prepare("UPDATE $table SET $set WHERE $whereNew");
        foreach ($finArray as $key => $value) {
            $this->stmt->bindValue(":$key", $value);
        }

        $this->stmt->execute();
    }

    /**
     * @param $sql
     * @param array $array
     * @return bool
     */
    public function returnRowCount($sql, $array = array())
    {
        $this->stmt = $this->dbh->prepare($sql);
        foreach ($array as $key => $value) {
            $this->stmt->bindValue(":$key", $value);
        }
        $this->stmt->execute();
        $rowCount = $this->stmt->rowCount();
        return ($rowCount > 0) ? true : false;
    }

    /**
     * @param $table
     * @return int
     */
    public function getRowCount($table)
    {
        $this->stmt = $this->dbh->prepare("SELECT * FROM $table");
        $this->stmt->execute();
        $rowCount = $this->stmt->rowCount();
        return $rowCount;
    }

    /**
     * @param $sql
     * @param array $array
     * @return int
     */
    public function getRowCountCond($sql, $array = array())
    {
        $this->stmt = $this->dbh->prepare($sql);
        foreach ($array as $key => $value) {
            $this->stmt->bindValue(":$key", $value);
        }

        $this->stmt->execute();
        $rowCount = $this->stmt->rowCount();
        return $rowCount;
    }

    /**
     * @param $table
     * @param $data
     * @return mixed
     */
    public function insert($table, $data)
    {
        ksort($data);

        $fieldNames = implode(", ", array_keys($data));
        $fieldValues = ':'. implode(', :', array_keys($data));
        $this->stmt = $this->dbh->prepare("INSERT INTO $table ($fieldNames) VALUES ($fieldValues)");
        foreach ($data as $key => $value) {
            $this->stmt->bindValue(":$key", $value);
        }
        $this->stmt->execute();
        $id = $this->dbh->lastInsertId();
        $newRow = $this->select("select * from ".$table." where ".$table."_id = :id",array("id"=>$id));
        return $newRow[0];
    }

    /**
     * @param $table
     * @param $where
     * @param array $array
     */
    public function delete($table, $where, $array = array())
    {
        $this->stmt = $this->dbh->prepare("DELETE FROM $table WHERE $where");
        foreach ($array as $key => $value) {
            $this->stmt->bindValue(":$key", $value);
        }
        return $this->stmt->execute();
    }

    /**
     * @param $table
     */
    public function deleteAll($table)
    {
        $this->stmt = $this->dbh->prepare("DELETE FROM $table");
        return $this->stmt->execute();
    }
}
