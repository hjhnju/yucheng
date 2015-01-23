<?php
/**
 * 用户对象
 * @author hejunhua
 * @since  2014-12-21
 */ 
class User_Object {

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
     * 字段与属性隐射关系
     * @var array
     */
    protected $infoProps = array(
        'realname',
        'certificatetype',
        'certificatecontent',
        'headurl',    
    );

    //封装User_Object_Login
    protected $loginObj;

    //封装User_Object_Info
    protected $infoObj;

    //封装User_Logic_Third
    protected $thirdLogic;


    public function __construct($userid){
        $this->userid   = $userid;
        $this->loginObj = new User_Object_Login($userid);
    }

    public function __get($name){
        $name = strtolower($name);
        if(in_array($name, $this->loginProps)){
            if(!$this->loginObj){
                $this->loginObj = new User_Object_Login($this->userid);
            }
            return $this->loginObj->$name;
        }
        if(in_array($name, $this->infoProps)){
            if(!$this->infoObj){
                $this->infoObj = new User_Object_Info($this->userid);
            }
            return $this->infoObj->$name;
        }

        return null;
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
