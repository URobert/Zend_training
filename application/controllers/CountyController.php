<?php

class CountyController extends Zend_Controller_Action
{
    public function init()
    {
        $messages = $this->_helper->flashMessenger->getMessages();
        if(!empty($messages))
        $this->_helper->layout->getView()->message = $messages[0];
    }
    
    public function indexAction()
    {   
        $table = new Application_Model_DbTable_County();
        $result =  $table->fetchAll( $table->select()->from('county') );
        $this->view->result = $result;
        header( "refresh:5;url=http://weather.local/home" );
    } 
    
    public function addcountyAction()
    {        
        $countyName = $this->getRequest()->getPost('county');
        $county = new Application_Model_DbTable_County;
        
        if ($this->getRequest()->isPost()) {
        // TEST FIELDS FOR NON-EMPTY AND LENGTH
            if (!$countyName) {
                    $this->_helper->flashMessenger('County filed can not be empty.');
                    $this->_helper->redirector('AddCounty');
            } else {
               $checkCounty = $county->fetchRow(
                   $county->select()
                       ->where('name = ?', $countyName)
               );
               if ($checkCounty) {
                    $this->_helper->flashMessenger('County already exists in DB.');
                    $this->_helper->redirector('AddCounty');
               } else {
                    $addNewCounty = $county->fetchNew();
                    $addNewCounty->name = $countyName;
                    $addNewCounty->save();
                    $countyId = $addNewCounty->id;                   
                    header('Location: /home');
                    exit;
               }
           }
        }//end of POST method check
    }
    
    public function getCounty($id){
        $table = new Application_Model_DbTable_County();
        $county = $table->fetchRow(
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
            $this->_helper->flashMessenger('County updated.');
            $this->_helper->redirector();
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
            $this->_helper->flashMessenger('That county can not be deteled. Only empty (without registred cities) counties can be deleted.');
            $this->_helper->redirector();
        }else{
            $table = new Application_Model_DbTable_County();
            $where = $table->getAdapter()->quoteInto('id = ?', $id);
            $table->delete($where);
            $this->_helper->flashMessenger(' County deleted !');
            $this->_helper->redirector();
        }
    }   
}// end of class