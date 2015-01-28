<?php
/**
 * 投标记录
 */
class ListController extends Base_Controller_Api {

    protected $needLogin = false;
        
    public function indexAction() {
        $loanId = intval($_REQUEST['id']);
        if (empty($loanId)) {
            $this->ajaxError(Base_RetCode::PARAM_ERROR);
        }
        
        $list = Invest_Api::getLoanInvests($loanId);
        $this->ajax($list);
    }
}
