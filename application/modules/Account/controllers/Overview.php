<?php
/**
 * 账户总览页
 */
class OverviewController extends Base_Controller_Page {

	private $huifuid;	
    public function init(){
        parent::init();
        $this->huifuid = !empty($this->objUser) ? $this->objUser->huifuid : '';
        $this->userInfoLogic = new Account_Logic_UserInfo();
        $this->ajax = true;
    }


    /**
     * /account/overview/index
     * 
     * 1.调用Finance_Api获取账户余额等信息
     * 2.调用Invest_Api获取收益信息     assign 数值至前端
     * 'openthirdpay' 1--开通  2--未开通
     * 'avlBal' 可用余额
     * 'acctBal' 资产总计
     * 'frzBal' 冻结金额
     * 'totalProfit' 累计收益
     * 'totalInvest' 累计投资
     * 'reposPrifit' 待收收益
     * 'reposPrincipal' 待收本金
     * 'rechargeurl' 充值url 
     * 
     * 'userinfo'=
     *  array('_username'
     *        '_realname'=array(
     *           'isopen' 1--开通  2--未开通
     *           'url' 跳转链接
     *        )
     *        '_phone'=array(
     *           'isopen' 1--开通  2--未开通
     *           'url' 跳转链接
     *        )
     *        'hiufu'=array(
     *           'isopen' 1--开通  2--未开通
     *           'url' 跳转链接
     *        )
     *        '_securedegree'=array(
     *           'score' 分数
     *           'degree' 1--低  2--中  3--高
     *        )
     *        'up' 提升安全等级链接
     *  )    
     *     
     */
    public function indexAction(){
        $userInfo = $this->userInfoLogic->getUserInfo($this->objUser);        
        if(empty($this->huifuid)) {
        	$avlBal = sprintf('%.2f',0);
        	$acctBal = sprintf('%.2f',0);
        	$frzBal = sprintf('%.2f',0);
        } else {
        	$userBg = $this->userInfoLogic->getUserBg($this->huifuid);
        	$avlBal = strval($userBg['avlBal']);
        	$acctBal = strval($userBg['acctBal']);
        	$frzBal = strval($userBg['frzBal']);
        }      

        $huifuid = $this->objUser->huifuid;
        $openthirdpay = isset($huifuid) ? 1 : 2;
        $rechargeurl = $this->webroot.'/account/cash/recharge';
        
        $money = Invest_Api::getUserEarnings($this->userid);
        
        
        $totalProfit = sprintf('%.2f',$money['all_income']);// 累计收益
        $totalInvest = sprintf('%.2f',$money['all_invest']);//累计投资
        $reposPrifit = sprintf('%.2f',$money['wait_interest']);//待收收益
        $reposPrincipal = sprintf('%.2f',$money['wait_capital']);//待收本金
        
        $this->getView()->assign("avlBal",$avlBal);
        $this->getView()->assign("acctBal",$acctBal);
        $this->getView()->assign("frzBal",$frzBal);
        $this->getView()->assign("totalProfit",$totalProfit);
        $this->getView()->assign("totalInvest",$totalInvest);
        $this->getView()->assign("reposPrifit",$reposPrifit);
        $this->getView()->assign("reposPrincipal",$reposPrincipal); 
        $this->getView()->assign("openthirdpay",$openthirdpay);         
        $this->getView()->assign('userinfo',$userInfo);  
        $this->getView()->assign('rechargeurl',$rechargeurl);
    }
    
    /**
     * /account/overview/profitcurve
     * 近半年收益曲线
     * @return 标准json
     * status 0 处理成功
     * status 1111
     * data: {
     *  x: ['2014-02', '2014-03'],
     *  y: [100, 1000]
     *  }
     */
    public function profitcurveAction() {
        $userid = $this->userid;
        $huifuid = $this->huifuid;
        $userName = $this->objUser->name;
        if(!isset($huifuid)) {
           // $this->ajax = false;
            $data = array(
            	'name' => $userName,
            );
            $this->outputView = 'test/noThirdPay.phtml';
            $this->output($data);
        } else {
            $now = intval(date("m",time()));
            $now = 7;
            if($now > 6) {
            	$arrRet = Invest_Api::getEarningsMonthly($userid,$now-5,$now);            	
            }
            
            foreach ($arrRet as $key => $value) {
                $x[] = $key;
                $y[] = $value;
            }
          //  var_dump($arrRet);die;
        	
            $ret = array(
                x => array('2014-05','2014-06','2014-07','2014-08','2014-09','2014-10'),
                y => array(10,20,500,60,49,1000),
            );          
            if($ret==false) {
                $this->outputError(Account_RetCode::GET_PROFIT_CURVE_FAIL,Account_RetCode::getMsg(Account_RetCode::GET_PROFIT_CURVE_FAIL));
                return ;
            }                        
            $this->output($ret);
      
        }       
    }
}
