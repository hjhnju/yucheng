<?php
/**
 * 消息未读数
 */
class UnreadController extends Base_Controller_Api {
    public function init(){
        $this->setNeedLogin(true);
        parent::init();
    }
	
	public function indexAction() {
        $uid = trim($_REQUEST['uid']);
        $this->msgLogic = new Msg_Logic_Msg();
        $ret = $this->msgLogic->getUnread($uid);
        return $this->ajax(array('num'=>$ret));
	}
}
