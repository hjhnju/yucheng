<?php
/**
 * 注册model类
 */
class RegistModel extends BaseModel {
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
    public function checkUserName($strName){
        $strSql = "SELECT  `uid` FROM `user_login` WHERE `name` = '$strName' LIMIT 0, 1";
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
    
    /**
     * 修改用户手机号
     */
    public function setPhone($intPhone){
        $strSql = "UPDATE  `user_login` SET `phone`=$intPhone WHERE `uid` = '$intUid'";
        try{
            return $this->db->fetchAll($strSql);
        }catch(Base_Exception $ex){
            $this->logger->notice($ex->getMessage(),__METHOD__,$intUseId);
            throw new Base_Exception("Db operation error!");
        }
    }
    
    /**
     * 修改用户邮箱
     */
    public function setEmail($strEmail){
        $strSql = "UPDATE  `user_login` SET `email`='$strEmail' WHERE `uid` = $intUid";
        try{
            return $this->db->fetchAll($strSql);
        }catch(Base_Exception $ex){
            $this->logger->notice($ex->getMessage(),__METHOD__,$intUseId);
            throw new Base_Exception("Db operation error!");
        }
    }
    
    /**
     * 修改用户密码
     */
    public function setPasswd($strPasswd){
        $strSql = "UPDATE  `user_login` SET `passwd`='$strPasswd' WHERE `uid` = '$intUid'";
        try{
            return $this->db->fetchAll($strSql);
        }catch(Base_Exception $ex){
            $this->logger->notice($ex->getMessage(),__METHOD__,$intUid);
            throw new Base_Exception("Db operation error!");
        }
    }
    
    /**
     * 设置用户绑定
     */
    public function setBinding(){
        $strSql = "UPDATE  `phone` FROM `user_login` WHERE `uid` = $intUid";
        try{
            return $this->db->fetchAll($strSql);
        }catch(Base_Exception $ex){
            $this->logger->notice($ex->getMessage(),__METHOD__,$intUid);
            throw new Base_Exception("Db operation error!");
        }
    }
    
    /**
     * 删除用户绑定
     */
    public function delBinding(){
        $strSql = "UPDATE  `phone` FROM `user_login` WHERE `uid` = $intUid";
        try{
            return $this->db->fetchAll($strSql);
        }catch(Base_Exception $ex){
            $this->logger->notice($ex->getMessage(),__METHOD__,$intUid);
            throw new Base_Exception("Db operation error!");
        }
    }
}
