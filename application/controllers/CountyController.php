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
    
    public function getCounty($id){
        $table = new Application_Model_DbTable_County();
        $county =  $table->fetchRow(
                    $table->select()
                        ->where('id = ?', $id)
                   );
        return $county;    
    }
    
    public function editAction()
    {
        $request = $this->getRequest();
        
        $id = (int) $request->get('id');
        $countyName = $this->getCounty($id)->name;
        
        //UPDATE COUNTY NAME IN DB
        if ( $this->getRequest()->isPost() ) {
            $countyName = $request->getPost('name');
            $table = new Application_Model_DbTable_County();
            $data = array('name' => "$countyName" );
            $where = $table->getAdapter()->quoteInto('id = ?', $id);
            $table->update($data, $where);
            
            echo "<script>
            alert('County successfully updated.');
            window.location.href='/home';
            </script>";
             
        }
        $this->view->countyName = $countyName;
    }
    
    public function deleteAction()
    {
        $uri = Zend_Controller_Front::getInstance()->getRequest()->getRequestUri();
        $stringResponse = explode("/",$uri);
        $id = (int) $stringResponse[3];
                
        $cityTable = new Application_Model_DbTable_City();
        $checkForCities = $cityTable->fetchRow(
            $cityTable->select()
                ->where('county_id = ?', $id)
        );
        if ($checkForCities) {
            echo "<script>
                alert('That county can not be deteled. Only empty (without registred cities) counties can be deleted.');
                window.location.href='/home';
                </script>";  
        }else{
            $table = new Application_Model_DbTable_County();
            $where = $table->getAdapter()->quoteInto('id = ?', $id);
            $table->delete($where);
            echo "<script>
                    alert('County deleted.');
                    window.location.href='/home';
                    </script>";
        }
    }    
    
    
}// end of class