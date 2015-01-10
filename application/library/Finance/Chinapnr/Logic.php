<?php
include_once "SecureTool.php";
/**
  * 汇付接口最终实现类
  * 
  */
class Finance_Chinapnr_Logic {
	private static $self=null;
	private $merId;
	private $scureTool;
	private $platformUrl;

	const VERSION_10 = "10";
	const VERSION_20 = "20";

	/**
	 * cmd const list
	 * you should read the API doc to see details
	 * see docoment 5.2.1
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

	/**
	 * @desc depends npc sign server.
	 * @param string $merId
	 */
	private function __construct($params){
		$this->merId= $params['merId'];
		$this->platformUrl= $params['serverLocation'];
		$this->scureTool= new Finance_Chinapnr_SecureTool($params['merchantPrivateKey'],$params['chinapnrPublicKey']);

	}

	/**
	 * @desc 向汇付天下发送http请求
	 * @param $postData
	 * @return mixed
	 */
	private function request($postData=array()){
		$post_string= $this->postArrayToString($postData);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->platformUrl);
		curl_setopt($ch, CURLOPT_POST,strlen($post_string));
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//禁止直接显示获取的内容 重要
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //不验证证书下同
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //
		$result=curl_exec($ch);
		curl_close($ch);
		return $result;
	}

	/**
	 * @desc urlencode request datas
	 * @param string $req
	 * @return string
	 */
	private function postArrayToString($req=array()){
		$tmp= array();
		foreach($req as $key => $value){
			array_push($tmp,  "$key=".urlencode($value));
		}
		return implode("&", $tmp);
	}

	/**
	 * @desc 指定验签报文的主键，自动拼接验签原文
	 * @param  $params
	 * @param  $keys
	 * @return string
	 */
	private function getSignContent($params=array(), $keys=array()){
		$ret="";
		foreach ($keys as $key){
			$ret.= isset($params[$key])?(trim($params[$key])):"";
		}
		return $ret;
	}

	/**
	 * @desc 处理接口的返回值，并进行验签
	 * 		如果返回值不合法或者验签失败，则返回 null
	 * @param string $res
	 * @param $signKeys
	 * @return array or null
	 */
	private function reactResponse($res= "", $signKeys=array()){
		$res= urldecode($res);
		$ret= json_decode($res,true);
		// 指定的signKeys 拼接字符串进行验签
		if($ret){
			if($this->verify($this->getSignContent($ret, $signKeys), $ret['ChkValue']))
				return $ret;
		}
		return null;
	}

	/**
	 * @desc 获取签名
	 * @param string $signData
	 * @return string
	 */
	private function sign($signData){
		return $this->scureTool->sign($signData);
	}

	/**
	 * @desc 验证签名
	 * @param string $originStr
	 * @param string $sign
	 * @return boolean
	 */
	private function verify($originStr, $sign){
		return $this->scureTool->verify($originStr, $sign);
	}

	/**
	 * @desc 商户加签信息，构造form，引导用户跳转到汇付天下站点进行操作的统一跳转表单构造方法
	 * @param array $reqData
	 * @return no return
	 */
	private function autoRedirect($reqData= array()){
		$html= <<<HTML
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<head><body onload="document.getElementById('autoRedirectForm').submit();">
<div class="margin:10px;font-size:14px;">正在跳转...</div>
<form id="autoRedirectForm" method="POST" action="$this->platformUrl">
HTML;
		foreach($reqData as $key => $value){
			$html.='<input type="hidden" value="'.$value.'" name="'.$key.'" />';
		}
		$html.="</form>";
		$html.="</body></html>";
		print $html;
		exit;
	}

	//
	public static function getInstance(){
		if(self::$self == null){
			$params= require_once 'Conf.php';
			self::$self= new Finance_Chinapnr_Logic($params);
		}
		return  self::$self;
	}

	/**
	 * @desc open an account 用户开户
	 * @link API:5.3.1
	 *
	 * @param  $merCustId
	 * @param  $bgRetUrl
	 * @param  $retUrl
	 * @param  $usrId
	 * @param  $usrName
	 * @param  $idType
	 * @param  $idNo
	 * @param  $usrMp
	 * @param  $usrMp
	 * @param  $usrEmail
	 * @param  $merPriv
	 * @param  $charSet
	 *
	 * @return 无返回，使用autoRedirect方式重定向用户浏览器页面
	 */
	public function userRegister($merCustId, $bgRetUrl, $retUrl="", $usrId="", $usrMp="", $usrName="", $idType="", $idNo="",  $usrEmail="", $merPriv="", $charSet=""){
		$checkValue= $this->sign($this::VERSION_10.$this::CMDID_USER_REGISTER.$merCustId.$bgRetUrl.$retUrl.$usrId.$usrName.$idType.$idNo.$usrMp.$usrEmail.$merPriv);
		$reqData=array(
				"Version"	=>	$this::VERSION_10,
				"CmdId"		=>	$this::CMDID_USER_REGISTER,
				"MerCustId"	=>	$merCustId,
				"BgRetUrl"	=>	$bgRetUrl,
				"RetUrl"	=>	$retUrl,
				"UsrId"		=>	$usrId,
				"UsrName"	=>	$usrName,
				"IdType"	=>	$idType,
				"IdNo"		=>	$idNo,
				"UsrMp"		=>	$usrMp,
				"UsrEmail"	=>	$usrEmail,
				"MerPriv"	=>	$merPriv,
				"CharSet"	=>	$charSet,
				"ChkValue"	=>	$checkValue,
		);
		$this->autoRedirect($reqData);
	}

	/**
	 * @desc query customer's account balance 用户账户余额查询
	 * @link API:5.5.1
	 * @param string $merCustId
	 * @param string $usrCustId
	 */
	public function queryBalance($merCustId,$usrCustId){
		$checkValue= $this->sign($this::VERSION_10.$this::CMDID_QUERY_BALANCE.$merCustId.$usrCustId);
		$reqData= array(
				"Version"	=>	$this::VERSION_10,
				"CmdId"		=>	$this::CMDID_QUERY_BALANCE,
				"MerCustId"	=>	$merCustId,
				"UsrCustId" =>	$usrCustId,
				"ChkValue"	=>	$checkValue,
		);

		return $this->reactResponse($this->request($reqData));
	}

	/**
	 * @desc query sub-accounts' infomation 子账户信息查询
	 * @link API:5.5.2
	 * @param string $merCustId
	 * @return Ambigous <multitype:, NULL, mixed>
	 */
	public function queryAccts($merCustId){
		$checkValue= $this->sign($this::VERSION_10.$this::CMDID_QUERY_ACCTS.$merCustId);
		$reqData=array(
				"Version"	=>	$this::VERSION_10,
				"CmdId"		=>	$this::CMDID_QUERY_ACCTS,
				"MerCustId"	=>	$merCustId,
				"ChkValue"	=>	$checkValue,
		);
		return $this->reactResponse($this->request($reqData),array("CmdId","RespCode","MerCustId"));
	}
	/**
	 * @desc query returnDzFee 垫资手续费返还查询
	 * @link API:4.4.11
	 * @param string $merCustId
	 * @param string $beginDate
	 * @param string $endDate
	 * @return Ambigous <multitype:, NULL, mixed>
	 */
	public function queryReturnDzFee($merCustId,$beginDate,$endDate)
	{
		$checkValue= $this->sign($this::VERSION_10.$this::CMDID_QUERY_RETURN_DZ_FEE.$merCustId.$beginDate.$endDate);

		$reqData=array(
				"Version"	=>	$this::VERSION_10,
				"CmdId"		=>	$this::CMDID_QUERY_RETURN_DZ_FEE,
				"MerCustId"	=>	$merCustId,
				"BeginDate"	=>	$beginDate,
				"EndDate"	=>	$endDate,
				"ChkValue"	=>	$checkValue,
		);
		$response = $this->reactResponse($this->request($reqData),array("CmdId","RespCode","MerCustId","BeginDate","EndDate"));
		return $response;

	}
	/**
	 * @desc query transStat 交易状态查询
	 * @link API:4.4.4
	 * @param string $merCustId
	 * @param string $ordId
	 * @param string $ordDate
	 * @param string $queryTransType
	 * @return Ambigous <multitype:, NULL, mixed>
	 */
	public function queryTransStat($merCustId,$ordId,$ordDate,$queryTransType)
	{
		$checkValue= $this->sign($this::VERSION_10.$this::CMDID_QUERY_TRANS_STAT.$merCustId.$ordId.$ordDate.$queryTransType);

		$reqData=array(
				"Version"	=>	$this::VERSION_10,
				"CmdId"		=>	$this::CMDID_QUERY_TRANS_STAT,
				"MerCustId"	=>	$merCustId,
				"OrdId"	=>	$ordId,
				"OrdDate"	=>	$ordDate,
				"QueryTransType"	=>	$queryTransType,
				"ChkValue"	=>	$checkValue,
		);
		$response = $this->reactResponse($this->request($reqData),array("CmdId","RespCode","MerCustId","OrdId","OrdDate","QueryTransType","TransStat"));
		return $response;

	}
	/**
	 * @desc reconciliation 放还款对账
	 * @link API:4.4.6
	 * @param string $merCustId
	 * @param string $beginDate
	 * @param string $endDate
	 * @param string $pageNum
	 * @param string $pageSize
	 * @param string $queryTransType
	 * @return Ambigous <multitype:, NULL, mixed>
	 */
	public function reconciliation($merCustId,$beginDate,$endDate,$pageNum,$pageSize,$queryTransType)
	{
		$checkValue= $this->sign($this::VERSION_10.$this::CMDID_RECONCILIATION.$merCustId.$beginDate.$endDate.$pageNum.$pageSize.$queryTransType);

		$reqData=array(
				"Version"	=>	$this::VERSION_10,
				"CmdId"		=>	$this::CMDID_RECONCILIATION,
				"MerCustId"	=>	$merCustId,
				"BeginDate"	=>	$beginDate,
				"EndDate"	=>	$endDate,
				"PageNum"	=>	$pageNum,
				"PageSize"	=>	$pageSize,
				"QueryTransType"	=>	$queryTransType,
				"ChkValue"	=>	$checkValue,
		);
		$response = $this->reactResponse($this->request($reqData),array("CmdId","RespCode","MerCustId","BeginDate","EndDate","PageNum","PageSize","TotalItems","QueryTransType"));
		return $response;

	}
	/**
	 * @desc cashReconciliation 取现对账
	 * @link API:4.4.8
	 * @param string $merCustId
	 * @param string $beginDate
	 * @param string $endDate
	 * @param string $pageNum
	 * @param string $pageSize
	 * @return Ambigous <multitype:, NULL, mixed>
	 */
	public function cashReconciliation($merCustId,$beginDate,$endDate,$pageNum,$pageSize)
	{
		$checkValue= $this->sign($this::VERSION_10.$this::CMDID_CASH_RECONCILIATION.$merCustId.$beginDate.$endDate.$pageNum.$pageSize);

		$reqData=array(
				"Version"	=>	$this::VERSION_10,
				"CmdId"		=>	$this::CMDID_CASH_RECONCILIATION,
				"MerCustId"	=>	$merCustId,
				"BeginDate"	=>	$beginDate,
				"EndDate"	=>	$endDate,
				"PageNum"	=>	$pageNum,
				"PageSize"	=>	$pageSize,
				"ChkValue"	=>	$checkValue,
		);
		$response = $this->reactResponse($this->request($reqData),array("CmdId","RespCode","MerCustId","BeginDate","EndDate","PageNum","PageSize","TotalItems"));
		return $response;

	}
	/**
	 * @desc queryBalanceBg 余额查询(后台)
	 * @link API:4.4.2
	 * @param string $merCustId
	 * @param string $usrCustId
	 * @return Ambigous <multitype:, NULL, mixed>
	 */
	public function queryBalanceBg($usrCustId,$merCustId="")
	{
		$checkValue= $this->sign($this::VERSION_10.$this::CMDID_QUERY_BALANCE_BG.$merCustId.$usrCustId);

		$reqData=array(
				"Version"	=>	$this::VERSION_10,
				"CmdId"		=>	$this::CMDID_QUERY_BALANCE_BG,
				"MerCustId"	=>	$merCustId,
				"UsrCustId"	=>	$usrCustId,
				"ChkValue"	=>	$checkValue,
		);
		$response = $this->reactResponse($this->request($reqData),array("CmdId","RespCode","MerCustId","UsrCustId","AvlBal","AcctBal","FrzBal"));
		return $response;

	}
	/**
	 * @desc queryTenderPlan 自动投标计划状态查询
	 * @link API:4.4.5
	 * @param string $merCustId
	 * @param string $usrCustId
	 * @return Ambigous <multitype:, NULL, mixed>
	 */
	public function queryTenderPlan($merCustId,$usrCustId)
	{
		$checkValue= $this->sign($this::VERSION_10.$this::CMDID_QUERY_TENDER_PLAN.$merCustId.$usrCustId);

		$reqData=array(
				"Version"	=>	$this::VERSION_10,
				"CmdId"		=>	$this::CMDID_QUERY_TENDER_PLAN,
				"MerCustId"	=>	$merCustId,
				"UsrCustId"	=>	$usrCustId,
				"ChkValue"	=>	$checkValue,
		);

		$response = $this->reactResponse($this->request($reqData),array("CmdId","RespCode","MerCustId","UsrCustId","TransStat"));
		return $response;

	}
	/**
	 * @desc TrfReconciliation 商户扣款对账
	 * @link API:4.4.7
	 * @param string $merCustId
	 * @param string $beginDate
	 * @param string $endDate
	 * @param string $pageNum
	 * @param string $pageSize
	 * @return Ambigous <multitype:, NULL, mixed>
	 */
	public function trfReconciliation($merCustId,$beginDate,$endDate,$pageNum,$pageSize)
	{
		$checkValue= $this->sign($this::VERSION_10.$this::CMDID_TRF_RECONCILIATION.$merCustId.$beginDate.$endDate.$pageNum.$pageSize);

		$reqData=array(
				"Version"	=>	$this::VERSION_10,
				"CmdId"		=>	$this::CMDID_TRF_RECONCILIATION,
				"MerCustId"	=>	$merCustId,
				"BeginDate"	=>	$beginDate,
				"EndDate"	=>	$endDate,
				"PageNum"	=>	$pageNum,
				"PageSize"	=>	$pageSize,
				"ChkValue"	=>	$checkValue,
		);
		$response = $this->reactResponse($this->request($reqData),array("CmdId","RespCode","MerCustId","BeginDate","EndDate","PageNum","PageSize","TotalItems"));
		return $response;

	}
	/**
	 * @desc saveReconciliation 充值对账
	 * @link API:4.4.10
	 * @param string $merCustId
	 * @param string $beginDate
	 * @param string $endDate
	 * @param string $pageNum
	 * @param string $pageSize
	 * @return Ambigous <multitype:, NULL, mixed>
	 */
	public function saveReconciliation($merCustId,$beginDate,$endDate,$pageNum,$pageSize)
	{
		$checkValue= $this->sign($this::VERSION_10.$this::CMDID_SAVE_RECONCILIATION.$merCustId.$beginDate.$endDate.$pageNum.$pageSize);

		$reqData=array(
				"Version"	=>	$this::VERSION_10,
				"CmdId"		=>	$this::CMDID_SAVE_RECONCILIATION,
				"MerCustId"	=>	$merCustId,
				"BeginDate"	=>	$beginDate,
				"EndDate"	=>	$endDate,
				"PageNum"	=>	$pageNum,
				"PageSize"	=>	$pageSize,
				"ChkValue"	=>	$checkValue,
		);
		$response = $this->reactResponse($this->request($reqData),array("CmdId","RespCode","MerCustId","BeginDate","EndDate","PageNum","PageSize","TotalItems"));
		return $response;

	}
	/**
	 * @desc corpRegisterQuery 担保类型企业开户状态查询接口
	 * @link API:4.4.13
	 * @param string $merCustId
	 * @param string $busiCode
	 * @param string $reqExt
	 * @return Ambigous <multitype:, NULL, mixed>
	 */
	public function corpRegisterQuery($merCustId,$busiCode,$reqExt = '')
	{
		$checkValue= $this->sign($this::VERSION_10.$this::CMDID_CORP_REGISTER_QUERY.$merCustId.$busiCode.$reqExt);
		$reqData=array(
				"Version"	=>	$this::VERSION_10,
				"CmdId"		=>	$this::CMDID_CORP_REGISTER_QUERY,
				"MerCustId"	=>	$merCustId,
				"BusiCode"	=>	$busiCode,
				"ReqExt"	=>	$reqExt,
				"ChkValue"	=>	$checkValue,
		);
		$response = $this->reactResponse($this->request($reqData),array("CmdId","RespCode","MerCustId","UsrCustId","UsrId","AuditStat","BusiCode","RespExt"));
		return $response;

	}
	/**
	 * @desc creditAssignReconciliation 债权查询接口
	 * @link API:4.4.14
	 * @param string $merCustId
	 * @param string $ordId
	 * @param string $beginDate
	 * @param string $endDate
	 * @param string $sellCustId
	 * @param string $buyCustId
	 * @param string $pageNum
	 * @param string $pageSize
	 * @param string $reqExt
	 * @return Ambigous <multitype:, NULL, mixed>
	 */
	public function creditAssignReconciliation($merCustId,$ordId = '',$beginDate,$endDate,$sellCustId = '',$buyCustId = '',$pageNum,$pageSize,$reqExt = '')
	{
		$checkValue= $this->sign($this::VERSION_10.$this::CMDID_CREDIT_ASSIGN_RECONCILIATION.$merCustId.$ordId.$beginDate.$endDate.$sellCustId.$buyCustId.$pageNum.$pageSize.$reqExt);

		$reqData=array(
				"Version"	=>	$this::VERSION_10,
				"CmdId"		=>	$this::CMDID_CREDIT_ASSIGN_RECONCILIATION,
				"MerCustId"	=>	$merCustId,
				"OrdId"	=>	$ordId,
				"BeginDate"	=>	$beginDate,
				"EndDate"	=>	$endDate,
				"SellCustId"	=>	$sellCustId,
				"BuyCustId"	=>	$buyCustId,
				"PageNum"	=>	$pageNum,
				"PageSize"	=>	$pageSize,
				"ReqExt"	=>	$reqExt,
				"ChkValue"	=>	$checkValue,
		);
		$response = $this->reactResponse($this->request($reqData),array("CmdId","RespCode","MerCustId","OrdId","BeginDate","EndDate","SellCustId","BuyCustId","PageNum","PageSize","TotalItems","RespExt"));
		return $response;

	}
	/**
	 * @desc fssPurchaseReconciliation 生利宝转入对账接口
	 * @link API:4.4.15
	 * @param string $merCustId
	 * @param string $beginDate
	 * @param string $endDate
	 * @param string $pageNum
	 * @param string $pageSize
	 * @param string $reqExt
	 * @return Ambigous <multitype:, NULL, mixed>
	 */
	public function fssPurchaseReconciliation($merCustId,$beginDate,$endDate,$pageNum,$pageSize,$reqExt = '')
	{
		$checkValue= $this->sign($this::VERSION_10.$this::CMDID_FSS_PURCHASE_RECONCILIATION.$merCustId.$beginDate.$endDate.$pageNum.$pageSize.$reqExt);

		$reqData=array(
				"Version"	=>	$this::VERSION_10,
				"CmdId"		=>	$this::CMDID_FSS_PURCHASE_RECONCILIATION,
				"MerCustId"	=>	$merCustId,
				"BeginDate"	=>	$beginDate,
				"EndDate"	=>	$endDate,
				"PageNum"	=>	$pageNum,
				"PageSize"	=>	$pageSize,
				"ReqExt"	=>	$reqExt,
				"ChkValue"	=>	$checkValue,
		);
		$response = $this->reactResponse($this->request($reqData),array("CmdId","RespCode","MerCustId","BeginDate","EndDate","PageNum","PageSize","TotalItems"));
		return $response;

	}
	/**
	 * @desc fssRedeemReconciliation 生利宝转出对账接口
	 * @link API:4.4.16
	 * @param string $merCustId
	 * @param string $beginDate
	 * @param string $endDate
	 * @param string $pageNum
	 * @param string $pageSize
	 * @param string $reqExt
	 * @return Ambigous <multitype:, NULL, mixed>
	 */
	public function fssRedeemReconciliation($merCustId,$beginDate,$endDate,$pageNum,$pageSize,$reqExt = '')
	{
		$checkValue= $this->sign($this::VERSION_10.$this::CMDID_FSS_REDEEM_RECONCILIATION.$merCustId.$beginDate.$endDate.$pageNum.$pageSize.$reqExt);

		$reqData=array(
				"Version"	=>	$this::VERSION_10,
				"CmdId"		=>	$this::CMDID_FSS_REDEEM_RECONCILIATION,
				"MerCustId"	=>	$merCustId,
				"BeginDate"	=>	$beginDate,
				"EndDate"	=>	$endDate,
				"PageNum"	=>	$pageNum,
				"PageSize"	=>	$pageSize,
				"ReqExt"	=>	$reqExt,
				"ChkValue"	=>	$checkValue,
		);
		$response = $this->reactResponse($this->request($reqData),array("CmdId","RespCode","MerCustId","BeginDate","EndDate","PageNum","PageSize","TotalItems"));
		return $response;

	}
	/**
	 * @desc queryFss 生利宝产品信息查询
	 * @link API:4.4.17
	 * @param string $merCustId
	 * @param string $reqExt
	 */
	public function queryFss($merCustId,$reqExt){
		$checkValue= $this->sign($this::VERSION_10.$this::CMDID_QUERY_FSS.$merCustId.$reqExt);
		$reqData= array(
				"Version"	=>	$this::VERSION_10,
				"CmdId"		=>	$this::CMDID_QUERY_FSS,
				"MerCustId"	=>	$merCustId,
				"ReqExt" =>	$reqExt,
				"ChkValue"	=>	$checkValue,
		);

		return $this->reactResponse($this->request($reqData),array("CmdId","RespCode","MerCustId","AnnuRate","PrdRate","RespExt"));
	}
	/**
	 * @desc queryFssAccts 生利宝账户信息查询
	 * @link API:4.4.18
	 * @param string $merCustId
	 * @param string $usrCustId
	 * @param string $reqExt
	 */
	public function queryFssAccts($merCustId,$usrCustId,$reqExt){
		$checkValue= $this->sign($this::VERSION_10.$this::CMDID_QUERY_FSS_ACCTS.$merCustId.$usrCustId.$reqExt);
		$reqData= array(
				"Version"	=>	$this::VERSION_10,
				"CmdId"		=>	$this::CMDID_QUERY_FSS_ACCTS,
				"MerCustId"	=>	$merCustId,
				"UsrCustId"	=>	$usrCustId,
				"ReqExt" =>	$reqExt,
				"ChkValue"	=>	$checkValue,
		);
		return $this->reactResponse($this->request($reqData),array("CmdId","RespCode","MerCustId","UsrCustId","TotalAsset","TotalProfit","RespExt"));
	}
	/**
	 * @desc queryCardInfo 银行卡查询接口
	 * @link API:4.4.12
	 * @param string $merCustId
	 * @param string $usrCustId
	 * @param string $cardId
	 * @param string $reqExt
	 */
	public function queryCardInfo($merCustId,$usrCustId,$cardId){
		$checkValue= $this->sign($this::VERSION_10.$this::CMDID_QUERY_CARD_INFO.$merCustId.$usrCustId.$cardId);
		$reqData= array(
				"Version"	=>	$this::VERSION_10,
				"CmdId"		=>	$this::CMDID_QUERY_CARD_INFO,
				"MerCustId"	=>	$merCustId,
				"UsrCustId"	=>	$usrCustId,
				"CardId" =>	$cardId,
				"ChkValue"	=>	$checkValue,
		);
		return $this->reactResponse($this->request($reqData),array("CmdId","RespCode","MerCustId","UsrCustId","CardId"));
	}
	/**
	 * @desc userLogin 用户登录接口
	 * @link API:4.2.5
	 *
	 * @param  $merCustId
	 * @param  $usrCustId
	 *
	 * @return 无返回，使用autoRedirect方式重定向用户浏览器页面
	 */
	public function userLogin($merCustId, $usrCustId)
	{
		$checkValue= $this->sign($this::VERSION_10.$this::CMDID_USER_LOGIN.$merCustId.$usrCustId);
		$reqData=array(
				"Version"	=>	$this::VERSION_10,
				"CmdId"		=>	$this::CMDID_USER_LOGIN,
				"MerCustId"	=>	$merCustId,
				"UsrCustId"	=>	$usrCustId,
				"ChkValue"	=>	$checkValue,
		);
		$this->autoRedirect($reqData);
	}
	/**
	 * @desc acctModify 账户信息修改（页面）接口
	 * @link API:4.2.6
	 *
	 * @param  $merCustId
	 * @param  $usrCustId
	 *
	 * @return 无返回，使用autoRedirect方式重定向用户浏览器页面
	 */
	public function acctModify($merCustId, $usrCustId)
	{
		$checkValue= $this->sign($this::VERSION_10.$this::CMDID_ACCT_MODIFY.$merCustId.$usrCustId);
		$reqData=array(
				"Version"	=>	$this::VERSION_10,
				"CmdId"		=>	$this::CMDID_ACCT_MODIFY,
				"MerCustId"	=>	$merCustId,
				"UsrCustId"	=>	$usrCustId,
				"ChkValue"	=>	$checkValue,
		);
		$this->autoRedirect($reqData);
	}
	/**
	 * @desc autoTenderPlan 自动投标计划
	 * @link API:4.3.8
	 *
	 * @param  $merCustId
	 * @param  $usrCustId
	 * @param  $tenderPlanType
	 * @param  $transAmt
	 * @param  $retURL
	 * @param  $merPriv
	 *
	 * @return 无返回，使用autoRedirect方式重定向用户浏览器页面
	 */
	public function autoTenderPlan($merCustId,$usrCustId,$tenderPlanType,$transAmt,$retUrl = '',$merPriv = '')
	{
		$checkValue= $this->sign($this::VERSION_10.$this::CMDID_AUTO_TENDER_PLAN.$merCustId.$usrCustId.$tenderPlanType.$transAmt.$retUrl.$merPriv);
		$reqData=array(
				"Version"	=>	$this::VERSION_10,
				"CmdId"		=>	$this::CMDID_AUTO_TENDER_PLAN,
				"MerCustId"	=>	$merCustId,
				"UsrCustId"	=>	$usrCustId,
				"TenderPlanType"	=>	$tenderPlanType,
				"TransAmt"	=>	$transAmt,
				"RetUrl"	=>	$retUrl,
				"MerPriv"	=>	$merPriv,
				"ChkValue"	=>	$checkValue,
		);
		$this->autoRedirect($reqData);
	}
	/**
	 * @desc corpRegister 企业开户接口
	 * @link API:4.2.7
	 *
	 * @param  $merCustId
	 * @param  $usrId
	 * @param  $usrName
	 * @param  $instuCode
	 * @param  $busiCode
	 * @param  $taxCode
	 * @param  $merPriv
	 * @param  $charSet
	 * @param  $guarType
	 * @param  $bgRetUrl
	 * @param  $reqExt
	 *
	 * @return 无返回，使用autoRedirect方式重定向用户浏览器页面
	 */
	public function corpRegister($merCustId,$usrId = '',$usrName = '',$instuCode = '',$busiCode,$taxCode = '',$merPriv = '',$charSet = '',$guarType = '',$bgRetUrl,$reqExt = '')
	{
		$checkValue= $this->sign($this::VERSION_10.$this::CMDID_CORP_REGISTER.$merCustId.$usrId.$usrName.$instuCode.$busiCode.$taxCode.$merPriv.$guarType.$bgRetUrl.$reqExt);
		$reqData=array(
				"Version"	=>	$this::VERSION_10,
				"CmdId"		=>	$this::CMDID_CORP_REGISTER,
				"MerCustId"	=>	$merCustId,
				"UsrId"	=>	$usrId,
				"UsrName"	=>	$usrName,
				"InstuCode"	=>	$instuCode,
				"BusiCode"	=>	$busiCode,
				"TaxCode"	=>	$taxCode,
				"MerPriv"	=>	$merPriv,
				"CharSet"	=>	$charSet,
				"GuarType"	=>	$guarType,
				"BgRetUrl"	=>	$bgRetUrl,
				"ReqExt"	=>	$reqExt,
				"ChkValue"	=>	$checkValue,
		);
		$this->autoRedirect($reqData);
	}
	/**
	 * @desc autoTenderPlanClose 自动投标计划关闭
	 * @link API:4.3.9
	 *
	 * @param  $merCustId
	 * @param  $usrCustId
	 * @param  $retURL
	 * @param  $merPriv
	 *
	 * @return 无返回，使用autoRedirect方式重定向用户浏览器页面
	 */
	public function autoTenderPlanClose($merCustId,$usrCustId,$retUrl = '',$merPriv = '')
	{
		$checkValue= $this->sign($this::VERSION_10.$this::CMDID_AUTO_TENDER_PLAN_CLOSE.$merCustId.$usrCustId.$retUrl.$merPriv);
		$reqData=array(
				"Version"	=>	$this::VERSION_10,
				"CmdId"		=>	$this::CMDID_AUTO_TENDER_PLAN_CLOSE,
				"MerCustId"	=>	$merCustId,
				"UsrCustId"	=>	$usrCustId,
				"RetUrl"	=>	$retUrl,
				"MerPriv"	=>	$merPriv,
				"ChkValue"	=>	$checkValue,
		);
		$this->autoRedirect($reqData);
	}
	/**
	 * @desc netSave 网银充值
	 * @link API:4.3.1
	 *
	 * @param  $merCustId
	 * @param  $usrCustId
	 * @param  $ordId
	 * @param  $ordDate
	 * @param  $gateBusiId
	 * @param  $openBankId
	 * @param  $dcFlag
	 * @param  $transAmt
	 * @param  $retUrl
	 * @param  $bgRetUrl
	 * @param  $merPriv
	 *
	 * @return 无返回，使用autoRedirect方式重定向用户浏览器页面
	 */
	public function netSave($merCustId,$usrCustId,$ordId,$ordDate,$gateBusiId = '',$openBankId = '',$dcFlag = '',$transAmt,$retUrl = '',$bgRetUrl,$merPriv = '')
	{
		$checkValue= $this->sign($this::VERSION_10.$this::CMDID_NET_SAVE.$merCustId.$usrCustId.$ordId.$ordDate.$gateBusiId.$openBankId.$dcFlag.$transAmt.$retUrl.$bgRetUrl.$merPriv);
		$reqData=array(
				"Version"	=>	$this::VERSION_10,
				"CmdId"		=>	$this::CMDID_NET_SAVE,
				"MerCustId"	=>	$merCustId,
				"UsrCustId"	=>	$usrCustId,
				"OrdId"	    =>	$ordId,
				"OrdDate"	=>	$ordDate,
				"GateBusiId"	=>	$gateBusiId,
				"OpenBankId"	=>	$openBankId,
				"DcFlag"	=>	$dcFlag,
				"TransAmt"	=>	$transAmt,
				"RetUrl"	=>	$retUrl,
				"BgRetUrl"	=>	$bgRetUrl,
				"MerPriv"	=>	$merPriv,
				"ChkValue"	=>	$checkValue,
		);
		
		$this->autoRedirect($reqData);
		
	}
	/**
	 * @desc initiativeTender 主动投标
	 * @link API:4.3.5
	 *
	 * @param  $merCustId
	 * @param  $ordId
	 * @param  $ordDate
	 * @param  $transAmt
	 * @param  $usrCustId
	 * @param  $maxTenderRate
	 * @param  $borrowerDetails
	 * @param  $isFreeze
	 * @param  $freezeOrdId
	 * @param  $retUrl
	 * @param  $bgRetUrl
	 * @param  $merPriv
	 * @param  $reqExt
	 *
	 * @return 无返回，使用autoRedirect方式重定向用户浏览器页面
	 */
	public function initiativeTender($merCustId,$ordId,$ordDate,$transAmt,$usrCustId,$maxTenderRate,$borrowerDetails,$isFreeze,$freezeOrdId = '',$retUrl = '',$bgRetUrl,$merPriv,$reqExt = '')
	{

		$borrowerDetails = $this->arrayToJsonStr($borrowerDetails);
		$reqExt = $this->arrayToJsonStr($reqExt);
		$checkValue= $this->sign($this::VERSION_20.$this::CMDID_INITIATIVE_TENDER.$merCustId.$ordId.$ordDate.$transAmt.$usrCustId.$maxTenderRate.$borrowerDetails.$isFreeze.$freezeOrdId.$retUrl.$bgRetUrl.$merPriv.$reqExt);
		$reqData=array(
				"Version"	=>	$this::VERSION_20,
				"CmdId"		=>	$this::CMDID_INITIATIVE_TENDER,
				"MerCustId"	=>	$merCustId,
				"OrdId"	=>	$ordId,
				"OrdDate"	=>	$ordDate,
				"TransAmt"	=>	$transAmt,
				"UsrCustId"	=>	$usrCustId,
				"MaxTenderRate"	=>	$maxTenderRate,
				"BorrowerDetails"	=>	$borrowerDetails,
				"IsFreeze"	=>	$isFreeze,
				"FreezeOrdId"	=>	$freezeOrdId,
				"RetUrl"	=>	$retUrl,
				"BgRetUrl"	=>	$bgRetUrl,
				"MerPriv"	=>	$merPriv,
				"ReqExt"	=>	$reqExt,
				"ChkValue"	=>	$checkValue,
		);
		$this->autoRedirect($reqData);
	}
	/**
	 * @desc TenderCancle 投标撤销
	 * @link API:4.3.7
	 *
	 * @param  $merCustId
	 * @param  $ordId
	 * @param  $ordDate
	 * @param  $transAmt
	 * @param  $usrCustId
	 * @param  $isUnFreeze
	 * @param  $unFreezeOrdId
	 * @param  $freezeTrxId
	 * @param  $retUrl
	 * @param  $bgRetUrl
	 * @param  $merPriv
	 * @param  $reqExt
	 *
	 * @return 无返回，使用autoRedirect方式重定向用户浏览器页面
	 */
	public function tenderCancle($merCustId,$usrCustId,$ordId,$ordDate,$transAmt,$usrCustId,$isUnFreeze,$unFreezeOrdId = '',$freezeTrxId = '',$retUrl = '',$bgRetUrl,$merPriv='',$reqExt='')
	{
		$checkValue= $this->sign($this::VERSION_20.$this::CMDID_TENDER_CANCLE.$merCustId.$ordId.$ordDate.$transAmt.$usrCustId.$isUnFreeze.$unFreezeOrdId.$freezeTrxId.$retUrl.$bgRetUrl.$merPriv.$reqExt);
		$reqData=array(
			"Version"	=>	$this::VERSION_20,
			"CmdId"		=>	$this::CMDID_TENDER_CANCLE,
			"MerCustId"	=>	$merCustId,
			"OrdId"	=>	$ordId,
			"OrdDate"	=>	$ordDate,
			"TransAmt"	=>	$transAmt,
			"UsrCustId"	=>	$usrCustId,
			"IsUnFreeze"	=>	$isUnFreeze,
			"UnFreezeOrdId"	=>	$unFreezeOrdId,
			"reezeTrxId"	=>	$freezeTrxId,
			"RetUrl"	=>	$retUrl,
			"BgRetUrl"	=>	$bgRetUrl,
			"MerPriv"	=>	$merPriv,
			"ReqExt"	=>	$reqExt,
			"ChkValue"	=>	$checkValue,
		);

		$this->autoRedirect($reqData);
	}
	/**
	 * @desc userBindCard 用户绑卡接口
	 * @link API:4.2.3
	 * @param string $merCustId
	 * @param string $usrCustId
	 * @param string $bgRetUrl
	 * @param string $merPriv
	 * @return Ambigous <multitype:, NULL, mixed>
	 */
	public function userBindCard($merCustId,$usrCustId,$bgRetUrl,$merPriv = '')
	{
		$checkValue= $this->sign($this::VERSION_10.$this::CMDID_USER_BIND_CARD.$merCustId.$usrCustId.$bgRetUrl.$merPriv);
		$reqData=array(
			"Version"	=>	$this::VERSION_10,
			"CmdId"		=>	$this::CMDID_USER_BIND_CARD,
			"MerCustId"	=>	$merCustId,
			"UsrCustId"	=>	$usrCustId,
			"BgRetUrl"	=>	$bgRetUrl,
			"MerPriv"	=>	$merPriv,
			"ChkValue"	=>	$checkValue,
		);

		$this->autoRedirect($reqData);

	}
	/**
	 * @desc cash 取现（页面）
	 * @link API:4.3.13
	 * @param string $merCustId
	 * @param string $ordId
	 * @param string $usrCustId
	 * @param string $transAmt
	 * @param string $servFee
	 * @param string $servFeeAcctId
	 * @param string $openAcctId
	 * @param string $retUrl
	 * @param string $bgRetUrl
	 * @param string $remark
	 * @param string $charSet
	 * @param string $merPriv
	 * @param string $reqExt
	 * @return Ambigous <multitype:, NULL, mixed>
	 */
	public function cash($merCustId,$ordId,$usrCustId,$transAmt,$servFee = '',$servFeeAcctId = '',$openAcctId = '',$retUrl = '',$bgRetUrl="",$remark = '',$charSet = '',$merPriv = '',$reqExt = '')
	{
		$checkValue= $this->sign($this::VERSION_20.$this::CMDID_CASH.$merCustId.$ordId.$usrCustId.$transAmt.$servFee.$servFeeAcctId.$openAcctId.$retUrl.$bgRetUrl.$remark.$merPriv.$reqExt);
		$reqData=array(
				"Version"	=>	$this::VERSION_20,
				"CmdId"		=>	$this::CMDID_CASH,
				"MerCustId"	=>	$merCustId,
				"OrdId"	=>	$ordId,
				"UsrCustId"	=>	$usrCustId,
				"TransAmt"	=>	$transAmt,
				"ServFee"	=>	$servFee,
				"ServFeeAcctId"	=>	$servFeeAcctId,
				"OpenAcctId"	=>	$openAcctId,
				"RetUrl"	=>	$retUrl,
				"BgRetUrl"	=>	$bgRetUrl,
				"Remark"	=>	$remark,
				"CharSet"	=>	$charSet,
				"MerPriv"	=>	$merPriv,
				"ReqExt"	=>	$reqExt,
				"ChkValue"	=>	$checkValue,
		);
// 		/var_dump($reqData);die;
		$this->autoRedirect($reqData);
	}
	/**
	 * @desc usrAcctPay 用户账户支付
	 * @link API:4.3.15
	 * @param string $ordId
	 * @param string $usrCustId
	 * @param string $merCustId
	 * @param string $transAmt
	 * @param string $inAcctId
	 * @param string $inAcctType
	 * @param string $retUrl
	 * @param string $bgRetUrl
	 * @param string $merPriv
	 * @return Ambigous <multitype:, NULL, mixed>
	 */
	public function usrAcctPay($ordId,$usrCustId,$merCustId,$transAmt,$inAcctId,$inAcctType,$retUrl = '',$bgRetUrl,$merPriv = '')
	{
		$checkValue= $this->sign($this::VERSION_10.$this::CMDID_USR_ACCT_PAY.$ordId.$usrCustId.$merCustId.$transAmt.$inAcctId.$inAcctType.$retUrl.$bgRetUrl.$merPriv);
		$reqData=array(
				"Version"	=>	$this::VERSION_10,
				"CmdId"		=>	$this::CMDID_USR_ACCT_PAY,
				"OrdId"	=>	$ordId,
				"UsrCustId"	=>	$usrCustId,
				"MerCustId"	=>	$merCustId,
				"TransAmt"	=>	$transAmt,
				"InAcctId"	=>	$inAcctId,
				"InAcctType"	=>	$inAcctType,
				"RetUrl"	=>	$retUrl,
				"BgRetUrl"	=>	$bgRetUrl,
				"MerPriv"	=>	$merPriv,
				"ChkValue"	=>	$checkValue,
		);

		$this->autoRedirect($reqData);
	}
	/**
	 * @desc usrTransfer 前台用户间转账接口
	 * @link API:4.3.17
	 * @param string $merCustId
	 * @param string $ordId
	 * @param string $retUrl
	 * @param string $bgRetUrl
	 * @param string $usrCustId
	 * @param string $inUsrCustId
	 * @param string $transAmt
	 * @param string $merPriv
	 * @param string $reqExt
	 * @return Ambigous <multitype:, NULL, mixed>
	 */
	public function usrTransfer($merCustId,$ordId,$retUrl,$bgRetUrl,$usrCustId,$inUsrCustId,$transAmt,$merPriv = '',$reqExt)
	{
		$checkValue= $this->sign($this::VERSION_10.$this::CMDID_USR_TRANSFER.$ordId.$usrCustId.$merCustId.$transAmt.$inUsrCustId.$retUrl.$bgRetUrl.$merPriv.$reqExt);
		$reqData=array(
				"Version"	=>	$this::VERSION_10,
				"CmdId"		=>	$this::CMDID_USR_TRANSFER,
				"MerCustId"	=>	$merCustId,
				"OrdId"	=>	$ordId,
				"RetUrl"	=>	$retUrl,
				"BgRetUrl"	=>	$bgRetUrl,
				"UsrCustId"	=>	$usrCustId,
				"InUsrCustId"	=>	$inUsrCustId,
				"TransAmt"	=>	$transAmt,
				"MerPriv"	=>	$merPriv,
				"ReqExt"	=>	$reqExt,
				"ChkValue"	=>	$checkValue,
		);

		$this->autoRedirect($reqData);
	}
	/**
	 * @desc creditAssign 债权转让接口
	 * @link API:4.3.18
	 * @param string $merCustId
	 * @param string $sellCustId
	 * @param string $creditAmt
	 * @param string $creditDealAmt
	 * @param string $bidDetails
	 * @param string $fee
	 * @param array $divDetails
	 * @param string $buyCustId
	 * @param string $ordId
	 * @param string $ordDate
	 * @param string $retUrl
	 * @param string $bgRetUrl
	 * @param string $merPriv
	 * @param string $reqExt
	 * @return Ambigous <multitype:, NULL, mixed>
	 */
	public function creditAssign($merCustId,$sellCustId,$creditAmt,$creditDealAmt,$bidDetails,$fee,$divDetails = '',$buyCustId,$ordId,$ordDate,$retUrl = '',$bgRetUrl,$merPriv = '',$reqExt = '')
	{
		$bidDetails = $this->arrayToJsonStr($bidDetails);
		$divDetails = $this->arrayToJsonStr($divDetails);

		$checkValue= $this->sign($this::VERSION_10.$this::CMDID_CREDIT_ASSIGN.$merCustId.$sellCustId.$creditAmt.$creditDealAmt.$bidDetails.$fee.$divDetails.$buyCustId.$ordId.$ordDate.$retUrl.$bgRetUrl.$merPriv.$reqExt);
		$reqData=array(
				"Version"	=>	$this::VERSION_10,
				"CmdId"		=>	$this::CMDID_CREDIT_ASSIGN,
				"MerCustId"	=>	$merCustId,
				"SellCustId"	=>	$sellCustId,
				"CreditAmt"	=>	$creditAmt,
				"CreditDealAmt"	=>	$creditDealAmt,
				"BidDetails"	=>	$bidDetails,
				"Fee"	=>	$fee,
				"DivDetails"	=>	$divDetails,
				"BuyCustId"	=>	$buyCustId,
				"OrdId"	=>	$ordId,
				"OrdDate"	=>	$ordDate,
				"RetUrl"	=>	$retUrl,
				"BgRetUrl"	=>	$bgRetUrl,
				"MerPriv"	=>	$merPriv,
				"ReqExt"	=>	$reqExt,
				"ChkValue"	=>	$checkValue,
		);

		$this->autoRedirect($reqData);
	}
	/**
	 * @desc fssTrans 生利宝交易接口
	 * @link API:4.3.20
	 * @param string $merCustId
	 * @param string $usrCustId
	 * @param string $ordId
	 * @param string $ordDate
	 * @param string $retUrl
	 * @param string $bgRetUrl
	 * @param string $merPriv
	 * @param string $reqExt
	 * @return Ambigous <multitype:, NULL, mixed>
	 */
	public function fssTrans($merCustId,$usrCustId,$ordId,$ordDate,$retUrl = '',$bgRetUrl,$merPriv = '',$reqExt = '')
	{
		$checkValue= $this->sign($this::VERSION_10.$this::CMDID_FSS_TRANS.$merCustId.$usrCustId.$ordId.$ordDate.$retUrl.$bgRetUrl.$merPriv.$reqExt);
		$reqData=array(
				"Version"	=>	$this::VERSION_10,
				"CmdId"		=>	$this::CMDID_FSS_TRANS,
				"MerCustId"	=>	$merCustId,
				"UsrCustId"	=>	$usrCustId,
				"OrdId"	=>	$ordId,
				"OrdDate"	=>	$ordDate,
				"RetUrl"	=>	$retUrl,
				"BgRetUrl"	=>	$bgRetUrl,
				"MerPriv"	=>	$merPriv,
				"ReqExt"	=>	$reqExt,
				"ChkValue"	=>	$checkValue,
		);

		$this->autoRedirect($reqData);
	}
	/**
	 * @desc queryAcctDetails 账户明细查询(页面)接口
	 * @link API:4.4.9
	 * @param string $merCustId
	 * @param string $usrCustId
	 */
	public function queryAcctDetails($merCustId,$usrCustId){
		$checkValue= $this->sign($this::VERSION_10.$this::CMDID_QUERY_ACCT_DETAILS.$merCustId.$usrCustId);
		$reqData= array(
				"Version"	=>	$this::VERSION_10,
				"CmdId"		=>	$this::CMDID_QUERY_ACCT_DETAILS,
				"MerCustId"	=>	$merCustId,
				"UsrCustId" =>	$usrCustId,
				"ChkValue"	=>	$checkValue,
		);

		$this->autoRedirect($reqData);
	}
	/**
	 * @desc bgBindCard 用户后台绑卡接口
	 * @link API:4.2.4
	 * @param string $merCustId
	 * @param string $usrCustId
	 * @param string $openAcctId
	 * @param string $openBankId
	 * @param string $openProvId
	 * @param string $openAreaId
	 * @param string $openBranchName
	 * @param string $isDefault
	 * @param string $merPriv
	 * @param string $charSet
	 * @return Ambigous <multitype:, NULL, mixed>
	 */
	public function bgBindCard($merCustId,$usrCustId,$openAcctId,$openBankId,$openProvId,$openAreaId,$openBranchName = '',$isDefault,$merPriv = '',$charSet = '')
	{
		$checkValue= $this->sign($this::VERSION_10.$this::CMDID_BG_BIND_CARD.$merCustId.$usrCustId.$openAcctId.$openBankId.$openProvId.$openAreaId.$openBranchName.$isDefault.$merPriv);
		$reqData=array(
				"Version"	=>	$this::VERSION_10,
				"CmdId"		=>	$this::CMDID_BG_BIND_CARD,
				"MerCustId"	=>	$merCustId,
				"UsrCustId"	=>	$usrCustId,
				"OpenAcctId"	=>	$openAcctId,
				"OpenBankId"	=>	$openBankId,
				"OpenProvId"	=>	$openProvId,
				"OpenAreaId"	=>	$openAreaId,
				"OpenBranchName"	=>	$openBranchName,
				"IsDefault"	=>	$isDefault,
				"MerPriv"	=>	$merPriv,
				"CharSet"	=>	$charSet,
				"ChkValue"	=>	$checkValue,
		);

		$response = $this->reactResponse($this->request($reqData),array("CmdId","RespCode","MerCustId","UsrCustId","OpenAcctId","OpenBankId","MerPriv"));
		return $response;
	}
	/**
	 * @desc delCard 用户后台绑卡接口
	 * @link API:4.2.4
	 * @param string $merCustId
	 * @param string $usrCustId
	 * @param string $cardId
	 * @return Ambigous <multitype:, NULL, mixed>
	 */
	public function delCard($merCustId,$usrCustId,$cardId)
	{
		$checkValue= $this->sign($this::VERSION_10.$this::CMDID_DEL_CARD.$merCustId.$usrCustId.$cardId);
		$reqData=array(
				"Version"	=>	$this::VERSION_10,
				"CmdId"		=>	$this::CMDID_DEL_CARD,
				"MerCustId"	=>	$merCustId,
				"UsrCustId"	=>	$usrCustId,
				"CardId"	=>	$cardId,
				"ChkValue"	=>	$checkValue,
		);

		$response = $this->reactResponse($this->request($reqData),array("CmdId","RespCode","MerCustId","UsrCustId","CardId"));
		return $response;
	}
	/**
	 * @desc posWhSave 商户无卡代扣充值
	 * @link API:4.3.2
	 * @param string $merCustId
	 * @param string $usrCustId
	 * @param string $openAcctId
	 * @param string $transAmt
	 * @param string $ordId
	 * @param string $ordDate
	 * @param string $checkDate
	 * @param string $retUrl
	 * @param string $bgRetUrl
	 * @param string $merPriv
	 * @return Ambigous <multitype:, NULL, mixed>
	 */
	public function posWhSave($merCustId,$usrCustId,$openAcctId,$transAmt,$ordId,$ordDate,$checkDate = '',$retUrl = '',$bgRetUrl,$merPriv = '')
	{
		$checkValue= $this->sign($this::VERSION_10.$this::CMDID_POS_WH_SAVE.$merCustId.$usrCustId.$openAcctId.$transAmt.$ordId.$ordDate.$checkDate.$retUrl.$bgRetUrl.$merPriv);
		$reqData=array(
				"Version"	=>	$this::VERSION_10,
				"CmdId"		=>	$this::CMDID_POS_WH_SAVE,
				"MerCustId"	=>	$merCustId,
				"UsrCustId"	=>	$usrCustId,
				"OpenAcctId"	=>	$openAcctId,
				"TransAmt"	=>	$transAmt,
				"OrdId"	=>	$ordId,
				"OrdDate"	=>	$ordDate,
				"CheckDate"	=>	$checkDate,
				"RetUrl"	=>	$retUrl,
				"BgRetUrl"	=>	$bgRetUrl,
				"MerPriv"	=>	$merPriv,
				"ChkValue"	=>	$checkValue,
		);
		$response = $this->reactResponse($this->request($reqData),array("CmdId","RespCode","MerCustId","UsrCustId","OpenAcctId","TransAmt","OrdId","OrdDate","CheckDate","TrxId","RetUrl","BgRetUrl","MerPriv"));
		return $response;
	}
	/**
	 * @desc usrFreezeBg 资金（货款）冻结
	 * @link API:4.3.3
	 * @param string $merCustId
	 * @param string $usrCustId
	 * @param string $subAcctType
	 * @param string $subAcctId
	 * @param string $ordId
	 * @param string $ordDate
	 * @param string $checkDate
	 * @param string $retUrl
	 * @param string $bgRetUrl
	 * @param string $merPriv
	 * @return Ambigous <multitype:, NULL, mixed>
	 */
	public function usrFreezeBg($merCustId,$usrCustId,$subAcctType = '',$subAcctId = '',$ordId,$ordDate,$transAmt,$retUrl = '',$bgRetUrl,$merPriv = '')
	{
		$checkValue= $this->sign($this::VERSION_10.$this::CMDID_USR_FREEZE_BG.$merCustId.$usrCustId.$subAcctType.$subAcctId.$ordId.$ordDate.$transAmt.$retUrl.$bgRetUrl.$merPriv);
		$reqData=array(
				"Version"	=>	$this::VERSION_10,
				"CmdId"		=>	$this::CMDID_USR_FREEZE_BG,
				"MerCustId"	=>	$merCustId,
				"UsrCustId"	=>	$usrCustId,
				"SubAcctType"	=>	$subAcctType,
				"SubAcctId"	=>	$subAcctId,
				"OrdId"	=>	$ordId,
				"OrdDate"	=>	$ordDate,
				"TransAmt"	=>	$transAmt,
				"RetUrl"	=>	$retUrl,
				"BgRetUrl"	=>	$bgRetUrl,
				"MerPriv"	=>	$merPriv,
				"ChkValue"	=>	$checkValue,
		);
		$response = $this->reactResponse($this->request($reqData),array("CmdId","RespCode","MerCustId","UsrCustId","SubAcctType","SubAcctId","OrdId","OrdDate","TransAmt","RetUrl","BgRetUrl","TrxId","MerPriv"));
		return $response;
	}
	/**
	 * @desc usrUnFreeze 资金（货款）冻结
	 * @link API:4.3.3
	 * @param string $merCustId
	 * @param string $ordId
	 * @param string $ordDate
	 * @param string $trxId
	 * @param string $subAcctType
	 * @param string $subAcctId
	 * @param string $checkDate
	 * @param string $retUrl
	 * @param string $bgRetUrl
	 * @param string $merPriv
	 * @return Ambigous <multitype:, NULL, mixed>
	 */
	public function usrUnFreeze($merCustId,$ordId,$ordDate,$trxId,$retUrl = '',$bgRetUrl,$merPriv = '')
	{
		$checkValue= $this->sign($this::VERSION_10.$this::CMDID_USR_UN_FREEZE.$merCustId.$ordId.$ordDate.$trxId.$retUrl.$bgRetUrl.$merPriv);
		$reqData=array(
				"Version"	=>	$this::VERSION_10,
				"CmdId"		=>	$this::CMDID_USR_UN_FREEZE,
				"MerCustId"	=>	$merCustId,
				"OrdId"	=>	$ordId,
				"OrdDate"	=>	$ordDate,
				"TrxId"	=>	$trxId,
				"RetUrl"	=>	$retUrl,
				"BgRetUrl"	=>	$bgRetUrl,
				"MerPriv"	=>	$merPriv,
				"ChkValue"	=>	$checkValue,
		);
		$response = $this->reactResponse($this->request($reqData),array("CmdId","RespCode","MerCustId","OrdId","OrdDate","TrxId","RetUrl","BgRetUrl","MerPriv"));
		return $response;
	}
	/**
	 * @desc autoTender 自动投标
	 * @link API:4.3.6
	 * @param string $merCustId
	 * @param string $ordId
	 * @param string $ordDate
	 * @param string $transAmt
	 * @param string $usrCustId
	 * @param string $maxTenderRate
	 * @param array $borrowerDetails
	 * @param string $checkDate
	 * @param string $retUrl
	 * @param string $bgRetUrl
	 * @param string $merPriv
	 * @return Ambigous <multitype:, NULL, mixed>
	 */
	public function autoTender($merCustId,$ordId,$ordDate,$transAmt,$usrCustId,$maxTenderRate,$borrowerDetails,$retUrl = '',$bgRetUrl,$merPriv = '')
	{
		$borrowerDetails = $this->arrayToJsonStr($borrowerDetails);
		$checkValue= $this->sign($this::VERSION_10.$this::CMDID_AUTO_TENDER.$merCustId.$ordId.$ordDate.$transAmt.$usrCustId.$maxTenderRate.$borrowerDetails.$retUrl.$bgRetUrl.$merPriv);
		$reqData=array(
				"Version"	=>	$this::VERSION_10,
				"CmdId"		=>	$this::CMDID_AUTO_TENDER,
				"MerCustId"	=>	$merCustId,
				"OrdId"	=>	$ordId,
				"OrdDate"	=>	$ordDate,
				"TransAmt"	=>	$transAmt,
				"UsrCustId"	=>	$usrCustId,
				"MaxTenderRate"	=>	$maxTenderRate,
				"BorrowerDetails"	=>	$borrowerDetails,
				"RetUrl"	=>	$retUrl,
				"BgRetUrl"	=>	$bgRetUrl,
				"MerPriv"	=>	$merPriv,
				"ChkValue"	=>	$checkValue,
		);
		$response = $this->reactResponse($this->request($reqData),array("CmdId","RespCode","MerCustId","OrdId","OrdDate","TransAmt","UsrCustId","TrxId","RetUrl","BgRetUrl","MerPriv"));
		return $response;
	}
	/**
	 * @desc loans 自动扣款（放款）
	 * @link API:4.3.10
	 * @param string $merCustId
	 * @param string $ordId
	 * @param string $ordDate
	 * @param string $outCustId
	 * @param string $transAmt
	 * @param string $fee
	 * @param string $subOrdId
	 * @param string $subOrdDate
	 * @param string $inCustId
	 * @param array $divDetails
	 * @param string $feeObjFlag
	 * @param string $isDefault
	 * @param string $isUnFreeze
	 * @param string $unFreezeOrdId
	 * @param string $freezeTrxId
	 * @param string $bgRetUrl
	 * @param string $merPriv
	 * @return Ambigous <multitype:, NULL, mixed>
	 */
	public function loans($merCustId,$ordId,$ordDate,$outCustId,$transAmt,$fee,$subOrdId,$subOrdDate,$inCustId,$divDetails = '',$feeObjFlag,$isDefault,$isUnFreeze,$unFreezeOrdId = '',$freezeTrxId = '',$bgRetUrl,$merPriv = '',$reqExt = '')
	{
		$divDetails = $this->arrayToJsonStr($divDetails);
		$reqExt = $this->arrayToJsonStr($reqExt);
		$checkValue= $this->sign($this::VERSION_20.$this::CMDID_LOANS.$merCustId.$ordId.$ordDate.$outCustId.$transAmt.$fee.$subOrdId.$subOrdDate.$inCustId.$divDetails.$feeObjFlag.$isDefault.$isUnFreeze.$unFreezeOrdId.$freezeTrxId.$bgRetUrl.$merPriv.$reqExt);
		$reqData=array(
				"Version"	=>	$this::VERSION_20,
				"CmdId"		=>	$this::CMDID_LOANS,
				"MerCustId"	=>	$merCustId,
				"OrdId"	=>	$ordId,
				"OrdDate"	=>	$ordDate,
				"OutCustId"	=>	$outCustId,
				"TransAmt"	=>	$transAmt,
				"Fee"	=>	$fee,
				"SubOrdId"	=>	$subOrdId,
				"SubOrdDate"	=>	$subOrdDate,
				"InCustId"	=>	$inCustId,
				"DivDetails"	=>	$divDetails,
				"FeeObjFlag"	=>	$feeObjFlag,
				"IsDefault"	=>	$isDefault,
				"IsUnFreeze"	=>	$isUnFreeze,
				"UnFreezeOrdId"	=>	$unFreezeOrdId,
				"FreezeTrxId"	=>	$freezeTrxId,
				"BgRetUrl"	=>	$bgRetUrl,
				"MerPriv"	=>	$merPriv,
				"ReqExt"	=>	$reqExt,
				"ChkValue"	=>	$checkValue,
		);

		$response = $this->reactResponse($this->request($reqData),array("CmdId","RespCode","MerCustId","OrdId","OrdDate","OutCustId","OutAcctId","TransAmt","Fee","InCustId","InAcctId","SubOrdId","SubOrdDate","FeeObjFlag","IsDefault","IsUnFreeze","UnFreezeOrdId","FreezeTrxId","BgRetUrl","MerPriv","RespExt"));
		return $response;
	}
	/**
	 * @desc repayment 自动扣款（还款）
	 * @link API:4.3.11
	 * @param string $merCustId
	 * @param string $ordId
	 * @param string $ordDate
	 * @param string $outCustId
	 * @param string $subOrdId
	 * @param string $subOrdDate
	 * @param string $outAcctId
	 * @param string $transAmt
	 * @param string $fee
	 * @param string $inCustId
	 * @param string $inAcctId
	 * @param array $divDetails
	 * @param string $feeObjFlag
	 * @param string $bgRetUrl
	 * @param string $merPriv
	 * @return Ambigous <multitype:, NULL, mixed>
	 */
	public function repayment($merCustId,$ordId,$ordDate,$outCustId,$subOrdId,$subOrdDate,$outAcctId = '',$transAmt,$fee,$inCustId,$inAcctId = '',$divDetails = '',$feeObjFlag,$bgRetUrl,$merPriv = '',$reqExt = '')
	{
		$divDetails = $this->arrayToJsonStr($divDetails);
		$checkValue= $this->sign($this::VERSION_20.$this::CMDID_REPAYMENT.$merCustId.$ordId.$ordDate.$outCustId.$subOrdId.$subOrdDate.$outAcctId.$transAmt.$fee.$inCustId.$inAcctId.$divDetails.$feeObjFlag.$bgRetUrl.$merPriv.$reqExt);
		$reqData=array(
				"Version"	=>	$this::VERSION_20,
				"CmdId"		=>	$this::CMDID_REPAYMENT,
				"MerCustId"	=>	$merCustId,
				"OrdId"	=>	$ordId,
				"OrdDate"	=>	$ordDate,
				"OutCustId"	=>	$outCustId,
				"SubOrdId"	=>	$subOrdId,
				"SubOrdDate"	=>	$subOrdDate,
				"OutAcctId"	=>	$outAcctId,
				"TransAmt"	=>	$transAmt,
				"Fee"	=>	$fee,
				"InCustId"	=>	$inCustId,
				"InAcctId"	=>	$inAcctId,
				"DivDetails"	=>	$divDetails,
				"FeeObjFlag"	=>	$feeObjFlag,
				"BgRetUrl"	=>	$bgRetUrl,
				"MerPriv"	=>	$merPriv,
				"ReqExt"	=>	$reqExt,
				"ChkValue"	=>	$checkValue,
		);

		$response = $this->reactResponse($this->request($reqData),array("CmdId","RespCode","MerCustId","OrdId","OrdDate","OutCustId","SubOrdId","SubOrdDate","OutAcctId","TransAmt","Fee","InCustId","InAcctId","FeeObjFlag","BgRetUrl","MerPriv","RespExt"));
		return $response;
	}
	/**
	 * @desc transfer 自动扣款转账（商户用）
	 * @link API:4.3.12
	 * @param string $ordId
	 * @param string $outCustId
	 * @param string $outAcctId
	 * @param string $transAmt
	 * @param string $inCustId
	 * @param string $inAcctId
	 * @param string $retUrl
	 * @param string $bgRetUrl
	 * @param string $merPriv
	 * @return Ambigous <multitype:, NULL, mixed>
	 */
	public function transfer($ordId,$outCustId,$outAcctId,$transAmt,$inCustId,$inAcctId = '',$retUrl = '',$bgRetUrl,$merPriv = '')
	{
		$checkValue= $this->sign($this::VERSION_10.$this::CMDID_TRANSFER.$ordId.$outCustId.$outAcctId.$transAmt.$inCustId.$inAcctId.$retUrl.$bgRetUrl.$merPriv);
		$reqData=array(
				"Version"	=>	$this::VERSION_10,
				"CmdId"		=>	$this::CMDID_TRANSFER,
				"OrdId"	=>	$ordId,
				"OutCustId"	=>	$outCustId,
				"OutAcctId"	=>	$outAcctId,
				"TransAmt"	=>	$transAmt,
				"InCustId"	=>	$inCustId,
				"InAcctId"	=>	$inAcctId,
				"RetUrl"	=>	$retUrl,
				"BgRetUrl"	=>	$bgRetUrl,
				"MerPriv"	=>	$merPriv,
				"ChkValue"	=>	$checkValue,
		);

		$response = $this->reactResponse($this->request($reqData),array("CmdId","RespCode","OrdId","OutCustId","OutAcctId","TransAmt","InCustId","InAcctId","RetUrl","BgRetUrl","MerPriv"));
		return $response;
	}
	/**
	 * @desc cashAudit 取现复核接口
	 * @link API:4.3.14
	 * @param string $merCustId
	 * @param string $ordId
	 * @param string $usrCustId
	 * @param string $transAmt
	 * @param string $auditFlag
	 * @param string $retUrl
	 * @param string $bgRetUrl
	 * @param string $merPriv
	 * @return Ambigous <multitype:, NULL, mixed>
	 */
	public function cashAudit($merCustId,$ordId,$usrCustId,$transAmt,$auditFlag,$retUrl = '',$bgRetUrl,$merPriv = '')
	{
		$checkValue= $this->sign($this::VERSION_10.$this::CMDID_CASH_AUDIT.$merCustId.$ordId.$usrCustId.$transAmt.$auditFlag.$retUrl.$bgRetUrl.$merPriv);
		$reqData=array(
				"Version"	=>	$this::VERSION_10,
				"CmdId"		=>	$this::CMDID_CASH_AUDIT,
				"MerCustId"	=>	$merCustId,
				"OrdId"	=>	$ordId,
				"UsrCustId"	=>	$usrCustId,
				"TransAmt"	=>	$transAmt,
				"AuditFlag"	=>	$auditFlag,
				"RetUrl"	=>	$retUrl,
				"BgRetUrl"	=>	$bgRetUrl,
				"MerPriv"	=>	$merPriv,
				"ChkValue"	=>	$checkValue,
		);

		$response = $this->reactResponse($this->request($reqData),array("CmdId","RespCode","MerCustId","OrdId","UsrCustId","TransAmt","OpenAcctId","OpenBankId","AuditFlag","RetUrl","BgRetUrl","MerPriv"));
		return $response;
	}
	/**
	 * @desc merCash 商户代取现接口
	 * @link API:4.3.16
	 * @param string $merCustId
	 * @param string $ordId
	 * @param string $usrCustId
	 * @param string $transAmt
	 * @param string $servFee
	 * @param string $servFeeAcctId
	 * @param string $retUrl
	 * @param string $bgRetUrl
	 * @param string $remark
	 * @param string $charSet
	 * @param array $merPriv
	 * @return Ambigous <multitype:, NULL, mixed>
	 */
	public function merCash($merCustId,$ordId,$usrCustId,$transAmt,$servFee = '',$servFeeAcctId = '',$retUrl = '',$bgRetUrl,$remark = '',$charSet = '',$merPriv = '',$reqExt = '')
	{
		$reqExt = $this->arrayToJsonStr($reqExt);
		$checkValue= $this->sign($this::VERSION_20.$this::CMDID_MER_CASH.$merCustId.$ordId.$usrCustId.$transAmt.$servFee.$servFeeAcctId.$retUrl.$bgRetUrl.$remark.$merPriv.$reqExt);
		$reqData=array(
				"Version"	=>	$this::VERSION_20,
				"CmdId"		=>	$this::CMDID_MER_CASH,
				"MerCustId"	=>	$merCustId,
				"OrdId"	=>	$ordId,
				"UsrCustId"	=>	$usrCustId,
				"TransAmt"	=>	$transAmt,
				"ServFee"	=>	$servFee,
				"ServFeeAcctId"	=>	$servFeeAcctId,
				"RetUrl"	=>	$retUrl,
				"BgRetUrl"	=>	$bgRetUrl,
				"Remark"	=>	$remark,
				"CharSet"	=>	$charSet,
				"MerPriv"	=>	$merPriv,
				"ReqExt"	=>	$reqExt,
				"ChkValue"	=>	$checkValue,
		);

		$response = $this->reactResponse($this->request($reqData),array("CmdId","RespCode","MerCustId","OrdId","UsrCustId","TransAmt","OpenAcctId","OpenBankId","FeeAmt","FeeCustId","FeeAcctId","ServFee","ServFeeAcctId","RetUrl","BgRetUrl","MerPriv","RespExt"));
		return $response;
	}
	/**
	 * @desc autoCreditAssign 自动债权转让接口
	 * @link API:4.3.19
	 * @param string $merCustId
	 * @param string $sellCustId
	 * @param string $creditAmt
	 * @param string $creditDealAmt
	 * @param string $bidDetails
	 * @param string $fee
	 * @param array $divDetails
	 * @param string $buyCustId
	 * @param string $ordId
	 * @param string $ordDate
	 * @param string $retUrl
	 * @param string $bgRetUrl
	 * @param string $remark
	 * @param string $charSet
	 * @param string $merPriv
	 * @param string $reqExt
	 * @return Ambigous <multitype:, NULL, mixed>
	 */
	public function autoCreditAssign($merCustId,$sellCustId,$creditAmt,$creditDealAmt,$bidDetails,$fee,$divDetails = '',$buyCustId,$ordId,$ordDate,$retUrl = '',$bgRetUrl,$merPriv = '',$reqExt = '')
	{
		$bidDetails = $this->arrayToJsonStr($bidDetails);
		$divDetails = $this->arrayToJsonStr($divDetails);
		$checkValue= $this->sign($this::VERSION_10.$this::CMDID_AUTO_CREDIT_ASSIGN.$merCustId.$sellCustId.$creditAmt.$creditDealAmt.$bidDetails.$fee.$divDetails.$buyCustId.$ordId.$ordDate.$retUrl.$bgRetUrl.$merPriv.$reqExt);
		$reqData=array(
				"Version"	=>	$this::VERSION_10,
				"CmdId"		=>	$this::CMDID_AUTO_CREDIT_ASSIGN,
				"MerCustId"	=>	$merCustId,
				"SellCustId"	=>	$sellCustId,
				"CreditAmt"	=>	$creditAmt,
				"CreditDealAmt"	=>	$creditDealAmt,
				"BidDetails"	=>	$bidDetails,
				"Fee"	=>	$fee,
				"DivDetails"	=>	$divDetails,
				"BuyCustId"	=>	$buyCustId,
				"OrdId"	=>	$ordId,
				"OrdDate"	=>	$ordDate,
				"RetUrl"	=>	$retUrl,
				"BgRetUrl"	=>	$bgRetUrl,
				"MerPriv"	=>	$merPriv,
				"ReqExt"	=>	$reqExt,
				"ChkValue"	=>	$checkValue,
		);

		$response = $this->reactResponse($this->request($reqData),array("CmdId","RespCode","MerCustId","SellCustId","CreditAmt","CreditDealAmt","Fee","BuyCustId","OrdId","OrdDate","RetUrl","BgRetUrl","MerPriv","RespExt"));
		return $response;
	}
	/**
	 * @desc array转为json字符串,主要防止字符串在json_encode时首尾增加冒号
	 * @param array $array
	 * @return string
	 */
	private function arrayToJsonStr($array = '')
	{
		if (is_array($array))
		{
			return json_encode($array);
		}
		else
		{
			return $array;
		}
	}
}
?>