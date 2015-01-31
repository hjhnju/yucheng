<?php
/**
 * 用户对象，
 * 组合user_login, user_info, user_corpinfo
 * 可读可写
 * @author hejunhua
 * @since  2014-12-21
 */ 
class User_Object {

    //用户类型－个人用户
    const TYPE_PRIV = 1;
    //用户类型－企业用户
    const TYPE_CORP = 2;

    /**
     * 字段与属性隐射关系
     * @var array
     */
    protected $loginProps = array(
        'userid',
        'usertype',
        'status',
        'name',
        'passwd',
        'phone',
        'email',
        'huifuid',
        'lastip',
        'logintime',
        'createtime',
    );

   /**
     * 普通用户信息字段与属性隐射关系
     * @var array
     */
    protected $infoProps = array(
        'realname',
        'certificateType',
        'certificateContent',
        'headurl',    
    );

    /**
     * 企业用户信息字段与属性隐射关系
     * @var array
     */
    protected $corpInfoProps = array(
        'corpname',
        'busicode',
        'instucode',
        'taxcode',
        'area',
        'years',
    );

    //封装User_Object_Login
    protected $loginObj;

    //封装User_Object_Info
    protected $infoObj;

    //封装User_Object_Corpinfo
    protected $corpInfoObj;

    //封装User_Logic_Third, 只读
    protected $thirdLogic;


    public function __construct($userid){
        $this->loginObj = new User_Object_Login(intval($userid));
        $usertype       = $this->loginObj->usertype;
        if(self::TYPE_CORP === $usertype){
            $this->corpInfoObj = new User_Object_Corpinfo($this->userid);
        }else{
            $this->infoObj = new User_Object_Info($this->userid);
        }
    }

    /**
     * 获取属性
     * @param $name 属性名，区分大小写
     */
    public function __get($name){
        if($this->loginObj && in_array($name, $this->loginProps)){
            return $this->loginObj->$name;
        }
        if($this->infoObj && in_array($name, $this->infoProps)){
            return $this->infoObj->$name;
        }
        if($this->corpInfoObj && in_array($name, $this->corpInfoProps)){
            return $this->corpInfoObj->$name;
        }

        return null;
    }


    /**
     * 设置属性值
     * @param $name 属性名，区分大小写
     * @param $value
     */
    public function __set($name, $value){
        if($this->loginObj && in_array($name, $this->loginProps)){
            $this->loginObj->$name = $value;
            return true;
        }
        if($this->infoObj && in_array($name, $this->infoProps)){
            $this->infoObj->$name = $value;
            return true;
        }
        if($this->corpInfoObj && in_array($name, $this->corpInfoProps)){
            $this->corpInfoObj->$name = $value;
            return true;
        }

        return false;
    }


    /**
     * 保存对象到数据库中，会更新状态到对象中，使对象中的数据跟DB是完全对应的
     * @return boolean
     */
    public function save() {
        if(!$this->loginProps){
            return false;
        }
        $bolRet1 = $bolRet2 = $bolRet3 = false;
        $bolRet1 = $this->loginObj->save();
        if($this->infoObj){
            $bolRet2 = $this->infoObj->save();
        }
        if($this->corpInfoObj){
            $bolRet3 = $this->corpInfoObj->save();
        }
        return $bolRet1 && $bolRet2 && $bolRet3;
    }

    /**
     * 获取OpenID, 也可以用于判断是否绑定
     * @param  $authtype 'qq' or 'weibo' or 'weixin'
     * @return $openid || false
     */ 
    public function getOpenid($authtype){
        if(!$this->thirdLogic){
            $this->thirdLogic = new User_Logic_Third($this->userid);
        }
        return $this->thirdLogic->getOpenid($authtype);
    }

    /**
     * 第三方登录类型
     * @param  $authtype 'qq' or 'weibo' or 'weixin'
     * @return $nickname || false
     */
    public function getNickname($authtype) {
        if(!$this->thirdLogic){
            $this->thirdLogic = new User_Logic_Third($this->userid);
        }
        return $this->thirdLogic->getNickname($authtype);
    }
       
}
