<?php 
/**
 * 用户管理逻辑类
 * 用户开户
 * 用户绑卡
 * 企业开户
 * @author lilu
 */
class Finance_Logic_UserManage extends Finance_Logic_Base{
	
    /**
     * 用户开户
     * @param string
     * @param string
     * @param array
     */
	public function userRegist($userName,$usrMp) {
	    $webroot = Base_Config::getConfig('web')->root;
		$userid = $this->getUserId();
		$chinapnr= Finance_Chinapnr_Logic::getInstance();
		$merCustId   = self::MERCUSTID;
		$bgRetUrl    = $webroot.'/finance/bgcall/userregist';
		$retUrl      = "";
		$usrId       = $userName;
		$usrMp       = $usrMp;
		$chinapnr->userRegister($merCustId, $bgRetUrl, $retUrl, $usrId, $usrMp);
		
	}
	
	/**
	 * 用户绑卡
	 * @param string 
	 */
    public function userBindCard($usrCustId) {
		$webroot = Base_Config::getConfig('web')->root;
		$chinapnr= Finance_Chinapnr_Logic::getInstance();
		$merCustId = self::MERCUSTID;
		$usrCustId = "6000060000696947";
		$bgRetUrl = $webroot.'/finance/bgcall/userbindcard';
		$merPriv = "";
		$chinapnr->userBindCard($merCustId,$usrCustId,$bgRetUrl,$merPriv);		
	}
	
	/**
	 * 汇付登录
	 * @param string
	 */
	public function userLogin($usrCustId) {
		$webroot = Base_Config::getConfig('web')->root;
		$chinapnr= Finance_Chinapnr_Logic::getInstance();
		$chinapnr->userLogin(self::MERCUSTID,"6000060000696947");
	}
	
	/**
	 * 汇付用户信息修改
	 * @param string
	 */
	public function  acctModify($usrCustId) {
	    $webroot = Base_Config::getConfig('web')->root;
		$chinapnr= Finance_Chinapnr_ChinapnrLogic::getInstance();
        $chinapnr->accModify(self::MERCUSTID,"6000060000696947");
	}
}