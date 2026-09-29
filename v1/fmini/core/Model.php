<?php

class Model {

    /** @var object $db - The database handler class reference */
    protected $db;

    /** @var array $fields - Table fields */
    protected $fields = null;

    /** @var string $table - The database table name */
    protected $table = null;

    /** @var string $schema - The database schema name */
    protected $schema = null;
    
    public function __construct($table, $db = null) {
        if (isset($table) && !empty($table)) {
            $this->db = $db;
            if (is_null($db)){
                $this->db = new DB();
            }
            $this->table = $table;
            $this->setFields();
        } 
    }

    /**
     * setFields - set database table columns into fields array  
     *
     * @return array - An array of fields
     */
    protected function setFields() {
        $this->db->prepareStatement("DESC {$this->table}");
        $this->db->execute();
        $results = $this->db->fetchAll();        
        foreach ($results as $row) {
            if ($row->Key == 'PRI') {
                $this->fields['pk'] = $row->Field;
            } else {
                $this->fields[] = $row->Field;
            }
        }
    }

    /**
     * select - Get all records or by PK
     *
     * @param int $pk - Primary key
     * @return array - a list of records
     */
    public function get($pk = null) {
        if (!is_null($pk)) {
            $this->db->prepareStatement("SELECT * FROM $this->table WHERE {$this->fields['pk']} = :{$this->fields['pk']}");
            $this->db->execute(["{$this->fields['pk']}" => $pk]);
            return $this->db->fetch();
        } else {
            $this->db->prepareStatement("SELECT * FROM $this->table");
            $this->db->execute();
			return $this->db->fetchAll();
        }
    }

    /**
     * find - Get record based on the where parameters
     *
     * @param array $data - assoc array with where params
     * @return object - @return object - an object record
     */
    public function find($data) {
        if (isset($data) && is_array($data)) {
            $where = "";
            foreach ($data as $key => $value) {
                if (in_array($key, $this->fields)) {
                    $where .= "$key = :$key AND ";
                }
            }
            $where = rtrim($where, 'AND ');
            $this->db->prepareStatement("SELECT * FROM $this->table WHERE {$where}");
            $this->db->execute($data);
            return $this->db->fetch();
        }
        return null;
    }

    /**
     * findAll - Get record based on the where parameters
     *
     * @param array $data - assoc array with where params
     * @return array - @return [object] - a list of records
     */
    public function findAll($data) {
        if (isset($data) && is_array($data)) {
            $where = "";
            foreach ($data as $key => $value) {
                if (in_array($key, $this->fields)) {
                    $where .= "$key = :$key AND ";
                }
            }
            $where = rtrim($where, 'AND ');
            $this->db->prepareStatement("SELECT * FROM $this->table WHERE {$where}");
            $this->db->execute($data);
            return $this->db->fetchAll();
        }
        return [];
    }

    /**
     * insert - Insert a new record
     * @param array $data - Associative array containing information to insert
     */
    public function insert($data) {
        if (isset($data) && is_array($data)) {
            $colums = implode(", ", array_keys($data));
            $values = ":" . implode(", :", array_keys($data));
            $this->db->prepareStatement("INSERT INTO $this->table ($colums) VALUES ($values)");
            $this->db->execute($data);
            return $this->db->insertId();
        }
        return false;
    }

    /**
     * insertBulk - Allows to insert multiple records at once, this is done by using the same 
     * prepared statement, it saves time and db resources since it does not prepare a new statement 
     * when inserting a new record
     * @param array $data - 2D array containing information to insert
     */
    public function insertBulk($data) {
        if (isset($data) && is_array($data)) {
            $colums = implode(", ", array_keys($data[0]));
            $values = ":" . implode(", :", array_keys($data[0]));
            $this->db->prepareStatement("INSERT INTO $this->table ($colums) VALUES ($values)");
            foreach($data as $item) {
                $this->db->execute($item);
            }
        }
        return false;
    }

    /**
     * update - Update records by primary key
     * @param array $data - Associative array containing information to update
     * @return int - Return the count of affected rows
     */
    public function update($data) {
        if (isset($data) && is_array($data)) {
            $updateList = "";
            $where = "";
            foreach ($data as $key => $value) {
                if (in_array($key, $this->fields)) {
                    if ($key === $this->fields['pk']) {
                        $where = "$key = :$key";
                    } else {
                        $updateList .= "$key = :$key, ";
                    }
                }
            }
            $updateList = rtrim($updateList, ', ');
            $u_query = "UPDATE $this->table SET $updateList WHERE $where";
            $this->db->prepareStatement($u_query);
            $this->db->execute($data);
            return $this->db->rowCount();
        }
        return 0;
    }

    public function updateBulk($data) {
        if (isset($data) && is_array($data)) {
            $updateList = "";
            $where = "";
            foreach ($data[0] as $key => $value) {
                if (in_array($key, $this->fields)) {
                    if ($key === $this->fields['pk']) {
                        $where = "$key = :$key";
                    } else {
                        $updateList .= "$key = :$key, ";
                    }
                }
            }
            $updateList = rtrim($updateList, ', ');
            $u_query = "UPDATE $this->table SET $updateList WHERE $where";
            $this->db->prepareStatement($u_query);
            // $this->db->execute($data);
            foreach($data as $item) {
                $this->db->execute($item);
            }
            return $this->db->rowCount();
        }
        return 0;
    }

    /**
     * delete - Delete records
     * @param int $pk - Primary key where to delete
     * @return int - Return the count of deleted records
     */
    public function delete($data) {
        if (isset($data)) {
            $where = "";
            $params = [];
            if (is_array($data)) {
                $params = $data;
                foreach ($data as $key => $value) {
                    if (in_array($key, $this->fields)) {
                        $where .= "$key = :$key AND ";
                    }
                } 
                $where = rtrim($where, " AND ");
            } else {
                $params = ["{$this->fields['pk']}" => $data];
                $where = "{$this->fields['pk']} = :{$this->fields['pk']}";
            }
            $query = "DELETE FROM $this->table WHERE $where";
            $this->db->prepareStatement($query);
            $this->db->execute($params);
            return $this->db->rowCount();
        }
        return 0;
    }

    /**
     * hide - Hide records by setting the deleted field to 1, i.e) soft delete
     * @param int $pk - Primary key where to hide
     * @return mixed - Return the count of affected records else null
     */
    public function hide($pk) {
        if (isset($pk)) {
            $u_query = "UPDATE $this->table SET deleted = :deleted WHERE {$this->fields['pk']} = :{$this->fields['pk']}";
            $this->db->prepareStatement($u_query);
            $this->db->execute(["deleted" => 1, "{$this->fields['pk']}" => $pk]);
            return $this->db->rowCount();
        }
        return 0;
    }

    /**
     * unhide - Unhide hiden records by setting the deleted field to 0, i.e) reverse soft delete
     * @param int $pk - Primary key where to retrieve
     * @return mixed - Return the count of affected records else null
     */
    public function unhide($pk) {
        if (isset($pk)) {
            $u_query = "UPDATE $this->table SET deleted = :deleted WHERE {$this->fields['pk']} = :{$this->fields['pk']}";
            $this->db->prepareStatement($u_query);
            $this->db->execute(["deleted" => 0, "{$this->fields['pk']}" => $pk]);
            return $this->db->rowCount();
        }
        return 0;
    }

}
