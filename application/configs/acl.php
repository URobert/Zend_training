<?php
//DEFINING ACCESS CONTROL LIST
$acl = new Zend_Acl ();

$roleGuest = new Zend_Acl_Role('guest');
$acl->addRole($roleGuest);
$acl->addRole( new Zend_Acl_Role('member'), $roleGuest);
$acl->addRole( new Zend_Acl_Role('admin'));

$alfa_parrent = array ('admin');
$acl->addRole (new Zend_Acl_Role('Roberto'), $alfa_parrent);


//Guest may only view content
$acl->allow('guest', null, 'view');

//Members inherit view privilege from guest but have additonal rights
$acl->allow('member', null, array('edit'));

//Admins inherits nothing, but is allowed all privileges
$acl->allow('admin');

//Testing permisions 

//        echo $acl->isAllowed('guest', null, 'view') ?
//     "allowed" : "denied";
//		echo "<br><br>";
//	 
//	    echo $acl->isAllowed('guest', null, 'edit') ?
//     "allowed" : "denied";
		


?>