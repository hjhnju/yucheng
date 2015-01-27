<?php
/**
 * 借款发布
 * @author hejunhua
 *
 */
class PublishAction extends Yaf_Action_Abstract {
    public function execute() {

        $loanid = isset($_REQUEST['loanid']) ? intval($_REQUEST['loanid']) : null;

        $logic  = new Admin_Logic_Loan();
        $bolRet = $logic->publish($loanId);
    }
}