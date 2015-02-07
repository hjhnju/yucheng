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
        // 对打款状态 标记为满标状态
        if ($loan['status'] == Invest_Type_InvestStatus::PAYING) {
            $loan['status'] = Invest_Type_InvestStatus::FULL_CHECK;
        }

        //检查是否允许投标
        $logic = new Invest_Logic_Invest();
        $loan['allow_invest'] = $logic->allowInvest($this->userid, $loan['id']);
        
        if (!empty($this->userid)) {
            $amount = Finance_Api::getUserAvlBalance($this->userid);
            $user   = array(
                'uid'         => $this->userid,
                'username'    => $this->objUser->name,
                'amount'      => $amount,
                'amount_text' => number_format(strval($amount),2,'.',','),
            );

            $this->_view->assign('user', $user);
        }
        $this->_view->assign('data', $loan);
        
        // 增加错误信息
        $sess = Yaf_Session::getInstance();
        $errorKey = 'invest_error';
        if ($sess->has($errorKey)) {
            $error = $sess->get($errorKey);
            $sess->del($errorKey);
            $this->_view->assign('error', Invest_RetCode::getMsg($error));
        }
    }
}
