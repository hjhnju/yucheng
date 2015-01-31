<?php
/**
 * 投标完成
 */
class ConfirmController extends Base_Controller_Response {
    
    public function indexAction() {
        $baseLogic = new Finance_Logic_Base();
    	$retParam = $baseLogic->arrUrlDec($_REQUEST); 
        //签名
        $checkValue = isset($retParam['ChkValue']) ? $retParam['ChkValue'] : '';
        //验签字段
        $signKeys = array("CmdId", "RespCode", "MerCustId", "OrdId", "OrdDate", 
            "TransAmt", "UsrCustId", "TrxId", "IsFreeze", "FreezeOrdId","FreezeTrxId",
            "RetUrl","BgRetUrl","MerPriv","RespExt");
        $bolRet = Finance_Api::verifySign($signKeys, $retParam, $checkValue);
        if(!$bolRet){
           Base_Log::error(array(
                'msg' => '签名验证失败',
                'req' => $retParam,
                'ret' => $bolRet,
            ));
            $this->_view->assign('success', 0);
            return;
        }
        $merPriv = explode('_',$_REQUEST['MerPriv']);       
        $userid  = intval(urldecode($merPriv[0]));
        $loanId  = intval(urldecode($merPriv[1]));      
        $amount  = floatval($_REQUEST['TransAmt']);
        $orderId = strval(trim($_REQUEST['OrdId']));
                
        $logic  = new Invest_Logic_Invest();
        $bolRet = $logic->doInvest($orderId, $userid, $loanId, $amount);
        if ($bolRet) {
            $this->_view->assign('amount', Base_Util_Number::tausendStyle($amount));
            $this->_view->assign('success', 1);
        } else {
            Base_Log::error(array(
                'msg'     => '投资confirm保存失败',
                'orderId' => $orderId,
                'userid'  => $userid,
                'loanId'  => $loanId,
                'amount'  => $amount,
            ));
            $this->_view->assign('success', 0);
        }
    }
}
