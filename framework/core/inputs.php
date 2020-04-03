<?php

class inputs
{
    public $request     = [];
    public $get         = [];
    public $post        = [];
    public $controller  = '';
    public $action      = '';
    public $form        = 0;
    
    public function __construct()
    {
        $this->request  = $_REQUEST;
        $this->post     = $_POST;
        $this->get      = $_GET;
        $this->filterRequest();
    }
    
    public function filterRequest()
    {
        $this->controller = empty($_GET[core::app()->controller_request_param]) ? 
                core::app()->default_controller :
                $_GET[core::app()->controller_request_param];
        /*
        switch (strtolower($_SERVER['REQUEST_METHOD'])) {
            case 'get':
                $this->action = isset($_GET['id']) ? 'view' : 'get';
                break;
            case 'post':
                $this->action = 'create';
                break;
            case 'delete':
                $this->action = 'delete';
                break;
            case 'put':
                $this->action = 'update';
                break;
        }
        /**/
        $this->action = empty($_GET[core::app()->action_request_param]) ? 
                core::app()->default_action :
                $_GET[core::app()->action_request_param];
        /**/

        if (isset($this->request['go'])) {
            $this->form = 1;
        }

        unset(
            $this->request['go'], 
            $this->get['go'], 
            $this->post['go'],
            $this->request[core::app()->controller_request_param], 
            $this->get[core::app()->controller_request_param], 
            $this->post[core::app()->controller_request_param],
            $this->request[core::app()->action_request_param], 
            $this->get[core::app()->action_request_param], 
            $this->post[core::app()->action_request_param]
        );
    }
}