<?php


class controllerCatalog extends controller
{
    public function actionList()
    {
        echo '<pre>';
        $model = $this->getModel('test', true);

        //$model->email = 'test@test.ru';
        //$model->password = md5('test');
        //$model->salt = md5('zzz');
        //$model->id = 1;
        $model->name = md5('testzzz');
        $model->description = md5('test');
        
        $model->save();
        
        
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