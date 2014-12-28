<?php
/**
 * 我的消息页面
 */
class MessageController extends Base_Controller_Response {
	
	public function init() {
		parent::init();
		$this->userInfoLogic = new Account_Logic_UserInfo();
		$this->ajax = true;
	}
	
	/**
	 * 接口7/account/message/index
	 * 渲染我的消息页面smarty
	 * userinfo 左上角用户信息
	 */
	public function indexAction() {
		$userid = $this->getUserId();
		$userInfo = $this->userInfoLogic->getUserInfo($userid);
		$this->getView()->assign('userinfo',$userInfo);
	}
	
	/**
	 * 接口9/account/message/detail
	 * 点击获取某一条消息详细内容
	 * @param messId 消息id
	 * @param isReaded 1--未读  2--已读   
	 * status 0 处理成功
	 * status 1110 获取消息内容失败
	 * data=
	 * {
	 *     {
	 *         'msgIId' 消息id
	 *         'msgIContent' 消息详细类型
	 *         'url' = 
	 *         {
	 *             {
	 *                'urlId' urlID
	 *                'urlName' 超链接名称 
	 *                'url' url链接地址
	 *             }
	 *         }//消息中的URL
	 *         
	 *     }
	 * }
	 * 
	 */
	public function msgDetailAction() {
		
	}
	
}
