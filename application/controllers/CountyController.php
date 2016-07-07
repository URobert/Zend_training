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
    
    public function addcountyAction()
    {
        $county = new Application_Model_DbTable_County();
        $posted = $this->getRequest()->getPost('county');
        
        if ($this->getRequest()->isPost()) {
            
        // TEST FIELDS FOR NON-EMPTY AND LENGTH
            if (!$county) {
                echo '<script language="javascript">alert("County filed can not be empty.")</script>';
            } else {
                // REFACTOR INSERT QUERIES
               $checkCounty = $county->fetchRow(
                   $county->select()
                       ->where('name = ?', $county)
               );
               if ($checkCounty) {
                    echo "<script>alert('County already exists in DB.')</script>";
               } else {
                    $addNewCounty = $county->fetchNew();
                    $addNewCounty->name = $posted;
                    $addNewCounty->save();
                    echo "<script>window.location.href='/home'</script>";
                    $countyId = $addNewCounty->id;
               }
            
           }
        }//end of POST method check
    }
}