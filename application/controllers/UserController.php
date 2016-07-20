<?php

class UserController extends CustomClass
{
	
    public function listAction()
    {	
		$request = $this->getRequest();
		$session = new Zend_Session_Namespace('user_session');
		$currentUserId  = $session->userid;
		
        //BRING IN SESSION INFO FROM DB
		$savedSession = new Application_Model_DbTable_UserSession();
		$query =  $savedSession->select()
					->setIntegrityCheck(false)
					->from('user_sessions')
					->columns(['session_info']);
		$query->where('user_id = ?', $currentUserId); 
		$result  = $savedSession->fetchRow($query);
		$decoded = json_decode($result->session_info);
		        
        if($decoded->user != "" || $decoded->email != "" || $decoded->status != ""){
            $user = $request->getParam('username', $decoded->user);
            $email = $request->getParam('email',$decoded->email);
            $status = $request->getParam('status',$decoded->status);
			
			$session->user= $decoded->user;
			$session->email = $decoded->email;
			$session->status = $decoded->status;
        } else {
            $user = $request->getParam('username', $session->user);
            $email = $request->getParam('email', $session->email);
            $status = $request->getParam('status', $session->status);
			
			$session->user= $user;
			$session->email = $email;
			$session->status =$status;
        }
        
        //SAVING SESSION INFO INTO DB AS WELL
		$session_model = new Application_Model_DbTable_UserSession;
		$checkQuery =  $session_model->select()
									 ->from ('user_sessions')
									 ->where('user_id = ?', $currentUserId);
		$logged_session_info = $session_model->fetchRow($checkQuery);
		
		if ($logged_session_info){
			//update user's sesssion info
			$fields = ['user' => $user, 'email'=> $email, 'status' => $status];
			$userSession = $session_model->fetchRow(
						   $session_model->select()
										 ->where( 'user_id = ?', "$currentUserId" )
			);
			$userSession->session_info = json_encode($fields);
			$userSession->save();	
		}
        //------------------------------------------------------------------------------------
        
		$usersAndPages = $this->SeachUsers($user, $email, $status);
		$this->view->users = $usersAndPages['users'];
		$this->view->username = $user;
		$this->view->email = $email;
		$this->view->status = $status;
		$this->view->pagination = $usersAndPages['pagination'];
    }

    private function SeachUsers($username, $email, $status)
    {
        $users = [];
        $user_model = new Application_Model_DbTable_User();
		$sqlReq = $user_model->select()
			->setIntegrityCheck(false)
			->from('user')
			->columns(['username']);
				
        //SEACH ONLY BY STATUS
        if ($status != '') {
            $sqlReq->where('status = ?', $status);
        }
        
        //SEACH BY USER
        if (!empty($username)) {
            $sqlReq->where('username LIKE ?', "$username%");
        }
        //SEACH BY EMAIL
        if (!empty($email)) {
            $sqlReq->where('email LIKE ?', "%$email%");
        }
        
        $number_per_page = 5;
        $request = $this->getRequest();
		$current_page = null !== $request->getParam('pn') ? $request->getParam('pn') : 1;
        $total = $user_model->fetchAll($sqlReq)->count();
        $pages = ceil($total/$number_per_page);
        $is_first_page = $current_page == 1;
        $is_last_page  = $current_page == $pages;

        $sqlReq->limit($number_per_page, $number_per_page * ($current_page - 1));
		$result = $user_model->fetchAll($sqlReq);
		
        return [
            'users' => $result,
            'pagination' => [
                'total' => $total,
                'pages' => $pages,
                'current_page' => $current_page,
                'is_first_page' => $is_first_page,
                'is_last_page'  => $is_last_page
            ]
        ];
    }
}//end of User class 
