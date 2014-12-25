<?php
/**
 * 安全中心页面
 */
class SecureController extends Base_Controller_Response{
	
	private $retData;
	public function init() {
		//$this->setNeedLogin(false); for test
		$this->secureLogic = new Account_Logic_Secure();
		$this->retData = array();
		parent::init();
		$this->ajax= true;
	}
	
	/**
	 * /account/secure/index
	 * 获取用户个人资料
	 * assign数值至前端
	 * 'phone' 1--认证  2--未认证
	 * 'phoneurl' 手机后的url
	 * 'certinfo' 1--认证  2--未认证
	 * 'certinfourl' 认证信息后的url
	 * 'thirdpay' 1--开通  2--未开通
	 * 'thirdpayurl' 第三方后url
	 * 'email' 1--绑定  2--未绑定 
	 * 'emailpay' email后的url
	 * 'modifypswurl' 修改密码url
	 * 'thirdPlatform' 第三方绑定平台  qq weixin wenbo
	 * 'thirdNickName' 第三方平台昵称 
	 * 'thirdloginurl' 第三方平台登录url
	 */
	public function indexAction() {	
		$webroot = Base_Config::getConfig('web')->root;
		$userid = $this->getUserId();
		$userObj = User_Api::getUserObject($userid);
		//$userObj = json_decode(json_encode(array('name'=>'lilu', 'phone'=>'18611015043','realname'=>'jiangbianliming',)));//for test
		$phone = $userObj->phone;//获取用户手机号码
		$realname = $userObj->realname;//获取用户真实姓名
		$certificateContent = $userObj->certificateContent;//获取用户的证件信息
		$huifuid = $userObj->huifuid;//获取用户的汇付id--判断用户有没有开通汇付
		$email = $userObj->email;
		$this->retData['phone']['bind'] = isset($phone) ? 1 : 2;//若设置手机，前端assign值1，否则assign值0，下同
		if($this->retData['phone'] == 1) {
			$this->retData['phone']['url'] = $webroot.'/account/secure/modifyphone';
		} else {
			$this->retData['phone']['url'] = $webroot.'/account/edit/chphone';
		}
		$this->retData['certificateInfo']['bind'] = (isset($realname) && isset($certificateContent)) ? 1 : 2;
		if($this->retData['certificateInfo'] == 1) {
			$this->retData['certificateInfo']['url'] = $webroot.'/account/secure/bindcertinfo';
		} else {
			$this->retData['certificateInfo']['url'] = '';
		}
		$this->retData['thirdPay']['bind'] = isset($huifuid) ? 1 : 2;
		if($this->retData['thirdPay']['bind'] == 1) {
			$this->retData['thirdpay']['url'] = $webroot.'/account/secure/bindthirdpay';
		} else {
			$this->retData['thirdpay']['url'] = $webroot.'/account/secure/viewthirdPay';
		}
		$this->retData['email']['bind'] = isset($email) ? 1 : 2;	
		if($this->retData['email']['bind'] == 1) {
			$this->retData['thirdpay']['url'] = $webroot.'/account/secure/bindemail';
		} else {
			$this->retData['thirdpay']['url'] = $webroot.'/account/edit/chemail';
		}
		$thirdLogin = array('qq','weibo','weixin');		
		foreach ($thirdLogin as $k=>$v) {
			if($userObj->getOpenid($v)!=false) {
				$this->retData['thirdPlatform'] = $v;
				$this->retData['thirdNickName'] = $userObj->getNickname($v);
				break;
			}
		} 			 	
		$this->getView()->assign('phone', $this->retData['phone']['bind']);
		$this->getView()->assign('phoneurl',$this->retData['phone']['url']);
		
		$this->getView()->assign('certinfo', $this->retData['certificateInfo']['bind']);
		$this->getView()->assign('certinfourl', $this->retData['certificateInfo']['url']);
		
		$this->getView()->assign('thirdpay', $this->retData['thirdPay']['bind']);
		$this->getView()->assign('thirdpayurl', $this->retData['thirdPay']['url']);
		
		$this->getView()->assign('email', $this->retData['email']['bind']);
		$this->getView()->assign('emailurl', $this->retData['email']['url']);
		
		$this->getView()->assign('modifypswurl',$webroot.'/account/edit/chpwd');	
			
		$this->getView()->assign('thirdPlatform',$this->retData['thirdPlatform']);
		$this->getView()->assign('thirdPlatform',$this->retData['thirdNickName']);
		$this->getView()->assign('thirdloginurl','');
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
	public function secureDegreeAction() {
		$this->retData['phone'] = 1;  //若设置手机，前端assign值1，否则assign值0，下同
		$this->retData['certificateInfo'] =  1;
		$this->retData['thirdPay'] = 2;
		$this->retData['email'] = 2;
		$ret = $this->secureLogic->scoreDegree($this->retData);
		$this->output($ret);		
	}	
	
	/**
	 * 接口/account/secure/bindphone
	 * 未认证手机号时点击去认证手机号
	 * 返回标准json
	 */
	public function bindphoneAction() {
		$this->ajax = false;
		$this->output();
	}
	
	/**
	 * 接口/account/secure/bindcertinfo
	 * 未认证证件信息时去认证证件信息
	 * 返回标准json
	 * status 0:成功
	 */
	public function bindcertinfo() {
		$this->ajax = false;
		$this->output();
	}
	
	/**
	 * 接口 /account/secure/bindthirdpay
	 * 未开通第三方支付时点击去开通第三方支付
	 * 返回标准json
	 * status 0:成功
	 */
	public function bindthirdpayAction() {
		$this->ajax = false;
		$this->output();
	}
	
	/**
	 * 接口 /account/secure/bindemail
	 * 未绑定Email时点击去绑定Email
	 * 返回标准json
	 * status 0:成功
	 */
	public function bindemailAction() {
		$this->ajax = false;
		$this->output();
	}
	
	/**
	 * 接口/account/secure/modifyphone
	 * 绑定手机后修改入口
	 * 返回标准json
	 * status 0:成功
	 */
	public function modifyphoneAction() {
		$this->ajax = false;
		$this->output();
	}
	
	/**
	 * 接口/account/secure/modifyemail
	 * 绑定邮箱后修改入口
	 * 返回标准json
	 * status 0:成功
	 */
	public function modifyemailAction() {
		$this->ajax = false;
		$this->output();
	}
	
	/**
	 * 接口/account/secure/viewthirdPay
	 * 查看第三方支付
	 * 返回标准json
	 * status 0:成功
	 */
	public function viewthirdPayAction() {
		$this->ajax = false;
		$this->output();
	}	
}
