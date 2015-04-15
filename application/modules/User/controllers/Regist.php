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
        $isMobile = Base_Util_Mobile::isMobile();
        if($isMobile){
            return $this->redirect('/m/regist');
        }

        //若有第三方登陆，则显示绑定提示
        $openid   = Yaf_Session::getInstance()->get(User_Keys::getOpenidKey());
        if($openid > 0){
            $authtype = Yaf_Session::getInstance()->get(User_Keys::getAuthTypeKey());
            $openid   = Yaf_Session::getInstance()->get(User_Keys::getOpenidKey());
            $logic    = new User_Logic_Third();
            $nickname = $logic->getUserNickname($openid, $authtype);
    
            $this->getView()->assign("type", strtoupper($authtype));
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
