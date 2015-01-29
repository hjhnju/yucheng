<?php
/**
 * 投标完成
 */
class ConfirmController extends Base_Controller_Response {
    
    public function indexAction() {
        $baseLogic = new Finance_Logic_Base();
    	$retParam = $baseLogic->arrUrlDec($_REQUEST); 
        //签名
<<<<<<< HEAD
        $checkValue = isset($retParam['ChkValue']) ? $retParam['ChkValue'] : '';
        //验签字段
        $signKeys = array("CmdId", "RespCode", "MerCustId", "OrdId", "OrdDate", 
            "TransAmt", "UsrCustId", "TrxId", "IsFreeze", "FreezeOrdId","FreezeTrxId",
            "RetUrl","BgRetUrl","MerPriv","RespExt");
        $bolRet = Finance_Api::verifySign($signKeys, $retParam, $checkValue);
        if(!$bolRet){
=======
       $checkValue = isset($_REQUEST['ChkValue']) ? $_REQUEST['ChkValue'] : '';

        //验签字段
       $signKeys = array("CmdId", "RespCode", "MerCustId", "OrdId", "OrdDate", 
           "TransAmt", "UsrCustId", "TrxId", "IsFreeze", "FreezeOrdId","FreezeTrxId",
           "RetUrl","BgRetUrl","MerPriv","RespExt");

       $bolRet = Finance_Api::verifySign($signKeys, $_REQUEST, $checkValue);       
       if(!$bolRet){
>>>>>>> 94d44c1d742f178d8372f5bf03a41dcd0b007f2a
            Base_Log::error(array(
                'msg' => '签名验证失败',
                'req' => $retParam,
                'ret' => $bolRet,
            ));
            $this->_view->assign('success', 0);
        }
        $merPriv = explode('_',$_REQUEST['MerPriv']);       
        $userid  = intval(urldecode($merPriv[0]));
        $loan_id = intval(urldecode($merPriv[1]));      
        $amount  = floatval($_REQUEST['TransAmt']);
                
        $logic  = new Invest_Logic_Invest();
        $bolRet = $logic->doInvest($userid, $loan_id, $amount);
        if ($bolRet) {
            $this->_view->assign('success', 1);
        } else {
            $this->_view->assign('success', 0);
        }
    }
}
