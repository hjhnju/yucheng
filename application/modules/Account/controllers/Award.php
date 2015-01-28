<?php
/**
 * 奖励邀请页面
 */
class AwardController extends Base_Controller_Page {
	
	CONST PAGESIZE = 20;	
	public function init() {
		parent::init();
		$this->userInfoLogic = new Account_Logic_UserInfo();
		$this->ajax = true;
	}
	
	/**
	 * assign至前端邀请url
	 * inviteUrl 用户的专属邀请链接
	 * userinfo 左上角信息
	 */
	public function indexAction() {				
        $userid = $this->userid;	
        $webroot = Base_Config::getConfig('web')->root;
        
        $userInfo = $this->userInfoLogic->getUserInfo($this->objUser);
        $inviteUrl = Awards_Api::getInviteUrl($userid);
		$inviteUrl = ($inviteUrl != false) ? $inviteUrl : ""; //获取该用户的专属邀请链接
		
		$awardsInfo = Awards_Api::getAwards($userid);//获取邀请列表
		$this->getView()->assign('inviteUrl',$inviteUrl);	
		$this->getView()->assign('userinfo',$userInfo);
		$this->getView()->assign('inviterinfo',$awardsInfo);		
	}
	
	/**
	 * 接口  /account/award/receiveawards
	 * 领取奖励
	 * @param userId 用户id
	 * @return 标准json格式
	 * status 0: 成功
	 * status 1104:领取奖励失败
	 */
	public function receiveawardsAction() {
		$userid = $_REQUEST['id'];
		$userid = intval($userid);
		$objUser = $this->objUser;
		$huifuid = $objUser->huifuid;
		$logic = new Finance_Logic_Transaction();
		$outUserId = Finance_Logic_Base::MERCUSTID;
		$outAcctId = 'MDT000001';
		if($userid === $this->userid) {
			$transAmt = 30.00;
		    $inUserId = $userid;
		    $ret = $logic->transfer($outUserId,$outAcctId,$transAmt,$inUserId);
			if(!$ret) {
				Base_Log::error(array(
			        'msg'      => '领取奖励失败',
			        'userid'   => $userid,
			        'transAmt' => $transAmt,
				));
				$errCode = Finance_RetCode::RECEIVE_AWARDS_FAIL;
				$errMsg = Finance_RetCode::getMsg($errCode);
				$this->outputError($errCode,$errMsg);
				return ;
			}
			$this->output();
			return ;
		}				
        $transAmt = 20.00;
		$inUserId = $userid;
		$ret = $logic->transfer($outUserId,$outAcctId,$transAmt,$inUserId);
		if(!$ret) {
			Base_Log::error(array(
			    'msg'      => '领取奖励失败',
			    'userid'   => $userid,
			    'transAmt' => $transAmt,
			));
			$errCode = Finance_RetCode::RECEIVE_AWARDS_FAIL;
			$errMsg = Finance_RetCode::getMsg($errCode);
			$this->outputError($errCode,$errMsg);
			return ;
		}
		$this->output();
		return ;
	}
}
