<?php

class LoginController extends Zend_Controller_Action
{
	public function loginpageAction()
	{
        //if (isset($_SESSION['userId'])) {
        //    header('Location: /home');
        //    exit;
        //}	
		$request = $this->getRequest();
		$session = new Zend_Session_Namespace('login_page');
		
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
					//SAVING SESSION INFO INTO DB AS WELL
					#1. Check for session info in DB
					//$checkSession = \ORM::for_table('user_sessions')->find_one();
					//if (!$checkSession){
					//    $userInfo = \ORM::for_table('user_sessions')->create();
					//    $userInfo
					//        ->set('user_id',$dbUser->id)
					//        ->save();
					//}
					header('Location: /home');
					exit;
				}
			}			
        }
	}
	
}//end of LoginController class