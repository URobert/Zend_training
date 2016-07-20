<?php

class LoginController extends CustomClass
{
	public function loginpageAction()
	{
		$request = $this->getRequest();
		$session = new Zend_Session_Namespace('user_session');
		
        if ($request->isPost()) {
            $username = $request->getParam('Username');
			//GETTING THE HASH FROM THE DB
			$user_model = new Application_Model_DbTable_User();
			$query = $user_model->select()
					->from('user')
					->where('username = ?', $username)
					->where('status = ?', 1);

			$user = $user_model->fetchRow($query);

			if (password_verify($request->getParam('Password'), $user->password)){
				$session->userid = $user->id;
				$session->is_logged_in = true;
				$this->_redirect('/home');
			}
        }
	}
	
    public function logoutpageAction()
    {
		$session = new Zend_Session_Namespace('user_session');
		$session->unlock();
		Zend_Session::namespaceUnset('user_session');
		$this->_redirect('/home/login');
    }
	
}//end of LoginController class