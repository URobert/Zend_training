<?php

class SignupController extends Zend_Controller_Action
{
	
    public function indexAction()
    {   
        $messages = $this->_helper->flashMessenger->getMessages();
        if(!empty($messages))
        $this->_helper->layout->getView()->message = $messages[0];
        header("refresh:2; url=/home/signup");
    }
	
    public function signupformAction()
    {
		
        if ($this->getRequest()->isPost()) {
			$session = new Zend_Session_Namespace('user_session');
			$request = $this->getRequest();
            if ($request->get('password') === $request->get('passwordVerify') && strlen($request->get('password')) >= 4) {
                $username = $request->get('username');
                $password = password_hash($request->get('password'), PASSWORD_BCRYPT);
                $email = $request->get('emailAddress');
                if ($request->get('status') == 'active') {
                    $status = 1;
                } else {
                    $status = 0;
                }
				
				$table = new Application_Model_DbTable_User();
				$query = $table->fetchNew();
				$query->username = $username;
				$query->password = $password;
				$query->email = $email;
				$query->status = $status;
				$query->save();
				$session->userid = $query->id;
				
				$session_model = new Application_Model_DbTable_UserSession;
				$addNewUser = $session_model->fetchNew();
				$addNewUser->user_id = $session->userid;
				$addNewUser->save();

				$fields = ['user' => null, 'email'=> null, 'status' => null];
				$data = array('session_info'=> json_encode($fields) );
				$where = $session_model->getAdapter()->quoteInto('user_id = ?', $session->userid);
				$session_model->update($data, $where);
				
				if ($status == 1) {
					$session->is_logged_in = true;
                    header('Location: /home');
                    exit;
                } else {
                    $session->userid = '';
                    header('Location: /home/logout');
                    exit;
                }
            } else {
                if (strlen($request->get('password')) <= 3) {
                    $this->_helper->flashMessenger('Password is too short. Please retry.');
					$this->_helper->redirector();
                } else {
                    $this->_helper->flashMessenger('Passwords do not match.');
					$this->_helper->redirector();
                }
            }
        }
    }
}
