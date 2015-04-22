<?php
/**
 * 消息标记已读
 */
class ReadController extends Base_Controller_Response {
    public function init(){
        $this->setNeedLogin(true);
        parent::init();
    }
	
    /**
     * 设置消息为已读状态
     * @param mid : 消息id
     */
	public function indexAction() {
        $this->msgLogic = new Msg_Logic_Msg();
        $mid = trim($_REQUEST['mid']);
        $ret = $this->msgLogic->setRead($mid);
        if(!$ret){
            $this->ajaxError($ret,Msg_RetCode::getMsg($ret));
        }
        $num = Msg_Api::getUnreadMsgNum($this->userid);
        $this->ajax(array('unreadMsg'=>$num));
	}
}
