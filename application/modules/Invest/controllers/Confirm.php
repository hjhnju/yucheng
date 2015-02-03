<?php
/**
 * 投标完成
 */
class ConfirmController extends Base_Controller_Response {
    
    /**
     * 对$_REQUEST进行urldecode
     * @param array
     * @return array || flase
     */   
    protected function arrUrlDec($arrParam) {
        $ret = array();
        foreach ($arrParam as $key => $value) {
            if(!is_array($value)) {
                $ret[$key] = urldecode($value);
            } else {
                $ret[$key] = $this->arrUrlDec($value);//对数组值进行递归解码
            }
        }
        return $ret;
    }
    
    public function indexAction() {
    	$retParam = $this->arrUrlDec($_REQUEST); 
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
            $this->redirect('/invest/fail?info=' . '签名验证失败');
            return false;
        }
        $merPriv = explode('_',$_REQUEST['MerPriv']);       
        $userid  = intval(urldecode($merPriv[0]));
        $loanId  = intval(urldecode($merPriv[1]));      
        $amount  = floatval($_REQUEST['TransAmt']);
        $orderId = strval(trim($_REQUEST['OrdId']));
                
        $logic  = new Invest_Logic_Invest();
        $bolRet = $logic->doInvest($orderId, $userid, $loanId, $amount);
        if ($bolRet) {
            $sess = Yaf_Session::getInstance();
            $sess->set('invest_amount', $amount);
            $this->redirect('/invest/success');
        } else {
            Base_Log::error(array(
                'msg'     => '投资confirm保存失败',
                'orderId' => $orderId,
                'userid'  => $userid,
                'loanId'  => $loanId,
                'amount'  => $amount,
            ));
            $this->redirect('/invest/fail?info=' . '投标失败，请联系客服');
        }
        return false;
    }
}
