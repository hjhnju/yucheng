<?php
/**
 * 微站投资列表
 */
class InvestController extends Base_Controller_Page {

    protected $loginUrl = '/m/login'; 
    
    const ERROR_KEY = 'invest_error';
    
    public function init(){
        //未登录不跳转
        $this->setNeedLogin(false);
        
        parent::init();
    }
  
    /*
     * 投资列表页面  
     * /m/invest/index 
     */
    public function indexAction() {
    	$this->getView()->assign('title', "投资列表");
    	$logic = new Invest_Logic_Invest();
        // 登录用户增加账号余额信息
        if (!empty($this->userid)) {
            $user = $logic->getUserBalance($this->objUser);
            $this->_view->assign('userBalance', $user);
        }
    }
    /*
     * 投资项目详情页面  
     * /m/invest/detail 
     */
    public function detailAction() {
       
        $this->getView()->assign('title', "项目详情");

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
        
        // 增加错误信息
        $sess = Yaf_Session::getInstance();
        if ($sess->has(self::ERROR_KEY)) {
            $error = $sess->get(self::ERROR_KEY);
            $sess->del(self::ERROR_KEY);
            $this->_view->assign('error', Invest_RetCode::getMsg($error));
        }
        
    }
 

    /**
     * 立即投资页面  /m/invest/bid
     * @return [type] [description]
     */
    public function bidAction() {
        $this->getView()->assign('title', "立即投资");
        $id = $this->getInt('id');  
        if(empty($this->userid)){
            $this->redirect("/m/login?u=/m/invest/bid?id=$id");
        }        
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
        
        // 增加错误信息
        $sess = Yaf_Session::getInstance();
        if ($sess->has(self::ERROR_KEY)) {
            $error = $sess->get(self::ERROR_KEY);
            $sess->del(self::ERROR_KEY);
            $this->_view->assign('error', Invest_RetCode::getMsg($error));
        }
    }


     /*
     * 投资成功页面  
     * /m/invest/index 
     */
    public function successAction() {
        $this->getView()->assign('title', "投资成功"); 
    }

     /*
     * 投资失败页面  
     * /m/invest/index 
     */
    public function failAction() {
        $this->getView()->assign('title', "投资失败");

        
    }
}
