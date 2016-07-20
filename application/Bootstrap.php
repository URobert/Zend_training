<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initDoctype()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('XHTML1_STRICT');
        
    }
    
    protected function _initAutoload()
    {
        require_once '/Users/robertuivarosi/Projects/Zend/weather/application/CustomClass.php';
    }

}

