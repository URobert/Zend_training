<?php

class CountyController extends Zend_Controller_Action
{

    public function init()
    {
        //Connect to db    
        $db = new Zend_Db_Adapter_Pdo_Mysql(array(
        'host'     => '127.0.0.1',
        'username' => 'root',
        'password' => 'IamGroot',
        'dbname'   => 'myDB'
        ));
        
        $sql = 'Select * from county';
        $result = $db->fetchAll($sql);
        $this->view->result = $result;
        
    }

    public function indexAction()
    {
        
    }
    
    public function addcountyAction()
    {        
        $countyName = $this->getRequest()->getPost('county');
        $county = new Application_Model_DbTable_County;
        
        if ($this->getRequest()->isPost()) {
            
        // TEST FIELDS FOR NON-EMPTY AND LENGTH
            if (!$countyName) {
                echo '<script language="javascript">alert("County filed can not be empty.")</script>';
            } else {
                // REFACTOR INSERT QUERIES
               $checkCounty = $county->fetchRow(
                   $county->select()
                       ->where('name = ?', $countyName)
               );
               if ($checkCounty) {
                    echo "<script>alert('County already exists in DB.')</script>";
               } else {
                    $addNewCounty = $county->fetchNew();
                    $addNewCounty->name = $countyName;
                    $addNewCounty->save();
                    echo "<script>window.location.href='/home'</script>";
                    $countyId = $addNewCounty->id;
               }
            
           }
        }//end of POST method check
    }
}