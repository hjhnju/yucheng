<?php
/**
 * 奖励邀请页面
 */
class AwardController extends Base_Controller_Page {
    
    CONST PAGESIZE = 20;    
    private $huifuid;
    public function init() {
        parent::init();
        $this->huifuid = !empty($this->objUser) ? $this->objUser->huifuid : '';
        $this->userInfoLogic = new Account_Logic_UserInfo();
        $this->ajax = true;
    }
    
    /**
     * assign至前端邀请url
     * inviteUrl 用户的专属邀请链接
     * userinfo 左上角信息
     */
    public function indexAction() {             
        $userid = $this->userid;    
        $webroot = Base_Config::getConfig('web')->root;
        
        $userInfo = $this->userInfoLogic->getUserInfo($this->objUser);
        $inviteUrl = Awards_Api::getInviteUrl($userid);
        $inviteUrl = ($inviteUrl != false) ? $inviteUrl : ""; //获取该用户的专属邀请链接
        
        $awardsInfo = Awards_Api::getAwards($userid);//获取邀请列表
        $this->getView()->assign('inviteUrl',$inviteUrl);   
        $this->getView()->assign('userinfo',$userInfo);
        $this->getView()->assign('inviterinfo',$awardsInfo);        
    }
    
    /**
     * 接口  /account/award/receiveawards
     * 领取奖励
     * @param userId 用户id
     * @return 标准json格式
     * status 0: 成功
     * status 1104:领取奖励失败
     */
    public function receiveawardsAction() {
        $awardsLogic = new Awards_Logic_Awards();
        $userid      = $_REQUEST['id'];
        $userid      = intval($userid);     
        $logic       = new Finance_Logic_Transaction();
        $conf        = Base_Config::getConfig('huifu', CONF_PATH . 'huifu.ini');
        $outUserId   = $conf['merId']
        $outAcctId   = 'MDT000001';
        //领的是本人的注册奖励
        if($userid === $this->userid) {
            $transAmt = 30.00;          
            $ret = Finance_Api::transfer($outUserId,$outAcctId,$transAmt,$this->userid,Finance_TypeStatus::RECE_AWD);
            if(!$ret) {
                Base_Log::error(array(
                    'msg'      => '领取注册奖励失败',
                    'userid'   => $userid,
                    'transAmt' => $transAmt,
                ));
                $errCode = Finance_RetCode::RECEIVE_AWARDS_FAIL;
                $errMsg  = Finance_RetCode::getMsg($errCode);
                $this->outputError($errCode,$errMsg);
                return ;
            }
            if(!($awardsLogic->updateRegistStatus($userid,Awards_Logic_Awards::STATUS_FINISH))) {
                Base_Log::error(array(
                    'msg'      => '领取注册奖励失败(更新Awards_Regist表失败)',
                    'userid'   => $userid,
                    'transAmt' => $transAmt,
                ));
                $errCode = Finance_RetCode::RECEIVE_AWARDS_FAIL;
                $errMsg = Finance_RetCode::getMsg($errCode);
                $this->outputError($errCode,$errMsg);
                return ;
            };  
            Msg_Api_sendmsg();
            $this->output();
            return ;
        }        
        $transAmt = 20.00;
        $userid = intval($userid);
        $invite = new Awards_List_Invite();
        $filters = array('userid' => $userid); //caution:被邀请人的userid
        $invite->setFilter($filters);
        $list = $invite->toArray(); //拿到了该邀请人邀请到的所有人的信息
        $userData = $list['list'][0];
        $id = $userData['id'];      
        $ret = Finance_Api::transfer($outUserId,$outAcctId,$transAmt,$this->userid,Finance_TypeStatus::RECE_AWD);
        if(!$ret) {
            Base_Log::error(array(
                'msg'      => '领取邀请奖励失败',
                'userid'   => $userid,
                'transAmt' => $transAmt,
            ));
            $errCode = Finance_RetCode::RECEIVE_AWARDS_FAIL;
            $errMsg = Finance_RetCode::getMsg($errCode);
            $this->outputError($errCode,$errMsg);
            return ;
        }
        if(!($awardsLogic->updateAwardsStatus($id,Awards_Logic_Awards::STATUS_FINISH))) {
            Base_Log::error(array(
                'msg'      => '领取邀请奖励失败(更新Awards_Invite表失败)',
                'userid'   => $userid,
                'transAmt' => $transAmt,
            ));
            $errCode = Finance_RetCode::RECEIVE_AWARDS_FAIL;
            $errMsg = Finance_RetCode::getMsg($errCode);
            $this->outputError($errCode,$errMsg);
            return ;
        }
        
        $this->output();        
        return ;
    }  
}
