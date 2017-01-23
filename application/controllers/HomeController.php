<?php

class HomeController extends CustomClass
{

    public function init()
    {
        Zend_Session::namespaceUnset('cityList');

    }

    public function indexAction()
    {
        #alternative 
        //$db = new Zend_Db_Adapter_Pdo_Mysql(array(
        //'host'     => '127.0.0.1',
        //'username' => 'root',
        //'password' => 'IamGroot',
        //'dbname'   => 'myDB'
        //));
        
        //$stmt = $db->query('SELECT county_id, county.name as County, city.name as City FROM county, city WHERE county.id = city.county_id');
        //$stmt = $db->query('SELECT county_id, county.name as County FROM county, city WHERE county.id = city.county_id GROUP BY county_id');
        //$result = $stmt->fetchAll();
        
        $table = new Application_Model_DbTable_County();
        $result =  $table->fetchAll( $table->select()->from('county') );
        $this->view->result = $result;
    }

}

