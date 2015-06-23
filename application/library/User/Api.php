<?php
/**
 * 对外的API接口
 */
class User_Api{
    
    const LAST_TIME = 5;     //验证码过期时间,5分钟
    
    /** 
     * User_Api::checkLogin()
     * 判断登陆状态，获取用户Object
     * @return object User_Object | null
     * User/Object.php封装了User_Object_Login, User_Object_Info实例
     */
    public static function checkLogin(){
        $logic  = new User_Logic_Login();
        $userid = $logic->checkLogin();
        if(false === $userid){
            return null;
        }
        $objUser  = self::getUserObject($userid);
        Base_Log::notice(array(
            'userid' => $userid,
        ));
        return $objUser;
    }

    /** 
     * User_Api::getUserObject($userid)
     * 获取用户Object
     * @return object User_Object | null
     * User/Object.php封装了User_Object_Login, User_Object_Info, User_Object_Third实例
     */
    public static function getUserObject($userid){
        $userid  = intval($userid);
        $objUser = null;
        if($userid > 0){
            $objUser = new User_Object($userid);
        }
        Base_Log::notice(array(
            'userid' => $userid,
        ));
        return $objUser;
    }

    public static function setBorrower($userName){
        $objUser = new User_Object_Login(array('name'=>$userName));
        $ret = false;
        if($objUser->isLoaded()){
            $objUser->isborrower = 1;
            $ret = $objUser->save();
        }
        return $ret;
    }

    /**
     * 获取借款用户列表
     * @param   $usertype 用户类型
     * @param   $list 用户列表
     */
    public static function getBorrowers($page = 1, $pagesize = 10){
        $logic = new User_Logic_Query();
        $list  = $logic->queryBorrowers($page, $pagesize);
        Base_Log::notice(array(
            'page'     => $list['page'],
            'pagesize' => $list['pagesize'],
        ));
        return $list;
    }

    /**
     * 获取企业用户列表
     * @param   $usertype 用户类型
     * @param   $list 用户列表
     */
    public static function getCorpUsers($page = 1, $pagesize = 10){
        $logic = new User_Logic_Query();
        $list  = $logic->queryCorpUsers($page, $pagesize);
        Base_Log::notice(array(
            'page'     => $list['page'],
            'pagesize' => $list['pagesize'],
        ));
        return $list['list'];
    }
    
    /**
     * 获取私人用户列表
     * @param int $page
     * @param int $pagesize
     * @return array
     */
    public static function getPrivUsers($page = 1, $pagesize = 10, $user){
        $logic = new User_Logic_Query();
        $list  = $logic->queryPrivUsers($page, $pagesize, $user);
        Base_Log::notice(array(
        'page'     => $list['page'],
        'pagesize' => $list['pagesize'],
        ));
        return $list;
    }
    
    /**
     * 设置用户真实姓名
     * @param int $uid
     * @param string $strRealName
     * @return boolean
     */
    public static function setRealName($uid,$strRealName){
        $objInfo = new User_Object_Info();
        $objInfo->fetch(array('userid'=>$uid));
        $objInfo->realname = $strRealName;
        $ret               = $objInfo->save();
        return $ret;
    }
    
    /**
     * 设置用户邮箱
     * @param int $uid
     * @param string $strEmail
     * @return boolean
     */
    public static function setEmail($userid,$strEmail){
        $objLogin        = new User_Object_Login($userid);
        $objLogin->email = $strEmail;
        $ret             = $objLogin->save();
        return $ret;
    }
    
    /**
     * 设置用户手机
     * @param int $uid
     * @param string $strPhone
     * @return boolean
     */
    public static function setPhone($userid,$strPhone){
        $objLogin        = new User_Object_Login($userid);
        $objLogin->phone = $strPhone;
        $ret             = $objLogin->save();
        return $ret;
    }
    
    /**
     * 设置用户证件
     * @param int $uid
     * @param string $strType,$strContent
     * @return boolean
     */
    public static function setCertificate($userid,$strContent,$strType='00'){
        $objInfo = new User_Object_Info($userid);
        $objInfo->certificateType      = $strType;
        $objInfo->certificateContent   = $strContent;
        $ret                           = $objInfo->save();
        return $ret;
    }
    
    /**
     * 设置用户密码
     * @param int $uid
     * @param string $strPasswd
     * @return int
     */
    public static function setPasswd($uid,$strPasswdOld,$strPasswdNew){
        $objLogin = new User_Object_Login();
        $objLogin->fetch(array('userid'=>$uid,'passwd'=>Base_Util_Secure::encrypt($strPasswdOld)));
        if(empty($objLogin->userid)){        	
            return User_RetCode::ORIGIN_PASSWD_WRONG;
        }
        $objLogin->passwd = Base_Util_Secure::encrypt($strPasswdNew);
        $ret = $objLogin->save();
        return $ret;
    }
 
    /**
     * 设置用户的汇付id
     * @param int $uid
     * @param string $strHuifuid
     * @return boolean
     */
    public static function setHuifuId($userid, $strHuifuid){
        $objUser = new User_Object($userid);
        $objUser->huifuid = $strHuifuid;
        $ret = $objUser->save();
        return $ret;
    }
    
    /**
     * 获取短信验证码信息,需要参数手机号及类型
     */
    public static function sendSmsCode($strPhone, $strType){
        //暂时需要让所有的验证码全部通过，因为现在不需要手机验证
        return true;

        if('dev' === ini_get('yaf.environ')){
            return true;
        }
        $srandNum = rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
        $arrArgs  = array($srandNum, self::LAST_TIME);
        $tplid    = Base_Config::getConfig('sms.tplid.vcode', CONF_PATH . '/sms.ini');
        $bResult  = Base_Sms::getInstance()->send($strPhone, $tplid[1], $arrArgs);
        if(!empty($bResult)){
            Base_Redis::getInstance()->setex(User_Keys::getSmsCodeKey($strPhone, $strType),
                60*(self::LAST_TIME), $srandNum);
            return true;
        }
        return false;
    }
    
    /**
     * 验证用户输入的短信验证码是否正确
     */
    public static function checkSmscode($strPhone, $strVeriCode, $strType){
        if('dev' === ini_get('yaf.environ')){
            return true;
        }
        $storeCode = Base_Redis::getInstance()->get(User_Keys::getSmsCodeKey($strPhone, $strType));
        if($strVeriCode === $storeCode){
            return true;
        }
        return false;
    }
    
    /**
     * 验证图片验证码
     * @param $strImageCode:图片验证码
     * @param $strType:类型
     */
    public static function checkImageCode($strImageCode, $strType){
        $bolRet= User_Logic_ImageCode::checkCode($strType, $strImageCode);
        Base_Log::notice(array(
            'bolRet' => $bolRet,
            'code'=>$strImageCode, 
            'type' => $strType
        ));
        return $bolRet;
    }
    
    /**
     * 返回用户绑定第三方账号状态,array()表示未绑定
     * @param  $userid
     * @return array('type'=>'qq','nickname'=>'xxx');
     */
    public static function checkBind($userid){
        $third = new User_Object_Third();
        $ret = $third->fetch(array('userid'=>intval($userid)));
        if($ret){           
            $logic = new User_Logic_Third();
            $strType = $logic->getStrAuthType($third->authtype);
            return array('type'=>$strType,'nickname'=>$third->nickname);
        }
        return array();
    }
    
    /**
     * 删除用户的第三方绑定
     * @param  $userid
     * @param string $strType
     * @return boolean
     */
    public static function delBind($userid,$strType){
        $third = new User_Object_Third();
        $logic = new User_Logic_Third();
        $intType = $logic->getAuthType($strType);
        $third->fetch(array('userid'=>intval($userid),'authtype'=>$intType));
        $ret = $third->erase();
        return $ret;
    }
    
    /**
     * 后台添加用户
     * @param string $strUserType 'priv' || 'corp'
     * @param string $strUserName
     * @param string $strPasswd
     * @param string $strPhone
     * @param string $strInviter
     */
    public static function regist($strUserType, $strUserName, $strPasswd, $strPhone, $strInviter=''){
        $logic  = new User_Logic_Regist();
        $objRet = $logic->regist($strUserType, $strUserName, $strPasswd, $strPhone, $strInviter);
        Base_Log::notice(array('status'=>$objRet->status));
        return $objRet->format();
    }

    public static function getInviteridByUserid($userid){
        $invite = new User_Object_Invite(array('invitee'=>$userid));
        if($invite->isLoaded()){
            return $invite->userid;
        }
        return null;
    }

    /**
     * 获取邀请用户id列表
     * @param  int $userid   邀请人id
     * @param  [type] $page     [description]
     * @param  [type] $pagesize [description]
     * @return array  
     */
    public static function getInvitees($userid, $page, $pagesize){
        $list = new User_List_Invite();
        $list->setFields(array('invitee'));
        $list->setFilter(array('userid'=>$userid));
        $list->setOrder('invitee desc');
        $list->setPage($page);
        $list->setPagesize($pagesize);
        $list = $list->toArray();

        return $list;
    }

    public static function getInviteeCnt($userid){
        $list = new User_List_Invite();
        $list->setFields(array('invitee'));
        $list->setFilter(array('userid'=>$userid));
        $list->setPage(1);
        $list->setPagesize(1);
        $list = $list->toArray();
        return $list['total'];
    }

    /**
     * 批量获取用户信息
     * @param  array $arrUid 
     * @return array
     */
    public static function getInfos($arrUid){
        if(empty($arrUid)){
            return false;
        }
        $list = new User_List_Login();
        $list->setFields(array('userid', 'name', 'phone', 'email', 'huifuid'));
        $list->setFilterString('userid IN ('. implode(',', $arrUid) . ')');
        $list->setPagesize(PHP_INT_MAX);
        $list = $list->toArray();        
        $arrRet = array_fill_keys($arrUid, null);
        foreach ($list['list'] as $row) {
            $row['displayname'] = !empty($row['name']) ? Base_Util_String::starUsername($row['name']) : 
                Base_Util_String::starPhone($row['phone']);
            $arrRet[$row['userid']] = $row;
        }
        return $arrRet;
    }

    public static function saveCorpInfo($userid, $arrInfo){
        $user = new User_Object($userid);
        if($user->userid <= 0){
            return false;
        }
        foreach ($arrInfo as $field => $value) {
            $user->$field = $value;
        }
        return $user->save();
    }
}
