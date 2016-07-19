<?php

class WeatherForecastController extends Zend_Controller_Action
{
	
	
	public function listAction()
	{
		$request = $this->getRequest();
		$session = new Zend_Session_Namespace('weather_forecast');
		
		$county = $request->getParam('county', $session->county);
		$city   = $request->getParam('city',   $session->city);
		$from   = $request->getParam('from',   $session->from);
		$to     = $request->getParam('to',     $session->to);
		
		if ($request->isPost()) {
			$session->county = $county;
			$session->city = $city;
			$session->from = $from;
			$session->to = $to;
		}
		
		$weather_model = new Application_Model_DbTable_Weather;
		$query = $weather_model->select()
			->setIntegrityCheck(false)
			->from('weather')
			->join('city_map', 'city_map.city_id = weather.city_id')
			->join('city', 'city_map.city_id = city.id')
			->join('county', 'county.id = city.county_id')
			->columns(['weather.id', 'city_map.name', 'weather.date', 'weather.temp', 'weather.min_temp', 'weather.max_temp', 'weather.humidity', 'weather.wind']);
			;
			
		if ($county) {
            $query->where('county.name LIKE ?', "$county%");
        }
		
        if ($city) {
            $query->where('city.name LIKE ?', "$city%");
        }
		
        if ($from) {
            $query->where('date >= ?', $from);
        }
		
        if ($to) {
            $query->where('date <= ?', $to);
		}
		
		$this->view->rows = $weather_model->fetchAll($query);
		$this->view->county = $county;
		$this->view->city = $city;
		$this->view->dateFrom = $from;
		$this->view->dateTo = $to;
	}
	
	public function importAction ()
	{
		//move date_dafault timezone to php.ini
		//GET LIST OF CITIES FOR WEATHER REPORT
		$cities = [];
		$weather = new Application_Model_DbTable_CityMap();
		$citiesDB = $weather->fetchAll();
		date_default_timezone_set('Europe/Bucharest');    
        $lastDateinDB =  date('Y-m-d',strtotime("+6 day"));		
		foreach ($citiesDB as $city) {	
			$weather_model = new Application_Model_DbTable_CityMap();
			$checkResult = $weather_model->fetchRow(
				$weather_model->select()
				->setIntegrityCheck(false)
				->from('city_map')
				->join('weather', 'city_map.city_id = weather.city_id')
				->where('city_map.name = ?', $city["name"])
				->where('weather.date = ?', "$lastDateinDB")
				);
			
            if ($checkResult){
                //skipping this particular city (already exists)
            }else{
				$weather_model = new Application_Model_DbTable_Weather();
				$where = $weather_model->getAdapter()->quoteInto('city_id = ?', $city['city_id']);
				$weather_model->delete($where);
				
                //add to city list that will be called by the API
                $cities [] = ['city_id' => $city['city_id'], 'name' => $city['name']];    
            }			
		}
					
        //CALLING API
        $appId = '01ffc2b8227e5302ffa7f8555ba7738e';
        $cityWeatherInfo = array();

        foreach ($cities as $city) {
            $response = file_get_contents('http://api.openweathermap.org/data/2.5/forecast/daily?q='.$city['name'].'&mode=json&units=metric&cnt=7'.'&APPID='.$appId.'&units=metric');
            $response = json_decode($response, true);
            
            for ($i = 0; $i < 7; ++$i) {
				    $addNewValue = $weather_model->fetchNew();
                    $addNewValue->city_id = $city['city_id'];
                    $addNewValue->date = gmdate("Y-m-d",$response['list'][$i]['dt']);
                    $addNewValue->temp = $response['list'][$i]['temp']['day'];
                    $addNewValue->min_temp = $response['list'][$i]['temp']['min'];
                    $addNewValue->max_temp =  $response['list'][$i]['temp']['max'];
                    $addNewValue->humidity = $response['list'][$i]['humidity'];
                    $addNewValue->wind = $response['list'][$i]['speed'];
                    $addNewValue->save();
					//use one variable fro $respoonse['list'][$i];
            }
        }
	}
}
