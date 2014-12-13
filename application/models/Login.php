<?php
/**
 * 注册model类
 */
class LoginModel extends BaseModel {
    const USER_STATUS = 0;
    
    public function __construct (){
        parent::__construct();
    }

    /**
     * 插入用户登录数据
     * @param $arrParams 用户注册时填的信息
     * @return int status 1插入正常，0出错
     */
    public function addRecord($arrParams){
        $now = date("Y-m-d h:i:s");
        $strSql  = "INSERT INTO `login_record` (`uid`,`status`,`ip`,`phone`,`create_time`) VALUES(";
        $strSql .= $arrParams['uid'].",";
        $strSql .= $arrParams['status'].",";
        $strSql .= $arrParams['ip'].",";
        $strSql .= $arrParams['phone'].",";
        $strSql .= $now.")";
        try{
            return $this->db->execute($strSql);
        }catch(Base_Exception $ex){
            $this->logger->notice($ex->getMessage(),__METHOD__,$intUseId);
            throw new Base_Exception("Db operation error!");
        }
    }
    
    /**
     * 获取登录用户信息
     */
    public function getUserInfo($uid){
        $strSql = "SELECT  `name`,`eamil`,`phone` FROM `user_login` WHERE `uid` = '$uid' LIMIT 0, 1";
        try{
            return $this->db->fetchAll($strSql);
        }catch(Base_Exception $ex){
            $this->logger->notice($ex->getMessage(),__METHOD__,$intUseId);
            throw new Base_Exception("Db operation error!");
        }
    }
    /**
     * 用户登录
     * @param string $type :密码对就的类型：eamil、phone、name
     * @param string $strPasswd
     */
    public function login($type,$strName,$strPasswd){
        $strSql = "SELECT  `uid` FROM `user_login` WHERE $type = $strName AND `passwd` = $strPasswd LIMIT 0, 1";
        try{
            return $this->db->fetchAll($strSql);
        }catch(Base_Exception $ex){
            $this->logger->notice($ex->getMessage(),__METHOD__,$intUseId);
            throw new Base_Exception("Db operation error!");
        }
    }
}
