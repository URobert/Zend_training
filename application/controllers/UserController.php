<?php

class UserController extends Zend_Controller_Action
{
	
    public function listAction()
    {
		
		$request = $this->getRequest();
		$session = new Zend_Session_Namespace('user_session');
		
        //BRING IN SESSION INFO FROM DB
		$savedSession = new Application_Model_DbTable_UserSession();
		$query =  $savedSession->select()
					->setIntegrityCheck(false)
					->from('user_sessions')
					->columns(['session_info']);
		$query->where('user_id = ?', 1);  # 1 HardCoded should b changed to session->userId /$_SESSION['userId']
		$result  = $savedSession->fetchRow($query);
		$decoded = json_decode($result->session_info);
		        
        if($decoded->user != "" || $decoded->email != "" || $decoded->status != ""){
            $user = $request->getParam('username', $decoded->user);
            $email = $request->getParam('email',$decoded->email);
            $status = $request->getParam('status',$decoded->status);
        } else {
            $user = $request->getParam('username', $session->user);
            $email = $request->getParam('email', $session->email);
            $status = $request->getParam('status', $session->status);
        }
        
        //SAVING SESSION INFO INTO DB AS WELL
				//should use encoding to keep the previous data form for session information in the db table
		//$userSession = new Application_Model_DbTable_UserSession();
		//$addNewSessionInfo = $cityTable->fetchNew();
		//$addNewSessionInfo->user = $user;
		//$addNewSessionInfo->email = $email;
		//$addNewSessionInfo->status = $status;
		//$addNewSessionInfo->save();
		//
        //----------------------------------
        
		$usersAndPages = $this->SeachUsers($user, $email, $status);
		$this->view->users = $usersAndPages['users'];
		$this->view->username = $user;
		$this->view->email = $email;
		$this->view->status = $status;
		$this->view->pagination = $usersAndPages['pagination'];
    }

    public function SeachUsers($username, $email, $status)
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
        
        $number_per_page = 3;
        $current_page = isset($_GET['pn']) ? $_GET['pn'] : 1;
	
        $total = $user_model->fetchAll($sqlReq)->count();
        $pages = ceil($total/$number_per_page);
        $is_first_page = $current_page == 1;
        $is_last_page  = $current_page == $pages;

        $sqlReq->limit($number_per_page, $number_per_page * ($current_page - 1));
            
		$result = $user_model->fetchAll($sqlReq);
		
        foreach ($result as $entry){
            $users [] = $entry;
        }
		
        return [
            'users' => $users,
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