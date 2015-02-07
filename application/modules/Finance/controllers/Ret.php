<?php 
/**
 * 汇付回跳中转url
 * 1. 状态提示（成功/失败）
 * 2. 按类型 文字描述
 * 3. 按类型 6秒跳转至响应的url
 * @author hejunhua
 */
class RetController extends Base_Controller_Page {

    public function init(){
        $this->setNeedLogin(false);
        parent::init();
        
    }

    /**
     * http://www.xingjiaodai.com.cn:8081/finance/ret?CmdId=NetSave&RespCode=0&TransAmt=101.11
     */ 
    public function indexAction() {
        $cmdId   = isset($_REQUEST['CmdId']) ? $_REQUEST['CmdId'] : '';
        $retCode = isset($_REQUEST['RespCode']) ? intval($_REQUEST['RespCode']) : false;
        
        //提示页面暂不用验签
        $arrData = array();
        if(!isset($this->cmdMap[$cmdId])){
            $arrData['desc']     = '当前页面不存在';
            $arrData['backurl']  = $this->webroot;
            $arrData['backname'] = '首页';
            $arrData['status']   = 0;
            $this->getView()->assign('data', $arrData);
            return;
        }
        $arrData = $this->cmdMap[$cmdId];
        $bolSucc  = ($retCode === Base_RetCode::SUCCESS) ? true : false;

        //为主动投标增加的逻辑，后续优化
        if($bolSucc && $cmdId === Finance_Chinapnr_Client::CMDID_INITIATIVE_TENDER){
            $orderId = $_REQUEST['OrdID'];
            $mixRet  = true;
            $i       = 0;
            while (is_null($mixRet) && $i <= 3) {
                $mixRet = Finance_Logic_Order::getTenderStatus($orderId);
                sleep(1);
                $i = $i + 1;
            }
            $bolSucc = is_null($mixRet)? true : $mixRet;
        }///为主动投标增加的逻辑，后续优化

        $cmdDesc = $bolSucc ? '成功' : '失败';

        $_REQUEST['status'] = $cmdDesc;
        $arrVars = array();
        foreach ($arrData['varkeys'] as $field) {
            $arrVars[$field] = isset($_REQUEST[$field]) ? $_REQUEST[$field] : '';
        }
        $arrData['status'] = $bolSucc;
        $arrData['desc']   = vsprintf($arrData['desc'], $arrVars);

        $this->getView()->assign('data', $arrData);
        Base_Log::notice(array(
            'req'  => $_REQUEST,
            'data' => $arrData,
        ));
        return;
    }  

    protected $cmdMap = array(
        Finance_Chinapnr_Client::CMDID_USER_REGISTER => array(
            'desc'     => '开通第三方支付账户%s',
            'backurl'  => '/account/overview',
            'backname' => '我的账户',
            'varkeys'  => array('status'),
        ),
        Finance_Chinapnr_Client::CMDID_NET_SAVE => array(
            'desc'     => '您的充值金额为%s，网银充值%s',
            'backurl'  => '/account/overview',
            'backname' => '我的账户',
            'varkeys'  => array('TransAmt', 'status'),
        ),
        Finance_Chinapnr_Client::CMDID_INITIATIVE_TENDER => array(
            'desc'     => '您的投标金额为%s，投标%s',
            'backurl'  => '/account/overview',
            'backname' => '我的账户',
            'varkeys'  => array('TransAmt','status'),
        ),
    );  
}