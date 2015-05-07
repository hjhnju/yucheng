<?php
/**
 * 微站我的个人中心
 */
class AccountController extends Base_Controller_Page{
	protected $loginUrl = '/m/login'; 
    
    const ERROR_KEY = 'invest_error';
    
    public function init(){
        //未登录跳转
        $this->setNeedLogin(false);       
        parent::init();
        $this->huifuid       = !empty($this->objUser) ? $this->objUser->huifuid : '';
        $this->userInfoLogic = new Account_Logic_UserInfo();
        $this->ajax          = true;
    }
     /*
     * 账户总览页面   我的账户
     * /m/account/overview
     */
    public function overviewAction() {
        $userInfo     = $this->userInfoLogic->getUserInfo($this->objUser);        
        $userBg       = Finance_Api::getUserBalance($this->userid);
        $avlBal       = Base_Util_Number::tausendStyle($userBg['AvlBal']);
        $acctBal      = Base_Util_Number::tausendStyle($userBg['AcctBal']);
        $frzBal       = Base_Util_Number::tausendStyle($userBg['FrzBal']);
        
        $huifuid      = $this->objUser->huifuid;
        $openthirdpay = isset($huifuid) ? 1 : 2;
        $rechargeurl  = $this->webroot.'/account/cash/recharge';
        $withdrawurl = "$this->webroot".'/account/cash/withdraw';
        $money        = Invest_Api::getUserEarnings($this->userid);
        
        $thismoth     = strtotime(date("Y-m"));
        $monthMoney   = Invest_Api::getEarningsMonthly($this->userid,$thismoth);
        $monthProfit  = $monthMoney[date("Y-m")];
        $monthProfit = Base_Util_Number::tausendStyle(floatval($monthProfit));// 本月收益

        // 累计收益
        $totalProfit = Base_Util_Number::tausendStyle(floatval($money['all_income']));// 累计收益
        // 累计投资
        $totalInvest = Base_Util_Number::tausendStyle(floatval($money['all_invest']));//累计投资
        // 待收收益
        $reposPrifit = Base_Util_Number::tausendStyle(floatval($money['wait_interest']));//待收收益
        // 待收本金
        $reposPrincipal = Base_Util_Number::tausendStyle(floatval($money['wait_capital']));//待收本金
        // 资产总计
        $totalAsset = Base_Util_Number::tausendStyle(floatval($userBg['AvlBal'] + $userBg['FrzBal'] 
            + $money['wait_interest'] + $money['wait_capital']));
        
        $this->getView()->assign("avlBal",$avlBal);
        $this->getView()->assign("acctBal",$acctBal);
        $this->getView()->assign("totalAsset", $totalAsset);
        $this->getView()->assign("frzBal",$frzBal);
        $this->getView()->assign("totalProfit",$totalProfit);
        $this->getView()->assign("monthProfit",$monthProfit);
        $this->getView()->assign("totalInvest",$totalInvest);
        $this->getView()->assign("reposPrifit",$reposPrifit);
        $this->getView()->assign("reposPrincipal",$reposPrincipal); 
        $this->getView()->assign("openthirdpay",$openthirdpay);         
        $this->getView()->assign('userinfo',$userInfo);  
        $this->getView()->assign('rechargeurl',$rechargeurl);
        $this->getView()->assign('withdrawurl',$withdrawurl);
    }
}