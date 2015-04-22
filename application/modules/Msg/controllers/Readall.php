<?php
/**
 * 标记所有消息已读
 */
class ReadallController extends Base_Controller_Api {
    public function init(){
        $this->setNeedLogin(true);
        parent::init();
    }
	
	public function indexAction() {
        $uid = trim($_REQUEST['uid']);
        $this->msgLogic = new Msg_Logic_Msg();
        $ret = $this->msgLogic->setReadAll($uid);
        if($ret){
            $num = Msg_Api::getUnreadMsgNum($this->userid);
            return $this->ajax(array('unreadMsg'=>$num));
        }
        return $this->ajaxError();
	}
}
