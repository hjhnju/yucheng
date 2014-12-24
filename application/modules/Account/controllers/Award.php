<?php
/**
 * 奖励邀请页面
 */
class AwardController extends Base_Controller_Response {
	
	CONST PAGESIZE = 20;
	
	public function init() {
		parent::init();
		$this->ajax = true;
	}
	
	public function indexAction() {				
        $userid = $this->getUserId();	
        $userid = isset($userid) ? $userid : 0;
		$inviteUrl = Awards_Api::getInviteUrl($userid);
		$inviteUrl = ($inviteUrl != false) ? $inviteUrl : ""; //获取该用户的专属邀请链接
		$this->getView()->assign("inviteUrl", $inviteUrl);			
	}
	
	/**
	 * 接口  /account/award/receiveAwards
	 * 领取奖励
	 * @param userId 用户id
	 * @return 标准json格式
	 * status 0: 成功
	 * status 1104:领取奖励失败
	 */
	public function receiveAwardsAction() {
		
		$ret = Awards_Api::receiveAwards($userid);
		if($ret === false) {
			$errCode = Account_RetCode::RECEIVE_AWARDS_FAIL;
			$errMsg = Account_RetCode::getMsg(Account_RetCode::RECEIVE_AWARDS_FAIL);
			$this->outputError($errCode,$errMsg);
		} else {
			$this->output();
		}
	}
	
	/**
	 * 接口  /account/award/getAwards
	 * 获取用户的邀请列表
	 * @param page
	 * @return 标准json格式
	 * status 0:成功
	 * status 1105:获取奖励列表失败
	 * data=
	 * {
	 *     {
     *         'tenderAmount' 投资金额
     *         'canBeAwarded' 是否可以领取奖励   1--达到奖励标准  2-- 未达到
     *         'userInfo'= {  
     *                         { 
     *                             'name' 注册用户名
     *                             'phone'用户手机号码 
     *                         }
     *                     }
	 *     }
	 * }
	 * 注意：第一项为该用户"我"的信息
	 * 
	 */
	public function  getAwards() {
		$page =isset($_REQUEST['page']) ? $_REQUEST['page'] :1;
		$userid = $this->getUserId();//from User Module
		$awardsInfo = Awards_Api::getAwards($userid, $page, $this->PAGESIZE);//获取邀请列表
		if($awardsInfo === false) {
			$errCode = Account_RetCode::GET_AWARDSLIST_FAIL;
			$errMsg = Account_RetCode::getMsg(Account_RetCode::GET_AWARDSLIST_FAIL);
			$this->outputError($errCode,$errMsg);
		} else {
			$this->output($awardsInfo);
		}		
	}
}
