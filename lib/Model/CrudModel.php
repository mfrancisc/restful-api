<?php
namespace lib\Model;
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

    /****************************************
     * Implement the following methods
     * in your concrete Model
     * -------------------------------------/

    /**
     * return the db table name
     * @return mixed
     */
    abstract protected function tableName(): string;

    /**
     * return row id
     * @return mixed
     */
    abstract protected function getId(): int;

    /**
     * return editable fields
     * @return mixed
     */
    abstract protected function getFields(): array;
    /** ---------------------------------- */

    /**
     * @param $post
     * @return bool|string
     */
    public function update(\stdClass $post)
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

        } catch (\PDOException $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param $model
     * @return bool|string
     */
    public function save(\stdClass $model)
    {
        $callback = function($query, $values): bool {
                return $this->_executeQuery($query, $values);
        };

        return $this->_insert($model, $callback);
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
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $stmt = null;
            $dbConn = null;

            return $result;

        } catch (\PDOException $e) {
            return $e->getMessage();
        }

    }

    /**
     * @param $modelId
     * @return mixed|string
     */
    public function fetch()
    {
        try {
            $dbConn = $this->_getDbConn();
            $stmt = $dbConn->prepare($this->_selectAll() . ' WHERE id =:id');
            $stmt->execute(array(':id'=>$this->getId()));
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

            $stmt = null;
            $dbConn = null;

            return $result;

        } catch (\PDOException $e) {
            return $e->getMessage();
        }
    }

    /**
     * @return bool|string
     */
    public function delete()
    {
            $this->query = "DELETE FROM " . $this->tableName();
            $this->query .= " WHERE id= " . $this->getId();

            return $this->_executeQuery($this->query);
    }

    /**
     * Create the insert query string
     * @param  \stdClass $model 
     * @return string          
     */
    private function _insert(\stdClass $model, callable $callback): string 
    {
        $query = "INSERT INTO " . $this->tableName() . " (";
        $values = [];
        foreach ($this->getFields() as $cnt => $field) {
            if (isset($model->$field)) $query .= $field . ", ";
            $values[$cnt] = $model->$field;
        }
        $query .= " created)";
        $query .= " VALUES(";
        for ($i = 0; $i < count($this->getFields()); $i++)
            $query .= "?, ";
        $query .= "?)";
        array_push($values, date("Y-m-d H:i:s"));
        
        return $callback($query, $values);  
    }

    /**
     * @param $sql string
     * @param array $values
     * @return bool
     */
    private function _executeQuery(string $sql,array $values = array()): bool
    {

        try {
            $dbConn = $this->_getDbConn();
            $dbConn->beginTransaction();

            $stmt = $dbConn->prepare($sql);
            if ( ! empty($values)) $exec = $stmt->execute($values);
            else $exec = $stmt->execute();

            $dbConn->commit();

            $stmt = null;
            $dbConn = null;

            return $exec;

        } catch (\PDOException $e) {

            if($dbConn) $dbConn->rollBack();

            return $e->getMessage();
        }

    }

    /**
     * Create the PDO connection
     * @return \PDO
     */
    private function _getDbConn(): \PDO
    {
        return new \PDO(DB_DSN, DB_USER, DB_PASS);
    }

    /**
     * @return string
     */
    private function _selectAll(): string
    {
        return "SELECT * FROM " . $this->tableName();
    }
}