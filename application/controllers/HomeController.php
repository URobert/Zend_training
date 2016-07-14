<?php

class HomeController extends Zend_Controller_Action
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
    
//ENFORCE LOGIN PAGE
//$session = new Zend_Session_Namespace('user_session');
//$isLoggedIn = $session->is_logged_in;
//$uri = $this->getRequest()->getRequestUri();
//if (!$isLoggedIn && $uri !== '/home/login') {
//            header('Location: /home/login');
//}
//--------------------------------------------------- THIS WILL BE MOVED
    
    $sql = 'Select * from county';
    $result = $db->fetchAll($sql);
    $this->view->result = $result;
    
        
    $table = new Application_Model_DbTable_County();
    $county =  $table->fetchRow(
                $table->select()
                    ->where('name = ?', 'Brasov')
               );
    #var_dump($county->name);
    }
    

    
}

