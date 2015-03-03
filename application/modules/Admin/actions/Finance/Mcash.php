<?php
/**
 * 平台充值
 * @author hejunhua
 *
 */
class McashAction extends Yaf_Action_Abstract {
    public function execute() {
        /**
        * 用于平台对自己的子账户充值controller层入口
        * @param String transAmt 交易金额(required)
        * @param String openBankId 开户银行代号(optional)
        * @param String gateBusiId 支付网关业务代号(optional)
        * @param String dcFlag 借贷记标记(optional)
        *
        */
        if(!empty($_POST)){
            $transAmt   = floatval($_POST['amount']);
            $openBankId = trim($_POST['bankid']);//'CIB';
            Base_Log::notice($_POST);
            if(!in_array($openBankId, array('CIB', 'BJRCB')) || $transAmt <=0.00){
                $this->getView()->assign('error_msg', '输入错误:'.$openBankId .','.$transAmt);
                return;
            }
            $userid     = 10000;//hjhnju
            $arrConf    = Base_Config::getConfig('huifu', CONF_PATH . '/huifu.ini');
            $huifuid    = $arrConf['merCustId'];
            $transAmt   = sprintf('%.2f',$transAmt);
            $gateBusiId = 'B2B';
            $dcFlag     = 'D';
            Base_Log::notice(array(
                'userid'     => $userid,
                'huifuid'    => $huifuid,
                'transAmt'   => $transAmt,
                'gateBusiId' => $gateBusiId,
                'openBankId' => $openBankId,
                'dcFlag'     => $dcFlag,
            ));
            $logic = new Finance_Logic_Transaction();
            $logic->netsave($userid, $huifuid, $transAmt, $openBankId, $gateBusiId, $dcFlag);         
        }
    }
}
