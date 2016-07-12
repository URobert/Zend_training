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
    
    public function mapAction()
    {
        //PICK LIST OF CITIES TO CHECK WITH THE API
        $listofCities [] = ['Oradea', 'Beius', 'Alesd', 'Nucet', 'Brasov', 'Bucuresti', 'London', 'Timisoara'];
        $appId = '01ffc2b8227e5302ffa7f8555ba7738e';
        $cityAndTemp = array();

        //Getting DB cities and obtaining differences between request and db cities
        $citiesInDB = new Application_Model_DbTable_CityMap();
        $result = $citiesInDB->fetchAll();
        foreach ($result as $row) {
            $listInDB [] = $row['name'];
        }
        $diffToImport = array_diff($listofCities[0], $listInDB);
        
        foreach ($diffToImport as $city) {
        $cityTable = new Application_Model_DbTable_City();        
        $addNewCity = $cityTable->fetchNew();
        $addNewCity->name = $city;
        $addNewCity->source_id = 1;
        $addNewCity->save();
        }
        
        $completeList = $citiesInDB->fetchAll(
                $citiesInDB->select()
                        ->order('name ASC')
                );
        
        foreach ($completeList as $city) {
            $allCities [] = ['name' => $city['name'], 'source_id' => $city['source_id'], 'id' => $city['id'], 'city_id' => $city['city_id']];
        }
        
        //API CALL
        foreach ($allCities as $city) {
            $responseJson = file_get_contents('http://api.openweathermap.org/data/2.5/weather?q='.$city['name'].'&APPID='.$appId.'&units=metric');
            $response = json_decode($responseJson);
            if ($response->cod == 200) {
                $cityAndTemp [] = ['city' => $response->name, 'temp' => $response->main->temp, 'source_id' => $city['source_id'], 'id' => $city['id'], 'city_id' => $city['city_id']];
            }
        }
        $this->view->cityAndTemp = $cityAndTemp;
    }
    
    
    public function searchAction()
    {
        $listInDB = array();
        $searchTerm = $this->getRequest()->getPost('userSearch');
        if (!null == $searchTerm) {
            $realCities = new Application_Model_DbTable_City();
            $result = $realCities->fetchAll(
                  $realCities->select()
                    ->where('name LIKE ?', $searchTerm)
            );
            
            foreach ($result as $city){
                $listInDB [] = $city[0]['name'];
            }
            
            var_dump($result);
            exit;
        }
        //    $realCitites = \ORM::for_table('city')
        //        ->select_many('id', 'name')
        //        ->where_like('name', $request->get('userSearch'))
        //        ->find_many();
        //    foreach ($realCitites as $city) {
        //        $listInDB [] = $city;
        //    }
        //}
        //
        //return $this->render(['mapid' => $request->get('mapid'), 'realCityList' => $listInDB]);
    }
    
    
    
    public function currentweatherAction(){
        
    }
    

}

