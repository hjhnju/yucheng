<?php
/**
 * 用户注册相关操作
 */
class RegistController extends Base_Controller_Page{
    
    public function init(){

        $this->setNeedLogin(false);

        parent::init();
        $this->registLogic = new User_Logic_Regist();
        $this->loginLogic = new User_Logic_Login();
    }
    
    
    /**
     * 用户注册类
     */
    public function IndexAction(){
        $strType = Yaf_Session::getInstance()->get("idtype");
        if(!empty($strType)){
            $openid = Yaf_Session::getInstance()->get("openid");
            $login = new User_Object_Third($openid);
            $this->getView()->assign("type",$strType);
            $this->getView()->assign("third",$login->nickname);
        }
    }
}
