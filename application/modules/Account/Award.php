<?php
/**
 * 奖励邀请页面
 */
class AwardController extends Base_Controller_Response {
	
	public function init() {
		parent::init();
		$this->ajax = true;
	}
	
	public function indexAction() {
		$userid = User_Api::getUserId();//from User Module
		$inviteUrl = Awards_Api::getInviteUrl($userid);
		$inviteUrl = isset($inviteUrl)?$inviteUrl:"";//获取该用户的专属邀请链接
		$this->getView()->assign("inviteUrl", $inviteUrl);
			
	}
	
	/**
	 * 接口5/account/award/receiveAwards
	 * 领取奖励
	 * @param userId 用户id
	 * @return 标准json格式
	 * status 0: 成功
	 * status 1104:领取奖励失败
	 */
	public function receiveAwardsAction() {
		
		Awards_Api::receiveAwards($userid);
		//ajax返回结果
	}
	
	/**
	 * 接口6/account/award/getAwards
	 * 获取用户的邀请列表
	 * @return 标准json格式
	 * status 0:成功
	 * status 1105:获取奖励列表失败
	 */
	public function  getAwards() {
		$userid = User_Api::getUserId();//from User Module
		$awardsInfo = Awards_Api::getAwards($userid);//获取邀请列表
		if($awardsInfo==false) {
			$this->outputError(Account_RetCode::GET_AWARDSLIST_FAIL,Account_RetCode::getMsg(Account_RetCode::GET_AWARDSLIST_FAIL));
		}
		$this->output($awardsInfo);
	}
}