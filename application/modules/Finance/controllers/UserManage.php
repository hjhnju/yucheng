<?php
/**
 * 用户管理功能controller层
 * 用户开户
 * 企业开户
 * 用户绑卡
 */
class UserManageController extends Base_Controller_Api{

    public function init(){
        parent::init();
        $this->userManageLogic = new Finance_Logic_UserManage();
    
    }

    /**
     * 用户开户Action层
     *
     */
    public function userRegisterAction(){
        //需要从huwei模块中取出调用API所需要的参数
        //
        //
        //
    }

    /**
     * 企业开户Action层
     *
     */
    public function corpRegisterAction(){
        //需要从huwei模块中取出调用API所需要的参数
        //  
        //  
        //  

    }
    
   /**
    * 用户绑卡Action层
    *
    */   
    public function userBindCardAction(){
        //需要从huwei模块中取出调用API所需要的参数
    
    }

}
