<?php
/**
 * 投标完成
 */
class ConfirmController extends Base_Controller_Response {
    
    public function indexAction() {
        //签名
        $checkValue = isset($_REQUEST['ChkValue']) ? $_REQUEST['ChkValue'] : '';

        //验签字段
        $signKeys = array("CmdId", "RespCode", "MerCustId", "OrdId", "OrdDate", 
            "TransAmt", "UsrCustId", "TrxId", "IsFreeze", "FreezeOrdId","FreezeTrxId",
            "RetUrl","BgRetUrl","MerPriv","RespExt");

        $bolRet = Finance_Api::verifySign($signKeys, $_REQUEST, $checkValue);
        if(!$bolRet){
            Base_Log::error(array(
                'msg' => '签名验证失败',
                'req' => $_REQUEST,
                'ret' => $bolRet,
            ));
            $this->_view->assign('success', 0);
            return;
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
