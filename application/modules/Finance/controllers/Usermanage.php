<?php
/**
 * 用户管理功能controller层
 * 用户开户
 * 企业开户
 * 用户绑卡
 */
class UsermanageController extends Base_Controller_Page{

	private $userManageLogic ;
	private $huifuid;
	private $userName;
	private $phone;
    public function init(){
    	Yaf_Dispatcher::getInstance()->disableView();    	 
    	$this->setNeedLogin(false);
        parent::init();
        $this->userManageLogic = new Finance_Logic_UserManage();        
        $this->huifuid = !empty($this->objUser) ? $this->objUser->huifuid : '';
        $this->userName = !empty($this->objUser) ? $this->objUser->name : "";
        $this->phone = $phone = !empty($this->objUser) ? $this->objUser->phone : "";
    }

    /**
     * @param 
     * 用户开户Action层
     *
     */
    public function userregistAction(){
        $objUser  = $this->objUser;
        $userid   = $this->userid;
        $userName = $this->userName;
        $phone = $this->phone;
        $this->userManageLogic->userRegist($userName,'',$userid);       
    }

    /**
     * 企业开户Action层
     *
     */
    public function corpregistAction(){

    }
    
   /**
    * 用户绑卡Action层
    *
    */   
    public function userbindcardAction(){
    	//若还没有绑定汇付，此时应该提示用户去绑定汇付
        //$objUser = $this->objUser;
        $huifuid = $this->huifuid;
        $userid  = $this->userid;  
        $this->userManageLogic->userBindCard($huifuid,$userid);
    }
    
    /**
     * 用户登录Action层
     * FOR TEST
     */
    public function loginAction() {
    	$huifuid = $this->huifuid;
    	$huifuid = "6000060000696947";	
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
     * 删除银行卡接口
     * FOR TEST
     */
    public function delCardAction() {
    	$huifuid = $this->huifuid;
    	$huifuid = "6000060000696947";
    	$cardId = "4367423378320018938";
    	$this->userManageLogic->delCard($huifuid,$cardId);
    }
    
    public function testAction() {
        $logic = new Finance_Logic_Transaction();
        $transAmt = "20.00";
        $userid=1;
        $uidborrowDetail = array(
            "BorrowerUserId" => 1,
        	"BorrowerAmt"    =>  "20000.00",
        	"BorrowerRate"   => "0.12" , 
        	"ProId"          => 1,
        );
        $isFreeze='Y';
        $retUrl='';
        $logic->initiativeTender($transAmt,$userid,$uidborrowDetail,$isFreeze,$retUrl);
    }
    
    public function liluAction() {
        Finance_Api::loans('111111111111','111111111111','111111111111','111111111111',20.00);
    }
}
