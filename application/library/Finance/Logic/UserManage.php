<?php 
/**
 * 用户管理逻辑类
 * 用户开户
 * 用户绑卡
 * 用户删除银行卡
 * 企业开户
 * @author lilu
 */
class Finance_Logic_UserManage extends Finance_Logic_Base{
	
    /**
     * 用户开户
     * @param string userName
     * @param string userMp
     * @param string userid
     */
	public function userRegist($userName,$userMp,$userid) {
	    $webroot = Base_Config::getConfig('web')->root;
		$chinapnr = Finance_Chinapnr_Logic::getInstance();
		$merCustId   = strval(self::MERCUSTID);
		$bgRetUrl    = $webroot.'/finance/bgcall/userregist';
		$retUrl      = '';
		$usrId       = strval($userName);
		$usrMp       = strval($userMp);
		$merPriv     = strval($userid);
		$chinapnr->userRegister($merCustId, $bgRetUrl, $retUrl, $usrId, $usrMp, "", "", "", "", $merPriv, "");		
	}
	
	/**
	 * 企业开户
	 * 
	 * 
	 */
	public function corpRegist() {
		$webroot = Base_Config::getConfig('web')->root;
		$chinapnr = Finance_Chinapnr_Logic::getInstance();
		$merCustId   = strval(self::MERCUSTID);
		$busiCode = strval(self::BUSICODE);
		$bgRetUrl = $webroot.'/finance/bgcall/corpRegist';
		$chinapnr->corpRegister($merCustId,'', '', '',$busiCode,'','', '', '',$bgRetUrl, '');
	}
	/**
	 * 用户绑卡
	 * @param string userCusId 
	 * @param string userid
	 */
    public function userBindCard($usrCustId,$userid) {
		$webroot = Base_Config::getConfig('web')->root;
		$chinapnr= Finance_Chinapnr_Logic::getInstance();
		$merCustId = self::MERCUSTID;
		$usrCustId = strval($usrCustId);
		$bgRetUrl = $webroot.'/finance/bgcall/userbindcard';
		$merPriv = strval($userid);
		$chinapnr->userBindCard($merCustId,$usrCustId,$bgRetUrl,$merPriv);		
	}
	
    /**
     * 删除银行卡
     * @param string usrCusId
     * @param string cardId
     * @return array || boolean
     * 
     */
	public function delCard($usrCustId,$cardId) {
		$merCustId = strval(self::MERCUSTID);
		$usrCustId = strval($usrCustId);
		$cardId    = strval($cardId);
		$chinapnr= Finance_Chinapnr_Logic::getInstance();
		$return = $chinapnr->delCard($merCustId,$usrCustId,$cardId);
		if(is_null($return)) {
			return false;
		} else {
			return $return;
			//var_dump($return);
		}
	}
	/**
	 * 汇付登录
	 * @param string
	 */
	public function userLogin($usrCustId) {
		$chinapnr= Finance_Chinapnr_Logic::getInstance();
		$chinapnr->userLogin(self::MERCUSTID,$usrCustId);
	}
	
	/**
	 * 汇付用户信息修改
	 * @param string
	 */
	public function  acctModify($usrCustId) {
		$chinapnr= Finance_Chinapnr_ChinapnrLogic::getInstance();
        $chinapnr->accModify(self::MERCUSTID,$usrCustId);
	}
}