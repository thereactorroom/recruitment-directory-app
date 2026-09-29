<?php


final class DB extends PDO {

    /** @var Statement $stmt - PDO statement reference */
    private $stmt = null;

    /** @var Config $config - Config reference */
    private $config = null;

    /**
     * Default constructor - Create the PDO database connection
     * @param string $dbconfig - Database configuration section
     */
    public function __construct($config_name = "recruitment_directory") {
        try {
            $this->config = new Config;
            $config_name .= "_" . $this->config->getEnv();
            $config = $this->config->getConfig($config_name);
            $dsn = "$config[dbdriver]:host=$config[host];dbname=$config[dbname];port=$config[port]";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_FOUND_ROWS => true
            ];
            parent::__construct($dsn, $config["user"], $config["password"], $options);
        } catch (Exception $ex) {
            die($ex->getMessage());
        }
    }

    /**
     * prepareStatement - Prepare an SQL statement
     * @param string $statement - SQL statement
     */
    public function prepareStatement($statement) {
        $this->stmt = $this->prepare($statement);
    }

    /**
     * execute - Executes a query with the given parameters
     * @param array $input_parameters - Parameter associative array
     * @return boolean - If success, returns true, else false
     */
    public function execute($input_parameters = null) {
        if ($this->stmt) {
            return $this->stmt->execute($input_parameters);
        }
        return false;
    }

    /**
     * bindValue - Binds a value to a parameter in an SQL statement
     * @param string $parameter - Parameter name
     * @param mixed $value - Mixed value
     * @param string $type - Type of a paramter
     */
    public function bindValue($parameter, $value, $type = PDO::PARAM_STR) {
        if ($this->stmt) {
            $this->stmt->bindValue($parameter, $value, $type);
        }
    }

    /**
     * fetch - Returns a record from a select statement
     * @return array - An associative array
     */
    public function fetch() {
        if ($this->stmt) {
            return $this->stmt->fetch();
        }
        return null;
    }

    /**
     * fetchAll - Returns multiple records from a select statement
     * @return array - A 2D associative object array
     */
    public function fetchAll() {
        if ($this->stmt) {
            return $this->stmt->fetchAll();
        }
        return null;
    }

    /**
     * setFetchMode - Allows to change the data fetch mode
     * @param int $mode
     */
    public function setFetchMode($mode) {
        if (isset($mode) && !is_null($mode)) {
            if ($this->stmt) {
                $this->stmt->setFetchMode($mode);
            }
        }
    }

    /**
     * rowCount - Returns the affected row count
     * @return int - Number of rows
     */
    public function rowCount() {
        if ($this->stmt) {
            return $this->stmt->rowCount();
        }
        return 0;
    }

    /**
     * insertId - Returns the last insert id
     * @return int - insert id
     */
    public function insertId() {
        return $this->lastInsertId();
    }

    public function beginTransactions(){
        $this->beginTransaction();
    }

}



