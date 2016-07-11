<?php

class CityController extends Zend_Controller_Action
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

    }
    
    public function indexAction()
    {   

    }
    
    public function getCounty($id)
    {
    $table = new Application_Model_DbTable_County();
    $county =  $table->fetchRow(
                $table->select()
                    ->where('id = ?', $id)
               );
    return $county;    
    }
    
    public function getCountyId()
    {
        //GET COUNTY ID
        $uri = Zend_Controller_Front::getInstance()->getRequest()->getRequestUri();
        $stringResponse = explode("/",$uri);
        $county_id = (int) $stringResponse[3];
        return $county_id;
    }
    
    public function listAction()
    {

        $county_id = $this->getCountyId();
        //GET LIST OF CITIES
        $cityTable = new Application_Model_DbTable_City();
        $checkForCities = $cityTable->fetchAll(
            $cityTable->select()
                ->where('county_id = ?', $county_id)
        );
        
        foreach ($checkForCities as $city) {
            $cityList [] = ['name' => $city->name, 'id' => $city->id];
        }
        $this->view->cityList = $cityList;
        $this->view->county_id = $county_id;
    }

    public function addcityAction()
    {
        $cityTable = new Application_Model_DbTable_City();
        $county_id = $this->getCountyId();
        $this->view->county_id = $county_id;
        $this->view->countyName = $this->getCounty($county_id)->name;
        
        if ($this->getRequest()->isPost()) {
            $cityName = $this->getRequest()->getPost('city');    
        // TEST FIELDS FOR NON-EMPTY AND LENGTH
            if (!$cityName) {
                echo '<script language="javascript">alert("City filed can not be empty.")</script>';
            } else {
                // REFACTOR INSERT QUERIES
               $checkCity = $cityTable->fetchRow(
                   $cityTable->select()
                       ->where('name = ?', $cityName)
               );

               if (!is_null($checkCity)) {
                    echo "<script>alert('City already exists in DB.')</script>";
               } else {
                    $addNewCity = $cityTable->fetchNew();
                    $addNewCity->name = $cityName;
                    $addNewCity->county_id = $county_id;
                    $addNewCity->save();
                    #echo "<script>window.location.href='/home'</script>";
               }
            
           }
        }//end of POST method check
    }
    
    public function deleteAction()
    {
        $id= $this->getCountyId();        
        $cityTable = new Application_Model_DbTable_City();
        $where = $cityTable->getAdapter()->quoteInto('id = ?', $id);
        $cityTable->delete($where);

        echo "<script>
        alert('City deleted');
        window.location.href='/home';
        </script>";

    }
    
    public function currentweatherAction(){
        
    }
    

}

