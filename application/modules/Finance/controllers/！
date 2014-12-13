<?php
/**
 * 开户(用户/企业)操作类
 *
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
        $version = $_REQUEST['version'];//版本号
        $merCusId = $_REQUEST['merCusId'];//商户客户号
        //需要从huwei模块中取出调用API所需要的参数
        //
        //
        //
        $userRegisterParam = array();
        $this->userManageLogic->userRegister($userRegisterParam);//调用Logic层的方法
    }
    /**
     * 企业开户Action层
     *
     */
    public function corpRegisterAction(){
        $version = $_REQUEST['version'];//版本号
        $merCusId = $_REQUEST['merCusId'];//商户客户号
        //需要从huwei模块中取出调用API所需要的参数
        //  
        //  
        //  
        $corpRegisterParam = array();
        $this->userManageLogic->corpRegister($corpRegisterParam);//调用Logic层的方法

    }
   /**
    * 用户绑卡Action层
    *
    */   
    public function userBindCardAction(){
        $version = $_REQUEST['version'];//版本号
        $merCusId = $_REQUEST['merCusId'];//商户客户号
   
        //需要从huwei模块中取出调用API所需要的参数
        $userBindCardParam = array();
        $this->userManageLogic->userBindCard($userBindCardParam);      
    
    }

}
