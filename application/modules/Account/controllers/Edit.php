<?php 
/**
 * 修改页面类
 * @author lilu
 */
class EditController extends Base_Controller_Page {
	public function init(){
		$this->setNeedLogin(false);
		parent::init();
		$this->userInfoLogic = new Account_Logic_UserInfo();
		$this->ajax = true;
	}
	
	/**
	 * 接口/account/edit/chphone
	 * 修改手机号smarty入口
	 * userinfo 左上角用户信息
	 */
	public function chphoneAction() {

	}
	
	/**
	 * 接口:/account/edit/chpwd
	 * 密码修改smarty入口
	 * userinfo 左上角用户信息
	 */
	public function chpwdAction() {
	 
	}
	
	/**
	 * 接口: /account/edit/chemail
	 * 用户修改邮箱
	 * userinfo 左上角用户信息
	 */
	public function chemailAction() {
		$userid = $this->userid;
		$userInfo = $this->userInfoLogic->getUserInfo($userid);
		$this->getView()->assign('userinfo',$userInfo);
	}
	
	/**
	 * 接口: /account/edit/emailsuccess
	 * 修改邮箱成功页面
	 * userinfo 左上角用户信息
	 */
	public function emailsuccessAction() {
		$userid = $this->userid;
		$userInfo = $this->userInfoLogic->getUserInfo($userid);
		$this->getView()->assign('userinfo',$userInfo);		
	}
}