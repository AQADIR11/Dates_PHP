<?php

class DP_Database
{

    // Database properties
    public $host     = HOST;
    public $driver   = DRIVER;
    public $user     = USER;
    public $password = PASSWORD;
    public $database = DATABASE;
    public $db;
    public $result;

    public function __construct()
    {
        if(CONNECTION === "YES"){
            try {
                $this->db = new PDO($this->driver . ":host=" . $this->host . ";dbname=" . $this->database, $this->user, $this->password);
                $this->db->exec("SET NAMES " . CHAR_SET . " COLLATE " . DATABASE_COLLECT);
            } catch (PDOException $e) {
                echo "Database connection error: " . $e->getMessage();
            }
        }
    }

    public function Query(string $query, array $params = [])
    {
        if (!empty($params)) {
            $this->result = $this->db->prepare($query);
            return $this->result->execute($params);
        } else {
            $this->result = $this->db->prepare($query);
            return $this->result->execute();
        }
    }

    public function NumRows()
    {
        return $this->result->rowCount();
    }

    public function fetch_object()
    {
        return $this->result->fetchAll(PDO::FETCH_OBJ);
    }

    public function fetch_array()
    {
        return $this->result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function last_insert_id()
    {
        return $this->db->lastInsertId();
    }

    public function getDataAll(string $table, string $fields = "*")
    {
        $this->result = $this->db->prepare("SELECT $fields FROM $table");
        if ($this->result->execute()) {
            return $this->result->execute();
        } else {
            return "No data found";
        }
    }

    public function getData(string $table, array $conditionArray, string $fields = '*')
    {
        $placeHolder = '';
        $values = array();
        $c = count($conditionArray);
        $i = 1;
        foreach ($conditionArray as $key => $where) {
            if ($i == $c) {
                $placeHolder .= " $key = ?";
                $values[] = $where;
            } else {
                $placeHolder .= " $key = ? AND ";
                $values[] = $where;
            }
            $i++;
        }
        $this->result = $this->db->prepare("SELECT $fields FROM $table WHERE $placeHolder");
        return $this->result->execute($values);
    }

    public function updateData(string $table, array $dataArray, array $conditionArray)
    {
        $sql = "UPDATE $table SET ";
        $values = array();
        $c = count($dataArray);
        $i = 1;
        foreach ($dataArray as $key => $value) {
            if ($i == $c) {
                $sql .= $key . ' = ? ';
            } else {
                $sql .= $key . ' = ?, ';
            }
            $values[] = $value;
            $i++;
        }
        $sql .= " WHERE";
        $j = count($conditionArray);
        $k = 1;
        foreach ($conditionArray as $key => $conditions) {
            if ($k == $j) {
                $sql .= " $key = ?";
            } else {
                $sql .= " $key = ? AND ";
            }
            $k++;
            array_push($values, $conditions);
        }
        $this->result = $this->db->prepare($sql);
        if ($this->result->execute($values)) {
            $response = true;
        } else {
            $response = false;
        }
        return $response;
    }

    public function joinQuery(string $table, string $columns, array $joins, array $condition = [])
    {

        $query = "SELECT $columns FROM $table ";
        if (is_array($joins) && count($joins) > 0) {
            foreach ($joins as $k => $v) {
                $query .= $v['jointype'] . " JOIN " . $v['table'] . " ON " . $v['condition'] . " ";
            }
        }
        if(is_array($condition) && count($condition) > 0){
            $query .= "WHERE ";
            $i = 0;
            foreach($condition as $k => $v){
                if($i == count($condition) -1){
                    $query .= "$k = '$v'";
                }else{
                    $query .= "$k = '$v' AND ";
                }
                $i++;
            }
        }
        return $this->Query($query);
    }

    public function insertData(string $table, array $dataArray)
    {
        $values = array();
        $placeHolder = '';
        $fields = array();
        $c = count($dataArray);
        $i = 1;
        foreach ($dataArray as $key => $value) {
            if ($i == $c) {
                $placeHolder .= '?';
            } else {
                $placeHolder .= '?, ';
            }
            $fields[] = $key;
            $values[] = $value;
            $i++;
        }
        $fieldsName = @implode(', ', $fields);
        $this->result = $this->db->prepare("INSERT INTO $table ($fieldsName)VALUES($placeHolder)");
        if ($this->result->execute($values)) {
            $insertId = $this->last_insert_id();
            $response['success'] = 'success';
            $response['insert_id'] = $insertId;
        } else {
            $response['error'] = 'error';
        }
        return array((object)$response);
    }
}
