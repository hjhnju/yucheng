<?php
/**
 * 用户登录相关操作
 */
class LoginController extends Base_Controller_Page{
    
    public function init(){
        //未登录不跳转
        $this->setNeedLogin(false);

        parent::init();
    }
    
    /**
     * 标准登录过程
     * 状态返回0表示登录成功
     */    
    public function indexAction(){
    }
  
    /**
     * 第三方登录跳转中间页
     * @param   $strType qq|weibo|weixin
     * 点击QQ\Weibo icon
     */
    public function authAction(){
        $strType = strtolower(trim($_REQUEST['type']));
        Yaf_Session::getInstance()->set(User_Keys::getAuthTypeKey(), $strType);
        $logic   = new User_Logic_Third();
        $url     = $logic->getAuthCodeUrl($strType);
        $this->redirect($url);
    }

    /**
     * /user/login/third
     * 作为redirect_uri，第三方回调
     * 
     * 获取open id，分为如下几步骤:
     * 1.拿到auth code,当用户点击授权后，将返回auth code
     * 2.拿到auth code后，首先检查access token是否存在，如果不存在执行3，存在执行4
     * 3.通过auth code获取access token
     * 4.通过access token拿到用户的openid
     * 
     * access_token缓存的用途是：使用api获取用户信息时需要access_token + openid
     * 所以access_token是缓存在userid或openid维度即可
     * @param string $code, authcode
     * @param string $state, rand num
     */
    public function thirdAction(){
        if(!isset($_REQUEST['code'])){   //auth code
            return $this->ajaxError(User_RetCode::GET_AUTHCODE_FAIL,
                User_RetCode::getMsg(User_RetCode::GET_AUTHCODE_FAIL));    
        }

        $strType      = Yaf_Session::getInstance()->get(User_Keys::getAuthTypeKey());
        $state        = trim($_REQUEST['state']);
        //TODO:check state
        $strAuthCode  = trim($_REQUEST['code']);

        $logic        = new User_Logic_Third();
        $objUser      = $logic->login($strAuthCode);

        if(!empty($objUser)){
            //用户登录成功并已经绑定账号
            $logic->setLogin($objUser);
            $this->redirect('/account/overview');
        }else{
            //用户未绑定账号
            //session 中已存openid
            $this->redirect('/user/regist');
        }       
    }
}
