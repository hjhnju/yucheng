<?php
/**
 * 投标完成
 */
class ConfirmController extends Base_Controller_Response {
    
    public function indexAction() {

    	$arrRet = Finance_Api::initiativeTenderBg($_REQUEST);
        if(empty($arrRet)){
           Base_Log::error(array(
                'msg' => '财务确认投标冻结失败',
                'req' => $_REQUEST,
                'ret' => $arrRet,
            ));
            return $this->redirect('/invest/fail?info=' . '投标冻结失败');
        }
        $orderId = strval($arrRet['orderId']);
        $userid  = intval($arrRet['userid']);
        $loanId  = intval($arrRet['loanId']);      
        $amount  = floatval($arrRet['transAmt']);
                
        $logic  = new Invest_Logic_Invest();
        $bolRet = $logic->doInvest($orderId, $userid, $loanId, $amount);
        if (!$bolRet) {
            Base_Log::error(array(
                'msg'     => '投资确认失败',
                'orderId' => $orderId,
                'userid'  => $userid,
                'loanId'  => $loanId,
                'amount'  => $amount,
            ));
            return $this->redirect('/invest/fail?info=' . '投标失败，请确认是否已满标');
        }

        $sess = Yaf_Session::getInstance();
        $sess->set('invest_amount', $amount);
        $this->redirect('/invest/success');
    }
}
