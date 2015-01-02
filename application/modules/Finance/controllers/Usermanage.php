<?php
/**
 * 用户管理功能controller层
 * 用户开户
 * 企业开户
 * 用户绑卡
 */
class UsermanageController extends Base_Controller_Api{

    public function init(){
    	$this->setNeedLogin(false);
        parent::init();
        $this->userManageLogic = new Finance_Logic_UserManage();
    
    }

    /**
     * 用户开户Action层
     *
     */
    public function userregistAction(){
    	$webroot = Base_Config::getConfig('web')->root;
        $objUser = $this->objUser;
        //$userid = !empty($objUser) ? $objUser->userid : 0;
        //$phone = $objUser->phone;
        //$userid = 1;
        //$phone = 15901538467;
        Finance_Api::userRegister("", "", "", "", "", "");//调用财务模块Api实现用户开户功能       
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
        $huifuid =!empty($objUser) ? $objUser->huifuid : 0;      
        Finance_Api::userBindCard($huifuid);
    }

}
