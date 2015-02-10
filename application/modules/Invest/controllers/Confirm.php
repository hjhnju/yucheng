<?php
/**
 * 投标完成
 */
class ConfirmController extends Base_Controller_Response {
    
    public function indexAction() {
        $arrRet = $_REQUEST;
        if(!isset($arrRet['RespCode']) || $arrRet['RespCode'] !== '000' 
            || $arrRet['CmdId'] !== Finance_Chinapnr_Client::CMDID_INITIATIVE_TENDER){
           Base_Log::error(array(
                'msg' => '汇付返回投标冻结失败',
                'req' => $arrRet,
            ));
            return $this->redirect('/invest/fail?info=' . '投标冻结失败');
        }

        $orderId = intval($arrRet['OrdId']);
        $amount  = floatval($arrRet['TransAmt']);

        //TODO:前端优化，等待几秒
        $mixRet = null;
        $i      = 0;
        while (is_null($mixRet) && $i <= 10) {
            //true||false||null
            $mixRet = Invest_Logic_ChkStatus::getInvestStatus($orderId);
            sleep(1);
            $i = $i + 1;
        }
        $bolSucc = is_null($mixRet)? false : $mixRet;

        if (!$bolSucc) {
            Base_Log::debug(array(
                'msg'     => '投资失败',
                'orderId' => $orderId,
                'amount'  => $amount,
            ));
            return $this->redirect('/invest/fail?info=' . '投标失败，请确认是否已满标');
        }

        $sess = Yaf_Session::getInstance();
        $sess->set('invest_amount', $amount);
        $this->redirect('/invest/success');
    }
}
