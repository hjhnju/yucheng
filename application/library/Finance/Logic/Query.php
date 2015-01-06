<?php 
/**
 * 财务模块查询逻辑类
 * @author lilu
 */
class Finance_Logic_Query extends Finance_Logic_Base {
	
	/**
	 * 查询余额
	 * @param string usrCustId
	 * @return array || boolean
	 */
	public function queryBalanceBg($userCustId) {
		$webroot = Base_Config::getConfig('web')->root;
		$chinapnr= Finance_Chinapnr_Logic::getInstance();
        
		$result = $chinapnr->queryBalanceBg("6000060000696947",self::MERCUSTID);
		if(is_null($result)) {
			return false;
		}	
		return $result;		
	}
	
	/**
	 * 查询银行卡
	 * @param string usrCustId
	 * @return array || boolean
	 */
	public function queryBankCard($userCustId,$cardid) {
		$webroot = Base_Config::getConfig('web')->root;
		$merCustId = strval(self::MERCUSTID);
		$userCustId = strval($userCustId);
		$chinapnr= Finance_Chinapnr_Logic::getInstance();
		$result = $chinapnr->queryCardInfo($merCustId, $userCustId,$cardid);
		if(is_null($result)) {
			return false;
		}
		return $result;
	}
	
	/**
	 * 商户子账户信息查询
	 * @return array || boolean
	 */
	public function queryAccts() {
		$merCustId = strval(self::MERCUSTID);
		$chinapnr= Finance_Chinapnr_Logic::getInstance();
		$result = $chinapnr->queryAccts($merCustId);
		if(is_null($result)) {
			return false;
		}
		$merAcct = $result['AcctDetails'];
		return $merAcct;
	}
}