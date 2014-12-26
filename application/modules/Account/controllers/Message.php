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
	 * @param type 0--全部  1--未读  2--已读
	 * @param page 前端页码
	 * @return 标准json
	 * status 0:成功
	 * status 1106:获取消息列表失败
	 * data=
	 * {
	 * 	   'page'页码
	 *     'pageall':10 总共页码 
	 *     'all' 数据条数
	 *     'list'=
	 *     {
	 *         'msgId' 消息id
     *         'msgIType' 消息类型
     *         'msgIContent' 消息内容
     *         'sendTime' 发送时间
     *         'isReaded' 1--未读  2--已读   
	 *     }
	 * }
	 */
	public function indexAction() {
		$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : 0;
		$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 0;
		
		
		//$msg = Msg_Api::getMsg($msgType);
		//$msg合法性判断
		//$this->output($msg);
		//$this->outputError()
        
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
