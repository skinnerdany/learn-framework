<?php

class controllerMain extends controller
{
    protected $layoutFile = 'main';

    public function actionGet()
    {
        echo $this->renderLayout();
    }
}