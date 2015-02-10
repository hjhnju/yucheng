<?php
/**
 * 安全中心页面
 */
class SecureController extends Base_Controller_Page{
	
	private $retData;
	public function init() {
		$this->retData = array();
		parent::init();
		$this->userInfoLogic = new Account_Logic_UserInfo();
	}
	
	/**
	 * /account/secure/index
	 * 获取用户个人资料
	 * assign数值至前端
	 * 'phone' 1--认证  2--未认证
	 * 'phonenum' 手机号码
	 * 'phoneurl' 手机后的url
	 * 
	 * 'certinfo' 1--认证  2--未认证
	 * 'realname' 真实姓名
	 * 'certinfonum' 身份证号
	 * 'certinfourl' 认证信息后的url
	 * 
	 * 'thirdpay' 1--开通  2--未开通
	 * 'huifuid' 汇付平台id
	 * 'thirdpayurl' 第三方后url
	 * 
	 * 'email' 1--绑定  2--未绑定 
	 * 'emailnum' 邮件
	 * 'emailurl' email后的url
	 * 
	 * 'chpwdurl' 修改url的接口 
	 * 
	 * 'bindthirdlogin'是否绑定第三方登录  1--是  2--否
	 * 'thirdPlatform' 第三方绑定平台  1--qq 2--weixin 3--weibo
	 * 'thirdNickName' 第三方平台昵称 
	 * 'thirdloginurl' 第三方平台登录url
	 */
	public function indexAction() {	
		$webroot = Base_Config::getConfig('web')->root;
		$userid = $this->userid;
		$userinfo = $this->userInfoLogic->getUserInfo($this->objUser);		
		$phone = $userinfo['phone']['isopen'];//用户手机是否开通
		$phonenum = $userinfo['phone']['value'];//用户手机号码
	
		$phonenum =  Base_Util_String::starPhone($phonenum);
		if($phone == 2) {
			$phoneurl = $webroot.'/user/open';
		} else {
			$phoneurl = $webroot.'/account/edit/chphone';
		}		
		$certinfo = $userinfo['realname']['isopen'];//实名认证是否开通
		$realname = $userinfo['realname']['realnameValue'];//用户实名
		$certinfonum = $userinfo['realname']['certValue'];//用户的证件值
		$certinfonum = substr_replace($certinfonum,'**************',2,14);
		if($certinfo == 2) {
			$certinfourl = $webroot.'/user/open';
		} else {
			$certinfourl = '';
		}
		
		$thirdpay = $userinfo['huifu']['isopen'];//用户是否开通了汇付托管
		$huifuid = $userinfo['huifu']['value'];//用户的汇付id			
		if($thirdpay == 2) {
			$thirdpayurl = $webroot.'/user/open';
		} else {
			$thirdpayurl = $webroot.'/finance/usermanage/login';
		}
		
		$email = $userinfo['email']['isopen'];//用户是否开通了email
	
		$emailnum = $userinfo['email']['value'];
		$emailnum = Base_Util_String::starEmail($emailnum);
		
		if($email == 2) {
			$emailurl = $webroot.'/account/edit/chemail';
		} else {
			$emailurl = $webroot.'/account/edit/chemail';				
		}
		
		$chpwdurl = $webroot.'/account/edit/chpwd';
		
		$thirdBindRet = User_Api::checkBind($userid);

		$this->retData['thirdPlatform'] = false;
		$this->retData['thirdNickName'] = false;
		//没有绑定第三方登陆
		if(empty($thirdBindRet)) {
			$this->retData['bindthirdlogin'] = 2;
			$thirdloginurl = $webroot.'/account/secure/bindthirdlogin';
		} else {
			$this->retData['bindthirdlogin'] = 1;
			$this->retData['thirdPlatform'] = $thirdBindRet['type'];
			$this->retData['thirdNickName'] = $thirdBindRet['nickname'];
			$thirdloginurl = $webroot.'/account/secure/unbindthird';
		}
		if(!empty($this->objUser->loginTime)){
		    $lastLoginTime = $this->objUser->loginTime;
		}else{
		    $lastLoginTime = $this->objUser->createTime;
		}
		$lastLoginTime = date("Y-m-d H:i:s",$lastLoginTime);
		$this->getView()->assign('lastLoginTime',$lastLoginTime);	
        $this->getView()->assign('userinfo',$userinfo);		
		
		$this->getView()->assign('phone', $phone);
		$this->getView()->assign('phoneurl',$phoneurl);
		$this->getView()->assign('phonenum',$phonenum);
		
		$this->getView()->assign('certinfo', $certinfo);
		$this->getView()->assign('certinfourl', $certinfourl);
		$this->getView()->assign('realname',$realname);
		$this->getView()->assign('certinfonum',$certinfonum);
		
		$this->getView()->assign('thirdpay', $thirdpay);
		$this->getView()->assign('thirdpayurl',$thirdpayurl);
		$this->getView()->assign('huifuid', $huifuid);
		
		
		$this->getView()->assign('email', $email);
		$this->getView()->assign('emailurl', $emailurl);
		$this->getView()->assign('emailnum', $emailnum);
		
		$this->getView()->assign('chpwdurl',$chpwdurl);
		
		
		$this->getView()->assign('bindthirdlogin',$this->retData['bindthirdlogin']);				
		$this->getView()->assign('thirdPlatform',$this->retData['thirdPlatform']);
		$this->getView()->assign('thirdNickName',$this->retData['thirdNickName']);
		$this->getView()->assign('thirdloginurl',$thirdloginurl);
	}

	/**
	 * 安全等级检测
	 * @return 标准json
	 * status 0 处理成功
	 * data=
	 * {
	 *     {
	 *         'score' 检测分数
	 *         'secureDegree'安全等级   1--低   2--中   3--高
	 *     }
	 * }
	 * 
	 */
	public function securedegreeAction() {	    
		$userinfo = $this->userInfoLogic->getUserInfo($this->objUser);		
		$ret = array(
		    'score'         => $userinfo['securedegree']['score'],
		    'secureDegree'  => $userinfo['securedegree']['degree'],  
		);
		$this->output($ret);		
	}	
	
	/**
	 * 接口/account/secure/bindphone
	 * 未认证手机号时点击去认证手机号
	 * 返回标准json
	 */
	public function bindphoneAction() {

	}
	
	/**
	 * 接口/account/secure/bindcertinfo
	 * 未认证证件信息时去认证证件信息
	 * 返回标准json
	 * status 0:成功
	 */
	public function bindcertinfoAction() {

	}
	
	/**
	 * 接口 /account/secure/bindthirdpay
	 * 未开通第三方支付时点击去开通第三方支付
	 * 返回标准json
	 * status 0:成功
	 */
	public function bindthirdpayAction() {

	}
	
	/**
	 * 接口 /account/secure/bindemail
	 * 未绑定Email时点击去绑定Email
	 * 返回标准json
	 * status 0:成功
	 */
	public function bindemailAction() {

	}
		
	/**
	 * 接口/account/secure/viewthirdPay
	 * 查看第三方支付
	 * 返回标准json
	 * status 0:成功
	 */
	public function viewthirdPayAction() {

	}	
	
	/**
	 * 接口/account/secure/bindthirdlogin
	 * 绑定第三方登录接口
	 * 返回标准json
	 * status 0:成功
	 */
	public function bindthirdloginAction() {

	}
    
	
}
