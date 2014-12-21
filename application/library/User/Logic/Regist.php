<?php
/**
 * 注册Logic层
 */
class User_Logic_Regist{
    
    public function __construct(){
    }
    
    /**
     * 
     * @param string $strName,用户名
     * @return int,0表示用户名不存在，1表示用户名存在，2表示输入的用户名不合法
     */
    public function checkName($strName){        
        if(empty($strName)||(User_Api::checkReg('name',$strName))){
            return User_RetCode::USERNAME_SYNTEX_ERROR;
        }
        $login = new User_List_Login();
        $filter = array('name'=>$strName);
        $login->setFilter($filter);
        $num = $login->getTotal();
        if(empty($num)) {
            return User_RetCode::SUCCESS;
        }
        return User_RetCode::USERNAME_EXIST;
    }
    
    /**
     *
     * @param string $strPhone,手机号
     * @return int,0表示手机存在，1表示手机不存在
     */
    public function checkPhone($strPhone){
        if(empty($strPhone)||(User_Api::checkReg('phone',$strPhone))){
            return User_RetCode::USERPHONE_SYNTEX_ERROR;
        }
        $login = new User_List_Login();
        $filter = array('phone'=>$strPhone);
        $login->setFilter($filter);
        $num = $login->getTotal();
        if(empty($num)) {
            return User_RetCode::SUCCESS;
        }
        return User_RetCode::USERPHONE_EXIST;
    }
    
    /**
     *
     * @param string $strPhone,手机号
     * @return int,0表示手机存在，1表示手机不存在
     */
    public function checkInviter($strPhone){
        if(empty($strPhone)||(User_Api::checkReg('phone',$strPhone))){
            return User_RetCode::USERPHONE_SYNTEX_ERROR;
        }
        $login = new User_List_Login();
        $filter = array('phone'=>$strPhone);
        $login->setFilter($filter);
        $user = $login->getObjects();
        if(empty($user[0]['userid'])) {
            return User_RetCode::INVALID_USER;
        }
        return intval($user[0]['userid']);
    }
    
    /**
     * 注册的同时要添加信息进`user_info`表
     * @param array $arrParam注册所需要的信息
     * @return int $uid,成功注册返回用户id，否则返回0
     */
    public function regist($arrParam){
        $regis = new User_Object_Login();
        $regis->name = $arrParam['name'];
        $regis->passwd = $arrParam['passwd'];
        $regis->phone = $arrParam['phone'];
        $ret = $regis->save();
        $uid = $regis->userid;        
        $info = new User_Object_Info();
        $info->userid = $uid;
        $info->usertype = 1;
        $ret = $info->save();
        if(!$ret){
            return User_RetCode::INVALID_USER;
        }
        return $uid;
    }
    
}