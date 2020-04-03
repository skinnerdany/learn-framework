<?php


class controllerCatalog extends controller
{
    public function actionIndex()
    {
        $fg = new formGenerator('/test/add', 'POST');
        $fg->attachInput('hidden', 'id', random_int(0, 100));
        $fg->attachInputByArray([
            'type' => 'email',
            'name' => 'userMail',
            'value' => 'test@test.ru'
        ]);
        $fg->attachInputByArray([
            'type' => 'submit',
            'name' => 'go',
            'value' => 'Сохранить'
        ]);
        echo $this->renderLayout([
            'error' => '',
            'modal' => '',
            'content' => $fg->generateForm()
        ]);
    }
    
    public function actionList()
    {
        // ................
        //$model = $this->getModel('users');
        /*
        $db = new pgsql();

        echo '<pre>';
        print_r($db->select('test'));
        $db->insert('test', ['name' => 'MY NAME', 'description' => 'THIS IS DESCRIPTION FOR RECORD'], true);
        print_r($db->select('test', '*'));
        $db->insert('test', ['name' => 'SECOND RECORD', 'description' => 'DESCRIPTION FOR SECOND RECORD'], true);
        print_r($db->select('test', '*', ['id' => 2]));
        $db->update('test', ['name' => 'name', 'description' => 'descr']);
        print_r($db->select('test', '*'));
        $db->update('test', ['name' => 'NAME', 'description' => 'DESCR'], ['id' => 1]);
        print_r($db->select('test', '*'));
        $db->delete('test', ['id' => 2]);
        print_r($db->select('test', '*'));
        /*
        $queryBuilder = new queryBuilder();
        //select t.id, t.name from test t left join subtest st on st.test_id=t.id where t.id<100
        $command = $queryBuilder->select('t.id, t.name')
                ->where('t.id<100')
                ->where('das')
                ->leftJoin('subtest st', 'st.test_id=t.id')
                ->from('test t');
        $command = $queryBuilder->insert(['name' => 'zzz', 'description' => 'jfhkjsdhfjksdhflsdk'])->into('test');
        $command->execute();
                //->exequte();
        echo $command->getText();
        $res = $command->execute();
        /**/
    }
    
    
    
    public function actionProduct()
    {
        echo 'YEEEE';
    }
}