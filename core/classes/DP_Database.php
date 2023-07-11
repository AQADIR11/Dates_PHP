<?php

class DP_Database
{

    // Database properties

   /**
     * database hostname;
     *
     * @var string
     */

    private $host     = HOST;

    /**
     * database driver;
     *
     * @var string
     */

    private $driver   = DRIVER;

    /**
     * database username;
     *
     * @var string
     */

    private $user     = USER;

    /**
     * database password;
     *
     * @var string
     */

    private $password = PASSWORD;

    /**
     * database name;
     *
     * @var string
     */

    private $database = DATABASE;

    /**
     * database connection object;
     * @var object
     */

    public $db;

    /**
     * database global query object;
     * @var object
     */

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

    /**
     * executing the database query
     * @param string $query string off the query
     * @param array $params placeholders parameters
     * @return [bool] return the query executed true or false
     * 
     */

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

     /**
     * get the executed query number of rows
     * @return integer number of rows
     * 
     */

    public function NumRows()
    {
        return $this->result->rowCount();
    }

    /**
     * get the executed query data
     * @return [object] data object
     * 
     */

    public function fetch_object()
    {
        return $this->result->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * get the executed query data
     * @return array data array
     * 
     */

    public function fetch_array()
    {
        return $this->result->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * get the last inserted data id
     * @return integer last inserted id
     * 
     */

    public function last_insert_id()
    {
        return $this->db->lastInsertId();
    }


    /**
     * get the data without query execute
     * @param string $table name of the table you want to select from
     * @param string $fields table column
     * @return bool return the boolean
     * 
     */

    public function getDataAll(string $table, string $fields = "*")
    {
        $this->result = $this->db->prepare("SELECT $fields FROM $table");
        if ($this->result->execute()) {
            return $this->result->execute();
        } else {
            return false;
        }
    }

    /**
     * get the conditional data without query execute
     * @param string $table name of the table you want to select from
     * @param array $conditionArray array of where clouse conditions 
     * @param string $fields names of the column you want select
     * @return bool return the boolean
     * 
     */

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

    /**
     * Update records without query execute
     * @param string $table name of the table you want to select from
     * @param array $dataArray data array which data you want to update
     * @param array $conditionArray array of where clouse conditions 
     * @return bool return the boolean
     * 
     */

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

    /**
     * Get data from multiple tables using joinQuery mathed
     * @param string $table name of the table you want to select from
     * @param string $columns names of the column you want select
     * @param array $joins Multidimensional array of join tables with join condition and join type
     * @param array $condition array of where clouse conditions 
     * @return bool return the boolean
     * 
     */

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

    /**
     * insert data into table
     * @param string $table name of the table you want to select from
     * @param array $dataArray array of data you want to insert data
     * @return array<object> return the response object with last insert id
     * 
     */

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
