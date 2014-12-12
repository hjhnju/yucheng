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
     * 用户注册，插入数据
     * @param $arrParams 用户注册时填的信息
     * @return int status 1插入正常，0出错
     */
    public function addUser($arrParams){
        $now = date("Y-m-d h:i:s");
        $strSql  = "INSERT INTO `user_login` (`status`,`name`,`passwd`,`phone`,`create_time`) VALUES(";
        $strSql .= self::USER_STATUS.",";
        $strSql .= $arrParams['name'].",";
        $strSql .= $arrParams['passwd'].",";
        $strSql .= $arrParams['phone'].",";
        $strSql .= $now.")";
        try{
            return $this->db->fetchAll($strSql);
        }catch(Base_Exception $ex){
            $this->logger->notice($ex->getMessage(),__METHOD__,$intUseId);
            throw new Base_Exception("Db operation error!");
        }
    }
    
    /**
     * 检查用户名是否存在
     */
    public function login($strPasswd){
        $strSql = "SELECT  `name`,`eamil`,`phone` FROM `user_login` WHERE `passwd` = '$strPasswd' LIMIT 0, 1";
        try{
            return $this->db->fetchAll($strSql);
        }catch(Base_Exception $ex){
            $this->logger->notice($ex->getMessage(),__METHOD__,$intUseId);
            throw new Base_Exception("Db operation error!");
        }
    }
    /**
     * 检查手机号是否存在
     */
    public function checkPhone($intPhone){
        $strSql = "SELECT  `uid` FROM `user_login` WHERE `phone` = $intPhone LIMIT 0, 1";
        try{
            return $this->db->fetchAll($strSql);
        }catch(Base_Exception $ex){
            $this->logger->notice($ex->getMessage(),__METHOD__,$intUseId);
            throw new Base_Exception("Db operation error!");
        }
    }
}
