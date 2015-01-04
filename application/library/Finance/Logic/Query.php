<?php 
/**
 * 财务模块查询逻辑类
 * @author lilu
 */
class Finance_Logic_Query extends Finance_Logic_Base {
	
	/**
	 * 查询余额
	 * @param string
	 */
	public function queryBalanceBg($userCustId) {
		$webroot = Base_Config::getConfig('web')->root;
		$chinapnr= Finance_Chinapnr_Logic::getInstance();
        
		$result = $chinapnr->queryBalanceBg("6000060000696947",self::MERCUSTID);
		$ret = array();
		$ret['avlBal']  = $result['AvlBal'];
		$ret['acctBal'] = $result['AcctBal'];
		$ret['frzBal']  = $result['FrzBal'];		
		return $ret;
		
	}
}