<?php

class httpException extends Exception
{
    public $statuses = [
        404 => '404 Not Found',
        401 => '401 Unauthorized'
    ];
}