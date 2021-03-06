<?php
/**
 * 充值提现页
 * 充值与提现的controller入口位于Fiance模块
 * TODO:: 调用Finance_Logic改为调用Finance_Api
 */
class CashController extends Base_Controller_Page {
    
    private $huifuid;
    private $phone;
    private $transLogic;

    public function init() {
        parent::init();
        $this->huifuid       = !empty($this->objUser) ? $this->objUser->huifuid : '';
        $this->phone         = !empty($this->objUser) ? $this->objUser->phone : '';
        $this->userInfoLogic = new Account_Logic_UserInfo();
        $this->transLogic    = new Finance_Logic_Transaction();
        $this->ajax          = true;
    }
    
    /**
     * 调用财务模块Finance_Api获取 账户余额
     * assign至前端即可
     * phone 用户手机号码
     * bindbank 0--未绑定  1--绑定了银行卡
     * banknum  银行卡号
     * bankID  银行编号
     * avlBal 可用金额
     * withdrawfee 提现费用
     *
     * userInfo 左上角用户信息
     */
    public function indexAction() {     
        $userBg   = Finance_Api::getUserBalance($this->userid); 
        $avlBal   = Base_Util_Number::tausendStyle(strval($userBg['AvlBal']));
        $acctBal  = Base_Util_Number::tausendStyle(strval($userBg['AcctBal']));
        $userinfo = $this->userInfoLogic->getUserInfo($this->objUser);
    
        $rechargeurl = "$this->webroot".'/account/cash/recharge';
        $withdrawurl = "$this->webroot".'/account/cash/withdraw';
        //assign至前端
        $this->getView()->assign('avlBal', $avlBal);
        $this->getView()->assign('acctBal',$acctBal);
        $this->getView()->assign('userinfo',$userinfo);
        $this->getView()->assign('rechargeurl',$rechargeurl);
        $this->getView()->assign('withdrawurl',$withdrawurl);
    }
    
    /**
     * /account/cash/recharge
     * 充值入口
     */
    public function rechargeAction() {
        if(empty($this->huifuid)) {
            $redirectUrl = $this->webroot.'/user/open';
            $this->redirect($redirectUrl);
        }
        if(!empty($_POST)) {
            $userid     = $this->userid;
            $huifuid    = $this->huifuid;
            $transAmt   = floatval($_REQUEST['value']);
            $transAmt   = sprintf('%.2f',$transAmt);
            $openBankId = strval($_REQUEST['id']);
            $gateBusiId = 'B2C';
            $dcFlag     = 'D';
            Base_Log::notice(array(
                'userid'     => $userid,
                'huifuid'    => $huifuid,
                'transAmt'   => $transAmt,
                'gateBusiId' => $gateBusiId,
                'openBankId' => $openBankId,
                'dcFlag'     => $dcFlag,
            ));
            $this->transLogic->netsave($userid, $huifuid, $transAmt, $openBankId, $gateBusiId, $dcFlag);            
        }       
        $userinfo = $this->userInfoLogic->getUserInfo($this->objUser);
        $this->getView()->assign('userinfo',$userinfo);     
    }
    
    /**
     * 提现入口
     */
    public function withdrawAction() {
        $huifuid = $this->huifuid;
        if(empty($huifuid)) {
            $redirectUrl = $this->webroot.'/user/open';
            $this->redirect($redirectUrl);
        }
        $userid = intval($this->userid);        
        $phone  = $this->phone;
        $avlBal = Finance_Api::getUserAvlBalance($this->userid);
        if(!empty($_POST)) {                        
            $userinfo = $this->userInfoLogic->getUserInfo($this->objUser);
            $bankInfo = $this->userInfoLogic->getuserCardInfo($huifuid);
            $bindBank = $bankInfo['bindbank'];
            $bankNum  = $bankInfo['banknum'];
            $bankID   = $bankInfo['bankID'];
            //TODO:?not empty为什么还要assigin
            $this->getView()->assign('bindbank', $bindBank);
            $this->getView()->assign('banknum', $bankNum);
            $this->getView()->assign('bankID', $bankID);
            $this->getView()->assign('avlBal', $avlBal);
            $this->getView()->assign('userinfo',$userinfo);
            $this->getView()->assign('withdrawfee','2');
            $this->getView()->assign('phone',$this->phone);
            
            $transAmt   = floatval($_REQUEST['value']);
            $openAcctId = isset($_REQUEST['openAcctId']) ? strval($_REQUEST['openAcctId']) : '';
            $transAmt = sprintf('%.2f',$transAmt);
            $openAcctId = strval($bankNum);
            $this->transLogic->cash($userid,$transAmt,$openAcctId);
        }
        
        $userinfo = $this->userInfoLogic->getUserInfo($this->objUser);
        $bankInfo = $this->userInfoLogic->getuserCardInfo($huifuid);
        $bindBank = $bankInfo['bindbank'];
        $bankNum  = $bankInfo['banknum'];
        $bankNum  = substr_replace($bankNum,'*********',4,13);
        $bankID   = $bankInfo['bankID'];
        $this->getView()->assign('bindbank', $bindBank);
        $this->getView()->assign('banknum', $bankNum);
        $this->getView()->assign('bankID', $bankID);
        $this->getView()->assign('avlBal', $avlBal);
        $this->getView()->assign('userinfo',$userinfo);
        $this->getView()->assign('withdrawfee','2');
        $this->getView()->assign('phone',$this->phone);
    }
    
    /**
     * /account/cash/rechargesuc
     * assign
     * status 0--充值  1--提现
     * 充值成功
     */
    public function rechargesucAction() {
    
    }

    /**
     * /account/cash/rechargesuc
     * assign 
     * status 0--充值  1--提现
     * 提现成功
     */
    public function withdrawsucAction() {
    
    }
}