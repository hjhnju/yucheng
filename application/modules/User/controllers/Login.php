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
        $logic   = new User_Logic_Login();
        $userid = $logic->checkLogin();
        if($userid){
            $this->redirect('/account/overview');
        }
        $logic = new User_Logic_Login();
        $u = isset($_REQUEST['u'])?trim($_REQUEST['u']):null;
        if(!empty($u)){
            $strRedirect = $u;
        }else{
            $strRedirect = $logic->loginRedirect($_SERVER['HTTP_REFERER']);
        }        
        Yaf_Session::getInstance()->set(User_Keys::LOGIN_REFER,$strRedirect);
        $intFails = Yaf_Session::getInstance()->get(User_Keys::getFailTimesKey());
        if($intFails >= 3) {
            $this->getView()->assign('url',$this->webroot . '/user/imagecode/getimage?type=login');
        }
    }
  
    /**
     * 第三方登录跳转中间页
     * @param string $type, qq|weibo|weixin
     * 点击qq,weibo icon
     */
    public function authAction(){
        $strType = strtolower(trim($_REQUEST['type']));

        //设置登陆类型qq|weibo|weixin
        Yaf_Session::getInstance()->set(User_Keys::getAuthTypeKey(), $strType);

        $logic = new User_Logic_Third();
        $url   = $logic->getAuthCodeUrl($strType);

        Base_Log::debug(array('authtype' => $strType));
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
        $state    = trim($_REQUEST['state']);
        //TODO:check state
        $strAuthCode  = trim($_REQUEST['code']);
        if(empty($state) || empty($strAuthCode)){   //auth code
            return $this->ajaxError(User_RetCode::GET_AUTHCODE_FAIL,
                User_RetCode::getMsg(User_RetCode::GET_AUTHCODE_FAIL));    
        }

        //获取登陆类型qq|weibo|weixin
        $strType = Yaf_Session::getInstance()->get(User_Keys::getAuthTypeKey());
        //授权登录并保存openid
        $logic   = new User_Logic_Third();
        $openid  = $logic->login($strType, $strAuthCode);
        if(empty($openid)){
            return $this->ajaxError(User_RetCode::GET_OPENID_FAIL,
                User_RetCode::getMsg(User_RetCode::GET_OPENID_FAIL));
        }
        Yaf_Session::getInstance()->set(User_Keys::getOpenidKey(), $openid);

        //是否已有绑定账号
        $userid = $logic->getBindUserid($openid, $strType);

        if(!empty($userid)){
            //已绑定的用户登录成功
            $objUser = new User_Object($userid);
            $logic   = new User_Logic_Login();
            $logic->setLogin($objUser);
            Base_Log::notice(array(
                'msg'  => 'success',
                'type' => $strType,
                'code' => $strAuthCode,
                'openid' => $openid,
            ));
            $this->redirect('/account/overview');
        }else{
            //用户未绑定账号
            //session 中已存openid
            Base_Log::notice(array(
                'msg' => 'redirect to regist bind',
                'type' => $strType,
                'code' => $strAuthCode,
                'openid' => $openid,
            ));
            $this->redirect('/user/regist');
        }
    }

    /**
     * 标准退出登录过程
     * 状态返回0表示登出成功
     */
    public function signOutAction(){
        $logic   = new User_Logic_Login();
        $ret = $logic->signOut();
        $redirectUri = '/user/login';
        $this->redirect($redirectUri);
    }   
}
