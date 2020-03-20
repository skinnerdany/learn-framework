<?php


class controllerCatalog extends controller
{
    public function actionList()
    {
        echo 'zzzz';
        return ['test' => 'zzz'];
        //echo 
        //$this->renderTemplate('tpl1');
        //$this->renderTemplate('tpl2');
        //$this->renderTemplate('tpl3');
        //$menu = $this->renderTemplate('menu', ['b' => '#']);
        //echo $this->renderLayout();
        //return ['test'];
        //echo htmlspecialchars($lo);
        //echo '>>>I AM ACTION TEST ' . core::app()->input->controller;
    }
    
    public function actionProduct()
    {
        echo 'YEEEE';
    }
}