<?php
/**
 * 获取借款详情
 * @author hejunhua
 *
 */
class GetdetailAction extends Base_Controller_Action {
    public function execute() {

        $loanId = isset($_REQUEST['loanid']) ? intval($_REQUEST['loanid']) : null;
        if (empty($loanId)) {
            $this->ajaxError(Base_RetCode::PARAM_ERROR);
        }
        
        $detail = Loan_Api::getLoanDetail($loanId);
        Base_Log::debug(array('detail'=>$detail));
        $this->ajax($detail);
    }
}