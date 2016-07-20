<?php

class HomeController extends Zend_Controller_Action
{

    public function init()
    {
        Zend_Session::namespaceUnset('cityList');

    }

    public function indexAction()
    {
        
        $table = new Application_Model_DbTable_County();
        $result =  $table->fetchAll( $table->select()->from('county') );
        $this->view->result = $result;

    }

}

