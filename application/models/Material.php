<?php
/**
 * 用户资料model类
 */
class MaterialModel extends BaseModel {
    
    public function __construct (){
        parent::__construct();
    }

    /**
     * 用户开通汇付，填加新数据
     * @param $arrParams 用户的信息
     * @return int status 0插入正常，1出错
     */
    public function addUserInfo($arrParams){
        $strSql  = "REPLACE INTO `user_info` (`uid`,`type`,`real_name`,`certificate_type`,`certificate_content`,`huifu_uid`) VALUES(";
        $strSql .= self::USER_STATUS.",";
        $strSql .= $arrParams['uid'].",";
        $strSql .= $arrParams['type'].",";
        $strSql .= $arrParams['real_name'].",";
        $strSql .= $arrParams['certificate_type'].",";
        $strSql .= $arrParams['certificate_content'].",";
        $strSql .= $arrParams['huifu_uid'].")";
        try{
            return $this->db->execute($strSql);
        }catch(Base_Exception $ex){
            $this->logger->notice($ex->getMessage(),__METHOD__,$intUseId);
            throw new Base_Exception("Db operation error!");
        }
    }
    
    /**
     * 
     * @param string $uid
     * @throws Base_Exception
     */
    public function getUserInfo($uid){
        $strSql  = "SELECT `type`,`real_name`,`certificate_type`,`certificate_content`,`huifu_uid` from `user_info`  WHERE `uid` = $uid";
        try{
            return $this->db->execute($strSql);
        }catch(Base_Exception $ex){
            $this->logger->notice($ex->getMessage(),__METHOD__,$intUseId);
            throw new Base_Exception("Db operation error!");
        }
    }
    
    /**
     * 检查用户名是否存在
     */
    public function checkUserName($strName){
        $strSql = "SELECT  `uid` FROM `user_info` WHERE `name` = '$strName' LIMIT 0, 1";
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
        $strSql = "SELECT  `uid` FROM `user_info` WHERE `phone` = $intPhone LIMIT 0, 1";
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
        $strSql = "UPDATE  `user_info` SET `phone`= $intPhone WHERE `uid` = $intUid";
        try{
            return $this->db->execute($strSql);
        }catch(Base_Exception $ex){
            $this->logger->notice($ex->getMessage(),__METHOD__,$intUseId);
            throw new Base_Exception("Db operation error!");
        }
    }
    
    /**
     * 修改用户邮箱
     */
    public function setEmail($strEmail){
        $strSql = "UPDATE  `user_info` SET `email`='$strEmail' WHERE `uid` = $intUid";
        try{
            return $this->db->execute($strSql);
        }catch(Base_Exception $ex){
            $this->logger->notice($ex->getMessage(),__METHOD__,$intUseId);
            throw new Base_Exception("Db operation error!");
        }
    }
    
    /**
     * 修改用户密码
     */
    public function setPasswd($strPasswd){
        $strSql = "UPDATE  `user_info` SET `passwd`='$strPasswd' WHERE `uid` = $intUid";
        try{
            return $this->db->execute($strSql);
        }catch(Base_Exception $ex){
            $this->logger->notice($ex->getMessage(),__METHOD__,$intUid);
            throw new Base_Exception("Db operation error!");
        }
    }
}
