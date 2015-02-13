<?php
/**
 * 投资项目详情
 */
class DetailController extends Base_Controller_Response {
    protected $needLogin = false;
    
    const ERROR_KEY = 'invest_error';
    
    public function indexAction() {
        $id = intval($_GET['id']);
        if (empty($id)) {
            $this->outputError(Base_RetCode::PARAM_ERROR);
        }

        //检查是否允许投标
        $logic = new Invest_Logic_Invest();
        $loan = $logic->getLoanDetail($id);
        if (empty($loan)) {
            $this->redirect('/');
            exit;
        }
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
        //紧急备用
        if($id === 100001 && $loan['status'] === Invest_Type_InvestStatus::REFUNDING){
             $loan['rest_total'] = Base_Util_Number::tausendStyle(10115.07);
             $loan['left_month'] = 1;
             $loan['next_date']  = strftime("2015-03-12 10:30:00");
        }
        if($id === 100000 && $loan['status'] === Invest_Type_InvestStatus::REFUNDING){
             $loan['rest_total'] = Base_Util_Number::tausendStyle(100920.55);
             $loan['left_month'] = 1;
             $loan['next_date']  = strftime("2015-03-13 10:30:00");
        }
        //待删除


        $this->_view->assign('data', $loan);

        
        // 增加错误信息
        $sess = Yaf_Session::getInstance();
        if ($sess->has(self::ERROR_KEY)) {
            $error = $sess->get(self::ERROR_KEY);
            $sess->del(self::ERROR_KEY);
            $this->_view->assign('error', Invest_RetCode::getMsg($error));
        }
    }
}
