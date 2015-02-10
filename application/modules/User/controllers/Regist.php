<?php
/**
 * 用户注册相关操作
 */
class RegistController extends Base_Controller_Page{
    
    public function init(){
        $this->setNeedLogin(false);
        parent::init();
    }
      
    /**
     * 用户注册类
     * 若有第三方登陆，则显示绑定提示
     */
    public function indexAction(){
        //若有第三方登陆，则显示绑定提示
        $referer  = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : false;
        if(preg_match('#/user/login/third#', $referer) === 1){
            $authtype = Yaf_Session::getInstance()->get(User_Keys::getAuthTypeKey());
            $openid   = Yaf_Session::getInstance()->get(User_Keys::getOpenidKey());
            $logic    = new User_Logic_Third();
            $nickname = $logic->getUserNickname($openid, $authtype);
    
            $this->getView()->assign("type", $authtype);
            $this->getView()->assign("third", $nickname);
    
            Base_Log::notice(array(
                'msg'      => 'to regist with openid',
                'openid'   => $openid,
                'type'     => $authtype,
                'nickname' => $nickname,
            ));
        }else{
            Base_Log::notice(array(
                'msg'    => 'to regist without openid',
            ));
        }
    }
}
