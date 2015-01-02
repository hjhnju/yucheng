<?php 
class Finance_Msgtype {
	
	/**
	 * 消息类型
	 */
	const CMDID_USER_REGISTER= "UserRegister"; //用户开户,页面浏览器方式
	const CMDID_BG_REGISTER= "BgRegister"; //后台用户开户,后台数据流方式
	const CMDID_USER_BIND_CARD= "UserBindCard"; //用户绑卡,页面浏览器方式
	const CMDID_BG_BIND_CARD= "BgBindCard"; //后台接口绑卡,后台数据流方式
	const CMDID_USER_LOGIN= "UserLogin"; //用户登录,页面浏览器方式
	const CMDID_ACCT_MODIFY= "AcctModify"; //账户信息修改,页面浏览器方式
	const CMDID_CORP_REGISTER= "CorpRegister"; //担保类型企业开户接口,页面浏览器方式
	const CMDID_DEL_CARD= "DelCard"; //删除银行卡接口,后台数据流方式
	const CMDID_NET_SAVE= "NetSave"; //网银充值,页面浏览器方式
	const CMDID_POS_WH_SAVE= "PosWhSave"; //商户无卡代扣充值,后台数据流方式
	const CMDID_USR_FREEZE_BG= "UsrFreezeBg"; //资金（货款）冻结,后台数据流方式
	const CMDID_USR_UN_FREEZE= "UsrUnFreeze"; //资金（货款）解冻,后台数据流方式
	const CMDID_INITIATIVE_TENDER= "InitiativeTender"; //主动投标,页面浏览器方式
	const CMDID_AUTO_TENDER= "AutoTender"; //自动投标,后台数据流方式
	const CMDID_TENDER_CANCLE= "TenderCancle"; //投标撤销,页面浏览器方式
	const CMDID_AUTO_TENDER_PLAN= "AutoTenderPlan"; //自动投标计划,页面浏览器方式
	const CMDID_AUTO_TENDER_PLAN_CLOSE= "AutoTenderPlanClose"; //自动投标关闭,页面浏览器方式
	const CMDID_LOANS= "Loans"; //自动扣款（放款）,后台数据流方式
	const CMDID_REPAYMENT= "Repayment"; //自动扣款（还款）,后台数据流方式
	const CMDID_TRANSFER= "Transfer"; //转账（商户用）,后台数据流方式
	const CMDID_CASH_AUDIT= "CashAudit"; //取现复核,后台数据流方式
	const CMDID_CASH= "Cash"; //取现,页面浏览器方式
	const CMDID_USR_ACCT_PAY= "UsrAcctPay"; //用户账户支付,页面浏览器方式
	const CMDID_MER_CASH= "MerCash"; //商户代取现接口,后台数据流方式
	const CMDID_USR_TRANSFER= "UsrTransfer"; //前台用户间转账接口,页面浏览器方式
	const CMDID_CREDIT_ASSIGN= "CreditAssign"; //债权转让接口,页面浏览器方式
	const CMDID_AUTO_CREDIT_ASSIGN= "AutoCreditAssign"; //自动债权转让接口,后台数据流方式
	const CMDID_FSS_TRANS= "FssTrans"; //生利宝交易接口,页面浏览器方式
	const CMDID_QUERY_BALANCE= "QueryBalance"; //余额查询(页面),页面浏览器方式
	const CMDID_QUERY_BALANCE_BG= "QueryBalanceBg"; //余额查询(后台),后台数据流方式
	const CMDID_QUERY_ACCTS= "QueryAccts"; //商户子账户信息查询,后台数据流方式
	const CMDID_QUERY_TRANS_STAT= "QueryTransStat"; //交易状态查询,后台数据流方式
	const CMDID_QUERY_TENDER_PLAN= "QueryTenderPlan"; //自动投标计划状态查询,后台数据流方式
	const CMDID_RECONCILIATION= "Reconciliation"; //投标对账(放款和还款对账),后台数据流方式
	const CMDID_TRF_RECONCILIATION= "TrfReconciliation"; //商户扣款对账,后台数据流方式
	const CMDID_CASH_RECONCILIATION= "CashReconciliation"; //取现对账,后台数据流方式
	const CMDID_QUERY_ACCT_DETAILS= "QueryAcctDetails"; //账户明细查询,页面浏览器方式
	const CMDID_SAVE_RECONCILIATION= "SaveReconciliation"; //充值对账,后台数据流方式
	const CMDID_QUERY_RETURN_DZ_FEE= "QueryReturnDzFee"; //垫资手续费返还查询,后台数据流方式
	const CMDID_CORP_REGISTER_QUERY= "CorpRegisterQuery"; //担保类型企业开户状态查询接口,后台数据流方式
	const CMDID_CREDIT_ASSIGN_RECONCILIATION= "CreditAssignReconciliation"; //债权查询接口,后台数据流方式
	const CMDID_FSS_PURCHASE_RECONCILIATION= "FssPurchaseReconciliation"; //生利宝转入对账接口,后台数据流方式
	const CMDID_FSS_REDEEM_RECONCILIATION= "FssRedeemReconciliation"; //生利宝转出对账接口,后台数据流方式
	const CMDID_QUERY_FSS= "QueryFss"; //生利宝产品信息查询,后台数据流方式
	const CMDID_QUERY_FSS_ACCTS= "QueryFssAccts"; //生利宝账户信息查询,后台数据流方式
	const CMDID_QUERY_CARD_INFO= "QueryCardInfo"; //银行卡查询接口,后台数据流方式
}