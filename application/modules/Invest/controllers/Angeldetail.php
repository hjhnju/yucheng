<?php
/**
 * 投资项目详情
 */
class AngeldetailController extends Base_Controller_Response {
    protected $needLogin = false;
    
    const ERROR_KEY = 'invest_error';
    
    public function indexAction() {
        $code = isset($_REQUEST['angel'])?$_REQUEST['angel']:'';
        $angle = new Angel_Object_Angel();
        $angle->fetch(array('angelcode'=>$code));
        $arrAngel['code'] = $code;
        $arrAngel['name'] = $angle->angelname;
        $arrAngel['headurl'] = $angle->angelimage;
        $id = $this->getInt('id');
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
        
        // 登录用户增加账号余额信息
        if (!empty($this->userid)) {
        	$user = $logic->getUserBalance($this->objUser);
            $this->_view->assign('userBalance', $user);
        }
        
        // 获取剩余还款本息 还款期数 下次还款日
        $loan['left_month'] = $logic->getRestRefundMonths($loan['refunds']);
        $loan['next_date'] = $logic->getNextRefundDate($loan['refunds']);
        $loan['rest_total'] = $logic->getRestRefundsTotal($loan['refunds']);
        $loan['rest_total'] = Base_Util_Number::tausendStyle($loan['rest_total']);

        // 对输出进行格式化
        $data = $logic->formatDetail($loan);
        $this->_view->assign('data', $data);
        $this->_view->assign('angel',$arrAngel);
        // 增加错误信息
        $sess = Yaf_Session::getInstance();
        if ($sess->has(self::ERROR_KEY)) {
            $error = $sess->get(self::ERROR_KEY);
            $sess->del(self::ERROR_KEY);
            $this->_view->assign('error', Invest_RetCode::getMsg($error));
        }
    }
}
