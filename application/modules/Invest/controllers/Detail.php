<?php
/**
 * 投资项目详情
 */
class DetailController extends Base_Controller_Response {
    protected $needLogin = false;
    
    public function indexAction() {
        $id = intval($_GET['id']);
        if (empty($id)) {
            $this->outputError(Base_RetCode::PARAM_ERROR);
        }
        
        $loan = Loan_Api::getLoanDetail($id);
        
        //检查是否允许投标
        $logic = new Invest_Logic_Invest();
        $loan['allow_invest'] = $logic->allowInvest($this->userid, $loan['id']);
        
        if (!empty($this->userid)) {
            $amount = Invest_Api::getAccountAvlBal($this->userid);
            $user   = array(
                'uid'         => $this->userid,
                'username'    => $this->objUser->name,
                'amount'      => $amount,
                'amount_text' => number_format($amount, 2),
            );
            $this->_view->assign('user', $user);
        }
        $this->_view->assign('data', $loan);
    }
}
