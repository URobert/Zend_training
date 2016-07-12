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
	
}
