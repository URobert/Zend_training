<?php

class DbTestingController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    //Connect to db    
    $db = new Zend_Db_Adapter_Pdo_Mysql(array(
    'host'     => '127.0.0.1',
    'username' => 'root',
    'password' => 'IamGroot',
    'dbname'   => 'myDB'
    ));
        
    $version = $db->getServerVersion();        
    
    var_dump($version);        
    }
    
}
