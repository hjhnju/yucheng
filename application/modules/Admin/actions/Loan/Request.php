<?php
/**
 * 申请借款
 * @author hejunhua
 *
 */
class RequestAction extends Yaf_Action_Abstract {
    public function execute() {
        $userid   = isset($_REQUEST['userid']) ? intval($_REQUEST['userid']) : null;
        if(empty($userid)){
        	$errorMsg = '未指定贷款申请人';
        	$this->getView()->assign('error_msg', $errorMsg);
        }
        $objUser = User_Api::getUserObject($userid);
        $arrUser = array();
        $arrUser['name'] = $objUser->name;

        $this->getView()->assign('user', $arrUser);
    }
}