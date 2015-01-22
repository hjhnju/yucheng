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
     * autoRedirect || return false
     */
	public function userRegist($userName,$userid,$userMp='') {
		if(!isset($userName) || !isset($userid)) {
			Base_Log::error(array(
				'msg'      => '请求参数错误',
				'userName' => $userName,
				'userid'   => $userid,
			));
			return false;
		}
		$userName = strval($userName);
		$userid = strval($userid);
		$userMp = strval($userMp);
		
	    $webroot   = Base_Config::getConfig('web')->root;
		$chinapnr  = Finance_Chinapnr_Logic::getInstance();
		
		$merCustId = strval(self::MERCUSTID);
		$bgRetUrl  = $webroot.'/finance/bgcall/userregist';
		$retUrl    = '';
		$usrId     = $userName;
		$usrMp     = $userMp;
		$usrName   = '';
		$idType    = '';
		$idNo      = '';
		$usrEmail  = '';
		$charSet   = '';
		$merPriv   = $userid;
		$chinapnr->userRegister($merCustId, $bgRetUrl, $retUrl, $usrId, $usrMp, 
		    $usrName, $idType, $idNo, $usrEmail, $merPriv, $charSet);
	}
	
	/**
	 * 企业开户
	 * @param int userid
	 * @param string userName
	 * @param string busiCode
	 * @param string instuCode optional
	 * @param string taxCode optional
	 * autoRedirect || return false
	 * 
	 */
	public function corpRegist($userid,$userName,$busiCode,$instuCode='',$taxCode='') {
		if(!isset($userid) || !isset($userName) || !isset($busiCode)) {
			Base_Log::error(array(
				'msg'      => '请求参数错误',
				'userid'   => $userid,
				'userName' => $userName,
				'busiCode' => $busiCode,
			));
			return false;
		}
		$userid = strval($userid);
		$userName = strval($userName);
		$busiCode = strval($busiCode);
		$instuCode = strval($instuCode);
		$taxCode = strval($taxCode);
		
		$webroot = Base_Config::getConfig('web')->root;
		$chinapnr = Finance_Chinapnr_Logic::getInstance();
		
		$merCustId = strval(self::MERCUSTID);
		$usrId = $userName;
		$usrName = '';
		$merPriv = $userid;
		$charSet = '';
		$guarType = '';
		$bgRetUrl = $webroot.'/finance/bgcall/corpRegist';
		$reqExt = '';		
		$chinapnr->corpRegister($merCustId, $usrId, $usrName, $instuCode, $busiCode, 
		    $taxCode, $merPriv, $charSet, $guarType, $bgRetUrl, $reqExt);
	}
	/**
	 * 用户绑卡
	 * @param string userCusId 
	 * @param string userid
	 * autoRedirect || return false
	 */
    public function userBindCard($usrCustId,$userid) {
    	if(!isset($usrCustId) || !isset($userid)) {
    		Base_Log::error(array(
    			'msg'        => '请求参数错误',
    			'userCustId' => $usrCustId,
    			'userid'     => $userid,
    		));
    		return false;
    	}
    	$userid = strval($userid);
    	$usrCustId = strval($usrCustId);
    	
		$webroot = Base_Config::getConfig('web')->root;
		$chinapnr = Finance_Chinapnr_Logic::getInstance();
		
		$merCustId = strval(self::MERCUSTID);
		$bgRetUrl = $webroot.'/finance/bgcall/userbindcard';
		$merPriv = $userid;
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