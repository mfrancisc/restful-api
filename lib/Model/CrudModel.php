<?php

/**
 * Class CrudModel
 * defines abstraction for
 * CRUD operations
 */
abstract class CrudModel
{
    /**
     * @var string
     */
    private $query;

    /**
     * CrudModel constructor.
     */
    public function __construct()
    {
        return $this;
    }

    /**
     * @return bool|string
     */
    public function delete()
    {

        try {
            $this->query = "DELETE FROM " . $this->tableName();
            $this->query .= " WHERE id= " . $this->getId();
            $exec = $this->_executeQuery($this->query);

            return $exec;
        } catch (PDOException $e) {
            return $e->getMessage();
        }

    }

    /****************************************
     * Implement the following methods
     * in your concrete Model
     * -------------------------------------/

    /**
     * return the db table name
     * @return mixed
     */
    abstract protected function tableName();

    /**
     * return row id
     * @return mixed
     */
    abstract protected function getId();

    /**
     * return editable fields
     * @return mixed
     */
    abstract protected function getFields();
    /** ---------------------------------- */

    /**
     * @param $sql string
     * @param array $values
     * @return bool
     */
    private function _executeQuery($sql, $values = array())
    {
        $dbConn = $this->_getDbConn();
        $stmt = $dbConn->prepare($sql);
        if ( ! empty($values)) $exec = $stmt->execute($values);
        else $exec = $stmt->execute();

        $stmt = null;
        $dbConn = null;

        return $exec;
    }

    /**
     * Create the PDO connection
     * @return PDO
     */
    private function _getDbConn()
    {
        return new PDO(DB_DSN, DB_USER, DB_PASS);
    }

    /**
     * @param $post
     * @return bool|string
     */
    public function update($post)
    {
        $this->query = "UPDATE " . $this->tableName() . " set ";
        foreach ($this->getFields() as $field) {
            if (isset($post->$field)) $this->query .= $field . "='" . $post->$field . "', ";
        }

        $this->query .= " modified='" . date("Y-m-d H:i:s") . "'";
        $this->query .= " WHERE id=" . $this->getId();
        try {
            $exec = $this->_executeQuery($this->query);

            return $exec;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param $post
     * @return bool|string
     */
    public function save($post)
    {
        $this->query = "INSERT INTO " . $this->tableName() . " (";
        $values = [];
        foreach ($this->getFields() as $cnt => $field) {
            if (isset($post->$field)) $this->query .= $field . ", ";
            $values[$cnt] = $post->$field;
        }
        $this->query .= " created)";
        $this->query .= " VALUES(";
        for ($i = 0; $i < count($this->getFields()); $i++)
            $this->query .= "?, ";
        $this->query .= "?)";
        array_push($values, date("Y-m-d H:i:s"));

        try {
            $exec = $this->_executeQuery($this->query, $values);

            return $exec;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    /**
     * @return array|string
     */
    public function fetchAll()
    {

        try {
            $dbConn = $this->_getDbConn();
            $stmt = $dbConn->prepare($this->_selectAll());
            $stmt->execute();
            $result = $stmt->fetchAll();

            $stmt = null;
            $dbConn = null;

            return $result;
        } catch (PDOException $e) {
            return $e->getMessage();
        }

    }

    /**
     * @param $modelId
     * @return mixed|string
     */
    public function fetch($modelId)
    {
        try {
            $dbConn = $this->_getDbConn();
            $stmt = $dbConn->prepare($this->_selectAll() . ' WHERE id =:id');
            $stmt->bindValue(':id', $modelId);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);


            $stmt = null;
            $dbConn = null;

            return $result;

        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    /**
     * @return string
     */
    private function _selectAll()
    {
        return "SELECT * from " . $this->tableName();
    }
}