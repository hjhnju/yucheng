<?php
/**
 * 用户管理功能controller层
 * 用户开户
 * 企业开户
 * 用户绑卡
 */
class UsermanageController extends Base_Controller_Page{

    public function init(){
    	Yaf_Dispatcher::getInstance()->disableView();    	 
    	$this->setNeedLogin(false);
        parent::init();
    }

    /**
     * @param 
     * 用户开户Action层
     *
     */
    public function userregistAction(){
        $objUser = $this->objUser;
        $userid = $this->userid;
        $userName = !empty($objUser) ? $objUser->name : "";
        $phone = !empty($objUser) ? $objUser->phone : "";
        //Finance_Api::userRegist($userName,$phone,$userid);//调用财务模块Api实现用户开户功能       
        //TODO: remove
        //FOR TEST
        Finance_Api::userRegist('lyx661228','');//调用财务模块Api实现用户开户功能       
        
    }

    /**
     * 企业开户Action层
     *
     */
    public function corpregistAction(){
        //需要从huwei模块中取出调用API所需要的参数
        //  
        //  
        //  

    }
    
   /**
    * 用户绑卡Action层
    *
    */   
    public function userbindcardAction(){
    	//若还没有绑定汇付，此时应该提示用户去绑定汇付
        //$objUser = $this->objUser;
        $huifuid =!empty($this->objUser) ? $this->objUser->huifuid : '';
        $userid = $this->userid;      
        Finance_Api::userBindCard($huifuid,$userid);
    }
    
    /**
     * 用户登录Action层
     * FOR TEST
     */
    public function loginAction() {
    	$huifuid =!empty($this->objUser) ? $this->objUser->huifuid : '';
    	Finance_Api::userLogin($huifuid);
    }
    
    /**
     * 账户信息修改Action层
     * FOR TEST
     * 
     */
    public function acctmodifyAction() {
    	$huifuid =!empty($this->objUser) ? $this->objUser->huifuid : '';
    	Finance_Api::acctModify($huifuid);
    }
    
    /**
     * 删除银行卡Action层
     * FOR TEST
     */
    public function delbankcardAction() {
    	
    }
    
    /**
     * 银行卡查询Action层
     * FOR TEST
     */
    public function querybankinfoAction() {
    	$bankinfo = Finance_Api::queryCardInfo("6000060000696947");
    }
    
    /**
     * 商户子账户查询Action层
     * FOR TEST
     */
    public function queryAcctsAction() {
    	$merAcct = Finance_Api::queryAccts();
    }
    
    /**
     * 删除银行卡接口
     * FOR TEST
     */
    public function delCardAction() {
    	$huifuid = "6000060000696947";
    	$cardId = "4367423378320018938";
    	$delret = Finance_Api::delCard($huifuid,$cardId);
    }
}