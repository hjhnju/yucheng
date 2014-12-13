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
     * @return int status 0插入正常，其它返回值出错
     */
    protected function addRecord($uid,$status,$ip){
        $strSql  = "INSERT INTO `login_record` (`uid`,`status`,`ip`) VALUES(";
        $strSql .= $uid.",";
        $strSql .= $status.",";
        $strSql .= $ip.")";
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
     * @param string $ip,登录时的用户ip
     */
    public function login($type,$strName,$strPasswd,$ip){
        $strSql = "SELECT  `uid` FROM `user_login` WHERE $type = $strName AND `passwd` = $strPasswd LIMIT 0, 1";
        try{
            $ret = $this->db->fetchAll($strSql);
            if(empty($ret)){
                $this->addRecord(0, User_RetCode::DB_ERROR, $ip);
                return User_RetCode::DATA_NULL;
            }
            else{
                $uid = $ret['uid'];
                $strSql = "UPDATE `user_login` SET `ip` = $ip";
                $ret = $this->db->execute($strSql);
                if(!empty($ret)){ 
                    $this->addRecord($uid, User_RetCode::SUCCESS, $ip);          
                    return User_RetCode::SUCCESS;
                }
            }
        }catch(Base_Exception $ex){
            $this->logger->notice($ex->getMessage(),__METHOD__,$intUseId);
            throw new Base_Exception("Db operation error!");
        }
    }
}
