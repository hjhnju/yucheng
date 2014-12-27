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
    public function IndexAction(){
        $openid = Yaf_Session::getInstance()->get(User_Keys::getOpenidKey());
        if(!empty($openid)){
            $strType      = Yaf_Session::getInstance()->get(User_Keys::getAuthTypeKey());
            $accessToken  = Base_Redis::getInstance()->get(User_Keys::getAccessTokenKey($openid));
            
            if(!empty($accessToken)){
                $logic     = new User_Logic_Third();
                $thirdUser = $logic->getUserThirdInfo($accessToken, $openid);

                $this->getView()->assign("type", $strType);
                $this->getView()->assign("third", $thirdUser->nickname);
            }
        }
    }
}
