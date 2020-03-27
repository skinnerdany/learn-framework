<?php

abstract class model
{
    protected static $db = false;
    protected $tableSchema = false;
    protected $tablleName = false;
    
    public function __construct()
    {
        if (self::$db === false) {
            self::$db = new pgsql();
        }
        $this->tableName = get_class($this);
        $this->tableSchema = $this->getTableSchema();
    }
    
    public function __set($name, $value)
    {
        if ($this->tableSchema[$name]) {
            $this->tableSchema[$name]['value'] = $value;
        } else {
            $this->$name = $value;
        }
    }

    protected function getTableSchema()
    {
        $schema = self::$db->select(
                'information_schema.columns', 
                'column_name, data_type', 
                ['table_name' => $this->tableName]
            );
        
        $tableSchema = [];
        foreach ($schema as $column) {
            $tableSchema[$column['column_name']] = [
                'type' => $column['data_type']
            ];
        }
        return $tableSchema;
    }
    
    public function save()
    {
        return empty($this->tableSchema['id']['value']) ? $this->insert() : $this->update();
    }

    private function update()
    {
        $data = $this->getSaveData();
        $id = $data['id'];
        unset($data['id']);
        self::$db->update($this->tableName, $data, ['id' => $id]);
    }

    private function insert()
    {
        $data = $this->getSaveData();
        self::$db->insert($this->tableName, $data);
    }
    
    private function getSaveData()
    {
        $data = [];
        foreach ($this->tableSchema as $field => $params) {
            if (!isset($params['value'])) {
                continue;
            }
            $data[$field] = $params['value'];
        }
        return $data;
    }
}