<?php
/**
 * 第三方登录Logic层
 * @author hejunhua
 */
class User_Logic_Third{

    //第三方登陆类型
    const TYPE_QQ     = 1;
    const TYPE_WEIBO  = 2;
    const TYPE_WEIXIN = 3;
    
    private $userid;
    //列表保存User_Object_Third的信息
    private $objs = array();

    public function __construct($userid){
        $this->userid = $userid;
        $list         = new User_List_Third()
        $filters      = array('userid' => $this->userid);
        $list->setFilter($filters);
        foreach ($list->toArray() as $value) {
            $this->objs[$value['type']] = $value;
        }
    }
    
    /**
     * 获取OpenID, 也可以用于判断是否绑定某个类型
     * @param  $authtype 'qq' or 'weibo' or 'weixin'
     * @return $openid || false
     */ 
    public function getOpenid($authtype){
        $arrInfo = $this->objs[$this->getType($authtype)];
        $openid  = isset($arrInfo['openid']) ? $arrInfo['openid'] : false;
        return $openid;
    )

    /**
     * 获取Nickname
     * @param  $authtype 'qq' or 'weibo' or 'weixin'
     * @return $nickname || false
     */
    public function getNickname($authtype){
        $arrInfo  = $this->objs[$this->getType($authtype)];
        $nickname = isset($arrInfo['nickname']) ? $arrInfo['nickname'] : false;
        return $nickname;
    }

    /**
     * @param  $authtype = 'weibo', 'qq', 'weixin'
     * @return intType
     */
    private function getType($authtype) {
        $authtype = strtolower($authtype);
        $type     = null;
        switch ($authtype) {
            case 'qq':
                $type = self::TYPE_QQ;
                break;
            case 'weibo':
                $type = self::TYPE_WEIBO;
                break;
            case 'weixin':
                $type = self::TYPE_WEIXIN;
                break;
            default:
                # code...
                break;
        }
        return $type;
    }


    //TODO:第三方登陆其他功能


    /**
     * 根据$intType类型获取auth code
     * 拼接URL的操作，发给前端放在点击授权处
     * @param int $intType,1表示qq,2表示微博
     */
    public function getAuthCode($intType){
        $this->type   = $intType;
        $arrData      =  Base_Config::getConfig('login');
        $redirect_url = $arrData['auth_code_url'];
        $arrData      = $arrData[$intType];
        $host         = $arrData['host'];
        $randnum      = md5(uniqid(rand(), TRUE));
        Yaf_Session::getInstance()->set("state",$randnum);
        $url = $arrData['authcode_url'].$arrData['appid']."&redirect_uri=".$redirect_url."&scope=get_user_info&state=".$randnum;
        if(empty($host)||empty($url))  {
            return User_RetCode::INVALID_URL;
        }
        return $host.$url;
    }
    
    /**
     * 第三账号登录，首先需要获取用户的部分资料
     * @param unknown $openid
     * @param unknown $intType
     */
    public function checkBind($openid,$intType){
        $ret = $this->modLogin->checkBing($openid,$intType);
        return $ret;
    }
       
    /**
     * 第三账号登录，首先需要获取用户的部分资料
     * @param unknown $openid
     * @param unknown $intType
     */
    public function setBind($openid, $intType,$strName,$strPasswd){
        $thirdObj = new User_Object_Third();
        $thirdObj->openid = $openid;
        $thirdObj->type   = $intType;
        $ret = $this->modLogin->setBing($openid,$intType,$status['bind']);
        return $ret;
    }

    /**
     * 第三账号登录，首先需要获取用户的部分资料
     * @param unknown $openid
     * @param unknown $intType
     */
    public function thridLogin($uid,$openid,$intType){
        $ip = Base_Util_Ip::getClientIp();
        if(User_RetCode::INVALID_USER != $uid){
           $this->modLogin->addRecord($uid,User_RetCode::SUCCESS,$ip);
           return User_RetCode::SUCCESS;
        }
        return User_RetCode::UNKNOWN_ERROR;
    }
    
  
}