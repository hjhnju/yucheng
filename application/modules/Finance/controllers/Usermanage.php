<?php
/**
 * 用户管理功能controller层
 * 用户开户
 * 企业开户
 * 用户绑卡
 * @author lilu
 */
class UsermanageController extends Base_Controller_Page {

	private $userManageLogic;
	private $huifuid;
	private $userName;
	private $phone;
    public function init(){
    	Yaf_Dispatcher::getInstance()->disableView();
    	//for test
    	//TODO remove    	 
    	$this->setNeedLogin(false);
        parent::init();
        $this->userManageLogic = new Finance_Logic_UserManage();        
        $this->huifuid = !empty($this->objUser) ? $this->objUser->huifuid : '';
        $this->userName = !empty($this->objUser) ? $this->objUser->name : '';
        $this->phone = $phone = !empty($this->objUser) ? $this->objUser->phone : '';
    }

    /**
     * 用户开户Action层
     */
    public function userregistAction(){
        $objUser = $this->objUser;
        $userid = $this->userid;
        $userName = $this->userName;        
        $phone = $this->phone;
        Base_Log::notice(array(
        	'userid'   => $userid,
        	'userName' => $userName,
        	'phone'    => $phone,
        ));
        $this->userManageLogic->userRegist($userName,$userid);             
    }

    /**
     * 企业开户Action层
     * for test
     */
    public function corpregistAction(){
    	$userid = $_REQUEST[''];
    	$busiCode = $_REQUEST[''];
    	$userName = $_REQUEST[''];
        Base_Log::notice(array(
        	'userid'   => $userid,
        	'busiCode' => $busiCode,
        	'userName' => $userName,
        )); 
        $this->userManageLogic->corpRegist($userid,$userName,$busiCode);
    }
    
   /**
    * 用户绑卡Action层
    *
    */   
    public function userbindcardAction(){
        $huifuid = $this->huifuid;
        $userid = $this->userid;  
        Base_Log::notice(array(
            'userid'  => $userid,
        	'huifuid' => $huifuid,      	
        ));
        $this->userManageLogic->userBindCard($huifuid,$userid);
    }
    
    /**
     * 用户登录Action层
     * FOR TEST
     */
    public function loginAction() {
    	$huifuid = $this->huifuid;
    	$this->userManageLogic->userLogin($huifuid);
    }
    
    /**
     * 账户信息修改Action层
     * FOR TEST
     * 
     */
    public function acctmodifyAction() {
    	$huifuid = $this->huifuid;
    	$this->userManageLogic->acctModify($huifuid);
    }
    
    /**
     * 删除银行卡接口
     * FOR TEST
     */
    public function delCardAction() {
    	$huifuid = $this->huifuid;
    	$cardId = "4367423378320018938";
    	$this->userManageLogic->delCard($huifuid,$cardId);
    }
    
    /**
     * 银行卡查询Action层
     * FOR TEST
     */
    public function querybankinfoAction() {
    	$huifuid = $this->huifuid;
    	$queryLogic = new Finance_Logic_Query();
    	//$bankinfo = $queryLogic->queryCardInfo($huifuid);
    	$bankinfo = $queryLogic->queryCardInfo("6000060000696947");
    }
    
    /**
     * 商户子账户查询Action层
     * FOR TEST
     */
    public function queryAcctsAction() {
    	$queryLogic = new Finance_Logic_Query();
    	$merAcct = $queryLogic->queryAccts();
    }
    
    /**
     * 交易状态查询
     * FOR TEST
     */
    public function queryTransStatAction() {
    	
    	$queryLogic = new Finance_Logic_Query();
    	$transStat = $queryLogic->queryTransStat('LOANS');
    	var_dump($transStat);
    }
    
    /**
     * FOR TEST
     */ 
    public function testAction() {
    	$loanId = 3;
    	$loan = Loan_Api::getLoanInfo($loanId);
        $subOrdId = '2015012113505488194';
        $inUserId = $loan['user_id'];
        $outUserId = 37;
        $transAmt = '200.00';
        Finance_Api::loans($loanId,$subOrdId,$inUserId,$outUserId,$transAmt);
    }
    
    /**
     * FOR TEST
     */
    public function liluAction() {
        $logic = Finance_Chinapnr_Logic::getInstance();
        $ret  = $logic->queryUsrInfo('6000060000677575','350823198601102016');
        var_dump($ret);
        return;
    }
    
    /**
     * FOR TEST
     */
    public function test1Action() {
    	$outUserId = 1;
    	$inUserId = 37;
    	$subOrdId = '2015012113525121505';
    	$transAmt = '200.00';
    	$loanId = 3;
    	Finance_Api::repayment($outUserId,$inUserId,$subOrdId,$transAmt,$loanId);
    }
    
    /**
     * FOR TEST
     */
    public function test2Action() {
    	
    	$logic = new Finance_Logic_Transaction();
    	$outUserId = '6000060000677575';
    	$outAcctId = 'MDT000001';
    	$transAmt = '200.05';
    	$inUserId = $this->userid;
    	$logic->transfer($outUserId,$outAcctId,$transAmt,$inUserId);
    	
    }
    
    /**
     * FOR TEST
     */
    public function test3Action() {
    	$logic = new Finance_Logic_Transaction();

        $transAmt = '10000.00';
        $userid = '15';
        $orderId = '2015012916141410941';
        $orderDate = '20150129';
        $freezeTrxId = '201501290000814906';
        $retUrl = '';
    	$logic->tenderCancel($transAmt,$userid,$orderId,$orderDate,$freezeTrxId,$retUrl);
    }
    
    /**
     * FOR TEST
     */
    public function test4Action() {
    	Finance_Api::queryCardInfo($this->huifuid);    	 
    }
    
    /**
     * FOR TEST
     */
    public function test5Action() {
    	$logic = new Finance_Logic_Transaction();
    	$userid = $this->userid;
    	$transAmt = '200.00';
    	$logic->merCash($userid,$transAmt);
    }
    
    /**
     * FOR TEST
     */
    public function test6Action() {
    	$logic = new Finance_Logic_Base();
    	$userid = $this->userid;
    	 
    	$ret = $logic->balance($userid);
    	var_dump($ret);
    	return ;
    }
    
    /**
     * FOR TEST
     * 测试递归解码
     */
    public function test7Action() {
        $arr = array(
    		'one' => '%E6%9D%8E%E7%92%90',
    		'two' => array(
    		    0 => '%E5%88%98%E8%89%B3%E9%9C%9E',
    			1 => array(
    			    0 => '%E5%88%98%E8%89%B3%E9%9C%9E',
    				1 => array('%E5%88%98%E8%89%B3%E9%9C%9E'),
    		    ),
    	    ),
    	);
    	$logic = new Finance_Logic_Base();
    	$arr1 = $logic->arrUrlDec($arr);
    	var_dump($arr);
    	echo "<br/>";
    	var_dump($arr1); 
    	return;   	 
    }
    
}
