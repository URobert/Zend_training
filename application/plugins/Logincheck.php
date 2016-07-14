<?php

	class Application_Plugin_Logincheck extends Zend_Controller_Plugin_Abstract
	{
		public function preDispatch(Zend_Controller_Request_Abstract $request)
		{		 
			$controllerName = $request->getControllerName();
			$actionName = $request->getActionName();
			
			//if ($controllerName == 'LoginController' &&
			//	$actionName == 'loginpage'
			//	){
			//	//do nothing
			//}else{
			//	header('Location: /home/login');	
			//}
			
			$session = new Zend_Session_Namespace('user_session');
			$isLoggedIn = $session->is_logged_in;
			$uri = $this->getRequest()->getRequestUri();
			if (!$isLoggedIn && $uri !== '/home/login') {
			            header('Location: /home/login');
			}
		}
	 
	}
?>