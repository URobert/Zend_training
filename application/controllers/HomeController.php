<?php

class HomeController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */

    }

    public function indexAction()
    {
        
    $table = new Application_Model_DbTable_County();
    $result =  $table->fetchAll( $table->select()->from('county') );
    $this->view->result = $result;

    }
    

    
}

