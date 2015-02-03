<?php 
/**
 * 财务模块基础逻辑类
 * @author lilu
 */
class Finance_Logic_Base {
    
    //汇付平台版本
    CONST VERSION_10 = "10";
    CONST VERSION_20 = "20";

    protected $merCustId;

    protected $chinapnr;

    public function __construct(){
        $arrConf          = Base_Config::getConfig('huifu', CONF_PATH . '/huifu.ini');
        $this->merCustId  = $arrConf['merCustId'];
        $this->privateKey = $arrConf['merchantPrivateKey'];
        $this->publicKey  = $arrConf['chinapnrPublicKey'];
        $this->chinapnr   = Finance_Chinapnr_Client::getInstance();
    }
    
    /**
     * 通过用户userid获取用户汇付id
     * @return string $huifuid
     */
    public function getHuifuid($userid){
        $userid  = intval($userid);
        $objUser = User_Api::getUserObject($userid);
        $huifuid = !empty($objUser) ? $objUser->huifuid : '';
        return $huifuid;
    }

}
    