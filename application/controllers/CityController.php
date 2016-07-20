<?php

class CityController extends Zend_Controller_Action
{
    
    public function init()
    {   
        $messages = $this->_helper->flashMessenger->getMessages();
        if(!empty($messages))
            $this->_helper->layout->getView()->message = $messages[0];
    }
    
    private function getCounty($id)
    {
        $table = new Application_Model_DbTable_County();
        $county =  $table->fetchRow(
                    $table->select()
                        ->where('id = ?', $id)
                   );
        return $county;    
    }
    
    private function getCountyId()
    {
        //GET COUNTY ID
        $request = $this->getRequest();
        $county_id = $request->county;
        return $county_id;
    }
    
    public function currentweatherAction()
    {
        //PAGE WAS BUILT WITH JS, ALL CODE AVAILABLE IN VIEW
    }
    
    public function listAction()
    {
		$session = new Zend_Session_Namespace('cityList');

        if (!$session->county_id){
            $session->county_id = $this->getCountyId();
        }
        
        //GET LIST OF CITIES FROM A SPECIFIC COUNTY
        $cityTable = new Application_Model_DbTable_City();
        $checkForCities = $cityTable->fetchAll(
            $cityTable->select()
                ->where('county_id = ?', $session->county_id)
        );
        
        $this->view->cityList = $checkForCities;        
        $this->view->county_id = $session->county_id;
        
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
                $this->_helper->flashMessenger('City filed can not be empty.');
                $this->_helper->redirector();
            } else {
               $checkCity = $cityTable->fetchRow(
                         $cityTable->select()
                         ->where('name = ?', $cityName)
               );

               if (!is_null($checkCity)) {
                $this->_helper->flashMessenger('City already exists in DB.');
                $this->_helper->redirector();
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
        $request = $this->getRequest();
        $id = $request->id;
        $table = new Application_Model_DbTable_City();
        $city =  $table->fetchRow(
                    $table->select()
                        ->where('id = ?', $id)
                   );
        $city->delete();
        
        $this->_helper->flashMessenger('City deleted!');
        $this->_redirect("/city/list/county/$id");
    }
    
    public function mapAction()
    {
        //PICK LIST OF CITIES TO CHECK WITH THE API
        $listofCities = ['Oradea', 'Beius', 'Alesd', 'Nucet', 'Brasov', 'Bucuresti', 'London', 'Timisoara'];
        $appId = '01ffc2b8227e5302ffa7f8555ba7738e';
        $cityAndTemp = array();

        //Getting DB cities and obtaining differences between request and db cities
        $citiesInDB = new Application_Model_DbTable_CityMap();
        $result = $citiesInDB->fetchAll();
        foreach ($result as $row) {
            $listInDB [] = $row['name'];
        }
        $diffToImport = array_diff($listofCities, $listInDB);
        
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
		$mapid = $this->getRequest()->mapid;
        $listInDB = array();
        $searchTerm = $this->getRequest()->getPost('userSearch');
    
        if ($this->getRequest()->isPost()){
            if (!null == $searchTerm) {
                $realCities = new Application_Model_DbTable_City();
                $listInDB = $realCities->fetchAll(
                      $realCities->select()
                        ->where('name LIKE ?', $searchTerm)
                );
            }
        }
        $this->view->mapid = $mapid;
        $this->view->realCityList = $listInDB;
    }
    
    public function mapcityAction()
    {
        $request = $this->getRequest();
        $mapid = $request->get('mapid');
        $targetid = $request->get('targetid');
        
        $table = new Application_Model_DbTable_CityMap();
        $city_map = $table->fetchRow(
                        $table->select()
                                ->where('id = ?', $mapid)
            );
		if (!is_object($city_map)) {
			// not found
		}
		
        $city_map->city_id = $targetid;
		$city_map->save();
		
        $this->_redirect('/city/map');
    }  
}//end of CityController class


