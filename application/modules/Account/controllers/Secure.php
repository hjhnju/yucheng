<?php
/**
 * 安全中心页面
 */
class SecureController extends Base_Controller_Response{
	
	private $retData;
	public function init() {
		//$this->setAutoJump(false); for test
		$this->retData = array();
		parent::init();
		$this->ajax= true;
	}
	
	/**
	 * 获取用户个人资料
	 * assign数值至前端
	 * 'phone' 1--认证  2--未认证
	 * 'certificateInfo' 1--认证  2--未认证
	 * 'thirdPay' 1--开通  2--未开通
	 * 'email' 1--绑定  2--未绑定 
	 * 'thirdPlatform' 第三方绑定平台  qq weixin wenbo
	 * 'thirdNickName' 第三方平台昵称 
	 */
	public function indexAction() {
		
		$userid = $this->getUserId();
		$userObj = User_Api::getUserObject($userid);
		//$userObj = json_decode(json_encode(array('name'=>'lilu', 'phone'=>'18611015043','realname'=>'jiangbianliming',)));//for test
		$phone = $userObj->phone;//获取用户手机号码
		$realname = $userObj->realname;//获取用户真实姓名
		$certificateContent = $userObj->certificateContent;//获取用户的证件信息
		$huifuid = $userObj->huifuid;//获取用户的汇付id--判断用户有没有开通汇付
		$email = $userObj->email;
		$this->retData['phone'] = isset($phone) ? 1 : 2;//若设置手机，前端assign值1，否则assign值0，下同
		$this->retData['certificateInfo'] = (isset($realname) && isset($certificateContent)) ? 1 : 2;
		$this->retData['thirdPay'] = isset($huifuid) ? 1 : 2;
		$this->retData['email'] = isset($email) ? 1 : 2;	
		$thirdLogin = array('qq','weibo','weixin');		
		foreach ($thirdLogin as $k=>$v) {
			if($userObj->getOpenid($v)!=false) {
				$this->retData['thirdPlatform'] = $v;
				$this->retData['thirdNickName'] = $userObj->getNickname($v);
				break;
			}
		} 			
		$this->getView()->assign('phone', $this->retData['phone']);
		$this->getView()->assign('certificateInfo', $this->retData['certificateInfo']);
		$this->getView()->assign('thirdPay', $this->retData['thirdPay']);
		$this->getView()->assign('email', $this->retData['email']);				
		$this->getView()->assign('thirdPlatform',$this->retData['thirdPlatform']);
		$this->getView()->assign('thirdPlatform',$this->retData['thirdNickName']);
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
		$ret = array();
		$sum = 0;
		foreach ($this->retData as $k=>$v) {
			if($v==1) {
				$sum += 25;
			}
		}
		$ret['score'] = $sum;
		if($sum==0 || $sum==25 || $sum==50) {
			$ret['secureDegree'] = 1;
		} elseif ($sum==75) {
			$ret['secureDegree'] = 2;
		} else {
			$ret['secureDegree'] = 3;
		}
		$this->output($ret);		
	}		 
}
