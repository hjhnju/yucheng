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
		
		Awards_Api::receiveAwards($userid);
		//ajax返回结果
	}
	
	/**
	 * 接口  /account/award/getAwards
	 * 获取用户的邀请列表
	 * @return 标准json格式
	 * status 0:成功
	 * status 1105:获取奖励列表失败
	 * data=
	 * {
	 *     {
	 *         'userId' 用户id
	 *         'userName' 注册用户名
	 *         'phone' 用户手机号
	 *         'registProgress' 注册进度
	 *         'percent' 投资满额百分数
	 *         'awardAmt' 奖励金额
	 *         'isAwarded' 是否可以领取奖励  0--不可以   1--可以
	 *     }
	 * }
	 * 
	 * 
	 */
	public function  getAwards() {
		$userid = $this->getUserId();//from User Module
		$awardsInfo = Awards_Api::getAwards($userid);//获取邀请列表
		if($awardsInfo === false) {
			$this->outputError(Account_RetCode::GET_AWARDSLIST_FAIL,Account_RetCode::getMsg(Account_RetCode::GET_AWARDSLIST_FAIL));
		}
		$this->output($awardsInfo);
	}
}