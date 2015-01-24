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
		$_emailParam = $_REQUEST['param'];
		$emailParam = explode('_',$_emailParam);
		$emailKey = strval($emailParam[0]);
		$emailAuth = strval($emailParam[1]);
		
		$_id = $_REQUEST['id'];
		$id = explode('_',$_id);
		$idKey = strval($id[0]);
		$idAuth = strval($id[1]);
		
		$newEmail = Base_Util_Secure::decodeSand(Base_Util_Secure::PASSWD_KEY,$emailAuth,$emailKey);	
		$userid = Base_Util_Secure::decodeSand(Base_Util_Secure::PASSWD_KEY,$idAuth,$idKey);
		if(!$newEmail || !$userid) {
			//解密失败
			return;			
		}
		$ret = User_Api::setEmail($userid,$newEmail);
		if(!$ret) {
			//入库失败
			return;
		}
		
		
			
	}
}