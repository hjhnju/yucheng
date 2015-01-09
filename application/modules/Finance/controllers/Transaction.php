<?php
/**
 * 交易类功能controller层
 * 网银充值
 * 提现
 *
 */
class TransactionController extends Base_Controller_Api{
   
	private $transLogic;
	private $huifuid;
    public function init(){
        //for test
        //TODO remove
        $this->setNeedLogin(false);
        parent::init();
        $this->transLogic = new Finance_Logic_Transaction();
        $this->huifuid = !empty($this->objUser) ? $this->objUser->huifuid : '';
        
    }
   
    /**
     * 充值controller层入口
     * @param String transAmt 交易金额(required)
     * @param String openBankId 开户银行代号(optional)
     * @param String gateBusiId 支付网关业务代号(optional)
     * @param String dcFlag 借贷记标记(optional)
     *
     */ 
    public function netsaveAction(){
        $userid = $this->userid;
<<<<<<< HEAD
        $huifuid = $this->huifuid;	
=======
        $huifuid =!empty($this->objUser) ? $this->objUser->huifuid : '';    
>>>>>>> d7476e906649085ec344f4f15a66dfe88a59ab88
        $transAmt = round($_REQUEST['transAmt'],2);
        $openBankId = $_REQUEST['openBankId'];
        $gateBusiId = $_REQUEST['gateBusiId'];
        $dcFlag = $_REQUEST['dcFlag'];
<<<<<<< HEAD
        $this->transLogic->netsave($userid, $huifuid, $transAmt, $openBankId, $gateBusiId, $dcFlag);     
=======
        //Finance_Api::netSave($userid,$huifuid,$transAmt,$gateBusiId,$openBankId,$dcFlag);
        //TODO:remove
        //FOR TEST
        // Finance_Api::netSave($huifuid,$userid,"20.00","B2C","ICBC","D");
        
        $huifuid    = '6000060000696947';
        $transAmt   = '20.00';
        $gateBusiId = 'FPAY';
        $openBankId = 'ICBC';
        $dcFlag     = 'D';
        $transLogic = new Finance_Logic_Transaction();
        Base_Log::notice(array(
            'transAmt'   => $transAmt,
            'gateBusiId' => $gateBusiId,
            'openBankId' => $openBankId,
            'dcFlag' => $dcFlag,
        ));
        $transLogic->netsave($userid, $huifuid, $transAmt, $gateBusiId, $openBankId, $dcFlag);         
>>>>>>> d7476e906649085ec344f4f15a66dfe88a59ab88
   }

   /**
    * 提现controller层入口
    * @param String $transAmt 交易金额(required)
    * @param String $captcha 验证码(required)
    * @param String $openAcctId 开户银行帐号(optional)
    *
    */
   public function cashAction(){
       $transAmt = $_REQUEST['transAmt'];
       $captcha = $_REQUEST['captcha'];
       $openAcctId = $__REQUEST['openAcctId'];
       
       
       //1.验证验证码操作
       //2.调用其他模块lib库得到所需参数
       //3.调用Logic层方法
   }

}
