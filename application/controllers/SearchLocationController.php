<?php

class SearchLocationController extends Zend_Controller_Action
{
	
	public function searchAction()
    {
	
		$request = $this->getRequest();
		$locations = new Zend_Session_Namespace('location_search');	
        $searchField = $request->getParam('userSearch', $locations->searchField);
        $category = $request->getParam('SearchBy', $locations->category);

        if ($request->isPost()) {
            $locations->searchField = $request->getParam('userSearch');
            if ($request->getParam('SearchBy') == 'county') {
                $locations->category = 'county';
            } else {
                $locations->category = 'city';
            }
        }

        $this->searchHelp($searchField, $category);
    }
	
    public function searchHelp($searchTerm, $category)
    {
		//
        $countiesAndCities = [];
		$county_model = new Application_Model_DbTable_County();
		$baseQuery =  $county_model->select()
			->setIntegrityCheck(false)
			->from('county')
			->join('city', 'county.id = city.county_id')
			->order('county.name ASC')
			->columns(['county.name as county', 'city.name as city']);

        if (!empty($searchTerm) && !is_null($category)) {
            $baseQuery->where("$category.name = ?", $searchTerm);
        }
		
		$countiesAndCities = $county_model->fetchAll( $baseQuery ) ;
        if (!$countiesAndCities) {
            #echo 'No result was found.';
        }

        $this->view->countiesAndCities = $countiesAndCities;
		$this->view->searchTerm = $searchTerm;
		$this->view->category = $category;
    }	
	
}//end of WeatherForecastController class