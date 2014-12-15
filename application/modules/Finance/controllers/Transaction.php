<?php
/**
 * 交易类功能controller层
 * 网银充值
 * 提现
 *
 */
class TransactionController extends Base_Controller_Api{
   
   public function init(){
        parent::init();
        $this->transactionLogic = new Finance_Logic_Transaction();
   }
   
   /**
    * 充值controller层入口
    * @param String transAmt 交易金额(required)
    * @param String openBankId 开户银行代号(optional)
    * @param String gateBusiId 支付网关业务代号(optional)
    * @param String dcFlag 借贷记标记(optional)
    *
    */ 
   public function netSave(){
       $transAmt = $_REQUEST['transAmt'];
       $openBankId = $_REQUEST['openBankId'];
       $gateBusiId = $_REQUEST['gateBusiId'];
       $dcFlag = $_REQUEST['dcFlag'];
       //1.调用其他模块lib库得到所需参数
       //2.调用Logic层方法
     
   }

   /**
    * 提现controller层入口
    * @param String $transAmt 交易金额(required)
    * @param String $captcha 验证码(required)
    * @param String $openAcctId 开户银行帐号(optional)
    *
    */
   public function cash(){
       $transAmt = $_REQUEST['transAmt'];
       $captcha = $_REQUEST['captcha'];
       $openAcctId = $__REQUEST['openAcctId'];
       //1.验证验证码操作
       //2.调用其他模块lib库得到所需参数
       //3.调用Logic层方法
   }

}
