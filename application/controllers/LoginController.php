<?php

class LoginController extends Zend_Controller_Action
{
	public function loginpageAction()
	{
		$request = $this->getRequest();
		$session = new Zend_Session_Namespace('user_session');
		
		
		
        if ($request->isPost()) {
            $user = $request->getParam('Username');
			//Getting the hash from the DB
			$user_model = new Application_Model_DbTable_User();
			$query = $user_model->select()
					->from('user')
					->columns('password');
			$query->where('username = ?', $user);
			$password = $user_model->fetchAll($query)[0]['password'];
			

			if (password_verify($request->getParam('Password'), $password)){
				$query =  $user_model->select()
						->from('user')
						->columns(['username', 'password', 'status']);
				$query->where('username = ?', $user);
				$query->where('status', 1);
				$result = $user_model->fetchRow($query);
				
				if ($result) {
					$session->userid = $result->id;
					$currentUserId =  $result->id;
					
					//SAVING SESSION INFO INTO DB AS WELL
					$session_model = new Application_Model_DbTable_UserSession;
					$checkQuery =  $session_model->select()
						->from ('user_sessions');
					$checkQuery->where('user_id = ?', $currentUserId);
					$logged_session_info = $session_model->fetchRow($checkQuery);
					
					if ($logged_session_info){
						//do nothing
					}else{
						//create user info in db
                    $addNewUser = $session_model->fetchNew();
                    $addNewUser->user_id = $currentUserId;
                    $addNewUser->save();						
					}
					
					//set session as logged in
					$session->is_logged_in = true;
					header('Location: /home');
					exit;
				}
			}			
        }
	}
	
    public function logoutpageAction()
    {
		$session = new Zend_Session_Namespace('user_session');
		$session->unlock();
		Zend_Session::namespaceUnset('user_session');
        header('Location: /home/login');
        exit;
    }
}//end of LoginController class