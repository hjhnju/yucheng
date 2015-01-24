<?php
/**
 * 借款详情编辑
 * @author hejunhua
 *
 */
class EditAction extends Yaf_Action_Abstract {
    public function execute() {

        // $userid   = isset($_REQUEST['userid']) ? intval($_REQUEST['userid']) : null;
        // if(empty($userid)){
        // 	$errorMsg = '未指定贷款申请人';
        // 	$this->getView()->assign('error_msg', $errorMsg);
        // }
        // $objUser = User_Api::getUserObject($userid);
        // $arrUser = array();
        // $arrUser['name'] = $objUser->name;

        // $this->getView()->assign('user', $arrUser);

        //贷款id
        $loanid = isset($_REQUEST['loanid']) ? intval($_REQUEST['loanid']) : null;
        if(!empty($loanid)){
        	$this->getView()->assign('loanid', $loanid);
        }
    }
}