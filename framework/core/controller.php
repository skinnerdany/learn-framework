<?php

class controller
{
    protected $layoutFile = 'index';
    protected $templatesDir = false;
    protected $models = [];

    protected function getModel($modelName, $newModel = false)
    {
        include_once core::app()->models_dir . $modelName . '.php';
        if ($newModel) {
            return new $modelName;
        }

        if (!isset($this->models[$modelName])) {
            $this->models[$modelName] = new $modelName;
        }
        return $this->models[$modelName];
    }
    
    protected function renderLayout($data = [])
    {
        foreach ($data as $varName => $varValue) {
            $$varName = $varValue;
        }
        ob_start();
        include core::app()->layout_dir . $this->layoutFile . '.php';
        return ob_get_clean();
    }

    protected function renderTemplate($templateName, $data = [])
    {
        foreach ($data as $varName => $varValue) {
            $$varName = $varValue;
        }
        if ($this->templatesDir === false) {
            $this->templatesDir = core::app()->input->controller . DS;
        }
        ob_start();
        include core::app()->views_dir . $this->templatesDir . $templateName . '.php';
        return ob_get_clean();
    }
    
    public function beforeAction(){}
    public function afterAction(){}
    
    public function response($outputData = '')
    {
        if (is_array($outputData)) {
            echo json_encode($outputData);
        } else {
            echo $outputData;
        }
    }
}
