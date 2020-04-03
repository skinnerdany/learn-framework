<?php

class controllerPosts extends controller
{
    protected $model = false;
    
    public function __construct()
    {
        parent::__construct();
        $this->model = $this->getModel('posts');
    }

    public function actionCreate()
    {
        $this->model->savePost(core::app()->input->post);
        echo json_encode(['success' => 1]);
    }
    
    public function actionGet()
    {
        $posts = $this->model->getPosts();
        echo json_encode($posts);
    }
    
    public function actionView()
    {
        $post = $this->model->getPosts(core::app()->input->get['id']);
        echo json_encode($post);
    }

    public function actionUpdate()
    {
        $data = '';
        $putdata = fopen("php://input", "r");
        while ($chunk = fread($putdata, 1024)) {
            $data .= $chunk;
        }
        fclose($putdata);

        $input = [];
        parse_str($data, $input);
        $this->model->savePost($input);
        echo json_encode(['success' => 1]);
    }

    public function actionDelete()
    {
        $this->model->deletePost(core::app()->input->get['id']);
        echo json_encode(['success' => 1]);
    }
}