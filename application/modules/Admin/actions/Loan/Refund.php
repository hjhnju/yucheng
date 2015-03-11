<?php
/**
 * 手动还款
 * @author hejunhua
 *
 */
class RefundAction extends Yaf_Action_Abstract {
    public function execute() {

        $loanId   = isset($_REQUEST['loanid']) ? intval($_REQUEST['loanid']) : null;
        $refundId = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : null;

        $arrRet = Loan_Api::doRefund($refundId, $loanId);
        if($arrRet['status'] !== Base_RetCode::SUCCESS){

            $this->getView()->assign('status', false);
            $this->getView()->assign('error_msg', $arrRet['statusInfo']);
            Base_Log::error(array(
                'msg'      => '手动还款失败',
                'arrRet'   => $arrRet,
                'loanId'   => $loanId,
                'refundId' => $refundId,
            ));
            return;
        }

        $this->getView()->assign('status', true);
        Base_Log::notice(array(
            'msg'      => '手动还款成功',
            'loanId'   => $loanId,
            'refundId' => $refundId,
        ));
    }
}