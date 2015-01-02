<?php
/**
 * 消息详情
 */
class DetailController extends Base_Controller_Api {
    public function init(){
        $this->setNeedLogin(true);
        parent::init();
    }
	
	public function indexAction() {
	    $mid = trim($_REQUEST['mid']);
        $this->msgLogic = new Msg_Logic_Msg();
        $ret = $this->msgLogic->getDetail($mid);
        if(empty($ret)){
            return $this->ajaxError();
        }
        return $this->ajax($ret);
	}
}
