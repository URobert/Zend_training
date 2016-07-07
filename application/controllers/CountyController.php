<?php

class CountyController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $county = new Application_Model_CountyMapper();
        $this->view->entries = $county->fetchAll();
    }


}

