<?php
/**
 * 代取现借口
 *
 */
class MercashAction extends Yaf_Action_Abstract {
    public function execute() {      
        $userid = 10018;
        $tranAmt = '99600.00';
        $ret = Finance_Api::merCash($userid,$tranAmt);
        if(!$ret) {
        	Base_Log::error(array(
        		'msg' => '商户代取现失败',
        		'userid' => $userid,
        		'transAmt' => $tranAmt,
        	));
        	return $this->getView()->assign('status', false);
        }
        Base_Log::notice(array(
            'userid' => $userid,
            'transAmt' => $tranAmt,
        )); 
        return $this->getView()->assign('status', true);
    }
}
