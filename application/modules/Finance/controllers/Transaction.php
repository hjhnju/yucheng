<?php
/**
 * 交易类功能controller层
 * 网银充值
 * 提现
 * @author lilu
 */
class TransactionController extends Base_Controller_Page{
   
   	private $transLogic;
	private $huifuid;
    public function init(){
        parent::init();
        $this->transLogic = new Finance_Logic_Transaction();
        $this->huifuid = !empty($this->objUser) ? $this->objUser->huifuid : '';
        
    }
   
    /**
     * 用于平台对自己的子账户充值controller层入口
     * @param String transAmt 交易金额(required)
     * @param String openBankId 开户银行代号(optional)
     * @param String gateBusiId 支付网关业务代号(optional)
     * @param String dcFlag 借贷记标记(optional)
     *
     */ 
    public function netsaveAction(){
        $userid  = $this->userid;
        $arrConf = Base_Config::getConfig('huifu', CONF_PATH . '/huifu.ini');
        $huifuid  = $arrConf['merCustId'];
        $transAmt = $_REQUEST['transAmt'];
        $transAmt = sprintf('%.2f',$transAmt);
        $gateBusiId = 'B2B';
        $openBankId = 'CIB';
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

   /**
    * 提现controller层入口
    * @param tring $transAmt 交易金额(required)
    * @param string phone 手机号码////前端还没传给我
    * @param string $captcha 验证码(required)
    * @param string $openAcctId 开户银行帐号(optional)
    *
    */
    public function cashAction(){
   	    $userid = intval($this->userid);
   	    $phone = $_REQUEST['phone'];
        $transAmt = floatval($_REQUEST['transAmt']);
        $captcha = $_REQUEST['captcha'];
        
        //验证验证码
        $openAcctId = strval($_REQUEST['openAcctId']);
        $type = 1;////////////////////////////////////////
/*         $smsRet = User_Api::checkSmscode($phone,$captcha,$type);
        if(!$smsRet) {
        	Base_Log::error(array(
        		'msg'     => '验证码验证失败',
        		'phone'   => $phone,
        		'captcha' => $captcha,
        		'type'    => $type,
        	));
        	return ;
        } */
        $transAmt = sprintf('%.2f',$transAmt);
        $transAmt = sprintf('%.2f',20);
        $openAcctId = '';
        $this->transLogic->cash($userid,$transAmt,$openAcctId);            
    }

}
