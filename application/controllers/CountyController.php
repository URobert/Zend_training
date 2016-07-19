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
                    $this->_redirect('/home');
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
        //CHECK FOR NON EXISTENT IDs
        $request = $this->getRequest();
        $id = (int) $request->get('id');
        $county_model = new Application_Model_DbTable_County();
        $checkQuery = $county_model->fetchRow(
                        $county_model->select()
                            ->where('id = ?', $id )
            );
        if (false == $checkQuery) {
            $this->_helper->flashMessenger('That county does not exist.');
            $this->_helper->redirector();
        }
        
        $countyName = $this->getCounty($id)->name;
        //UPDATE COUNTY NAME IN DB
        if ( $this->getRequest()->isPost() ) {
            $countyName = $request->getPost('name');
            $table = new Application_Model_DbTable_County();
            $data = array('name' => $countyName );
            $where = $table->getAdapter()->quoteInto('id = ?', $id);
            $table->update($data, $where);
            $this->_helper->flashMessenger('County updated.');
            $this->_helper->redirector();
        }
        $this->view->countyName = $countyName;
    }
    
    public function deleteAction()
    {
        //get id from request, remove getAddapter
        $request = $this->getRequest();
        $id = $request->id;
                
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
            $county = $table->fetchRow(
                        $table->select()
                                ->where ('id = ?', $id)
            );
            $county->delete();
            $this->_helper->flashMessenger(' County deleted !');
            $this->_helper->redirector();
        }
    }   
}// end of class