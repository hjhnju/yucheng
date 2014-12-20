<?php
/**
 * 我的消息页面
 */
class MessageController extends Base_Controller_Response {
	
	public function init() {
		parent::init();
		$this->ajax = true;
	}
	
	/**
	 * 接口7/account/message/index
	 * 获取Msg_Api消息列表，消息详情
	 * @return 标准json
	 * status 0:成功
	 * status 1106:获取消息列表失败
	 */
	public function indexAction() {
		//$msg = Msg_Api::getMsg($msgType);
		//$msg合法性判断
		//$this->output($msg);
		//$this->outputError()
        
	}
	
	/**
	 * 接口8/account/message/list
	 * 获取Msg_Api特定消息列表，消息详情
	 * @param msgType 0--未读  1--已读
	 * @return 标准json
	 * status 0:成功
	 * status 1106:获取消息列表失败
	 */
	public function listAction() {
		$msgType = $_REQUEST['msgType'];
		//$msg = Msg_Api::getMsg($msgType);
		//$msg合法性判断
		//$this->output($msg);
		//$this->outputError()
	
	}
}
