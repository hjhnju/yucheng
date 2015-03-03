<?php
/**
 * 代取现接口
 *
 */
class MercashAction extends Yaf_Action_Abstract {
    public function execute() {      
        $userid  = isset($_REQUEST['userid'])? intval($_REQUEST['userid']) : 0;
        $tranAmt = isset($_REQUEST['amount'])? intval($_REQUEST['amount']) : 0.00;
        if($userid <= 0 || $tranAmt <= 0.00){
            return $this->getView()->assign('status', false);
        }
        $ret = Finance_Api::merCash($userid,$tranAmt);
        if(!$ret) {
            Base_Log::error(array(
                'msg'     => '商户代取现失败',
                'userid'  => $userid,
                'tranAmt' => $tranAmt,
            ));
            return $this->getView()->assign('status', false);
        }
        Base_Log::notice(array(
            'userid'  => $userid,
            'tranAmt' => $tranAmt,
        )); 
        return $this->getView()->assign('status', true);
    }
}
