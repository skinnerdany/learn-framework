<?php

class pgsql
{
    protected $connection = false;

    public function __construct()
    {
        if ($this->connection === false) {
            $cs = 'host=' . core::app()->db['host'] .
               ' port=' . core::app()->db['port'].
               ' dbname=' . core::app()->db['name'].
               ' user=' . core::app()->db['login'].
               ' password=' . core::app()->db['password'];
            $this->connection = pg_connect($cs);
        }
    }
    
    public function query($sql)
    {
        $result = pg_fetch_all(pg_query($this->connection, $sql));
        if (empty($result)) {
            $result = [];
        }
        return $result;
    }
    
    public function escape($value)
    {
        return pg_escape_string($value);
    }
    
    public function select(string $table, $fields = '*', $where = [])
    {
        $sql = 'SELECT ';
        $sql .= is_array($fields) ? implode(',', $fields) : $fields;
        $sql .= ' FROM ' . $table;
        $sql .= $this->getWhere($where);
        return $this->query($sql);
        //codeception
    }

    public function insert(string $table, array $data, $returnId = false)
    {
        $sql = 'INSERT INTO ' . $table;
        $insertSqlFields = [];
        $insertSqlValues = [];
        foreach ($data as $field => $value) {
            $insertSqlFields[] = $field;
            $insertSqlValues[] = "'" . $this->escape($value) . "'";
        }
        $sql .= '(' . implode(',', $insertSqlFields) . ') VALUES (' .implode(',', $insertSqlValues) . ')';
        if ($returnId) {
            $sql .= ' RETURNING id';
        }
        $id = 0;
        $res = $this->query($sql);
        if (!empty($res)) {
            $id = $res[0]['id'];
        }
        
        return $id;
    }

    public function delete(string $table, $where = [])
    {
        return $this->query('DELETE FROM ' . $table . $this->getWhere($where));
    }

    public function update(string $table, $data, $where = [])
    {
        $sql = 'UPDATE ' . $table . ' SET ';
        
        $updateData = [];
        foreach ($data as $field => $value) {
            $updateData[] = $field . '=\'' . $this->escape($value) . "'";
        }
        $sql .= implode(',', $updateData);
        $sql .= $this->getWhere($where);
        return $this->query($sql);
    }

    protected function getWhere($where = [])
    {
        $sql = '';
        if (is_array($where) && !empty($where)) {
            $whereSql = [];
            foreach ($where as $field => $value) {
                $whereSql[] = $field . '=\'' . $this->escape($value) . "'";
            }
            if (!empty($whereSql)) {
                $sql .= ' WHERE ' . implode(' AND ', $whereSql);
            }
        }
        return $sql;
    }
}
