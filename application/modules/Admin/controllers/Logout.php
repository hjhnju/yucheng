<?php
class LogoutController extends Base_Controller_Admin {
    protected $needLogin = false;
    function indexAction(){
    	$logic   = new User_Logic_Login();
    	$ret = $logic->signOut();
    	$redirectUri = '/user/login';
    	$this->redirect($redirectUri);   
    }
}