<?php

class test extends model
{
    /*
    public $id = 0;
    public $name = '';
    public $description = '';
    /**/

    public function getRecords()
    {
        return self::$db->select('test', '*');
    }
}