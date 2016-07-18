<?php

class DbTestingController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
    //Connect to db    
    $db = new Zend_Db_Adapter_Pdo_Mysql(array(
    'host'     => '127.0.0.1',
    'username' => 'root',
    'password' => 'IamGroot',
    'dbname'   => 'myDB'
    ));
        
    $tables =  $db->listTables();        
    var_dump($tables);
    echo "<br><br>";
    
    $sql = 'Select * from county';
    $result = $db->fetchAll($sql);
    var_dump($result);
    
    
    
    }
    

}
