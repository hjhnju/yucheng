<?php
/**
 * 奖励邀请页面
 */
class AwardController extends Base_Controller_Page {
    
    CONST PAGESIZE = 20;    

    public function init() {
        parent::init();
        $this->userInfoLogic = new Account_Logic_UserInfo();
        $this->ajax          = true;
    }
    
    /**
     * assign至前端邀请url
     * inviteUrl 用户的专属邀请链接
     * userinfo 左上角信息
     */
    public function indexAction() {             
        $userid     = $this->userid;    
        $webroot    = Base_Config::getConfig('web')->root;
        
        $userInfo   = $this->userInfoLogic->getUserInfo($this->objUser);
        $inviteUrl  = Awards_Api::getInviteUrl($userid);
        $inviteUrl  = ($inviteUrl != false) ? $inviteUrl : ""; //获取该用户的专属邀请链接
        
        $awardsInfo = Awards_Api::getAwards($userid);//获取邀请列表
        $this->getView()->assign('inviteUrl',$inviteUrl);   
        $this->getView()->assign('userinfo',$userInfo);
        $this->getView()->assign('inviterinfo',$awardsInfo);        
    }
    
    /**
     * 接口  /account/award/receiveawards
     * 领取奖励
     * TODO::领取奖励得先看他有没有奖励！
     * @param userId 用户id
     * @return 标准json格式
     * status 0: 成功
     * status 1104:领取奖励失败
     */
    public function receiveawardsAction() {
        $awardsLogic = new Awards_Logic_Awards();
        $userid      = intval($_REQUEST['id']);     
        //领的是本人的注册奖励
        if($userid === $this->userid) {
            $transAmt = Base_Config::getConfig('awards.regist.amount', CONF_PATH.'/awards.ini');   
            $ret      = Finance_Api::giveAwards($this->userid, $transAmt);
            if(!$ret) {
                Base_Log::error(array(
                    'msg'      => '领取注册奖励失败',
                    'userid'   => $userid,
                    'transAmt' => $transAmt,
                ));
                //错误码不应该在Finance模块定义
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
                $errMsg  = Finance_RetCode::getMsg($errCode);
                $this->outputError($errCode,$errMsg);
                return;
            };  
            //TODO:?
            //Msg_Api::sendmsg();
            $this->output();
            return;
        }        

        $transAmt = Base_Config::getConfig('awards.inviter.amount', CONF_PATH.'/awards.ini');
        $userid   = intval($userid);
        $invite   = new Awards_List_Invite();
        $filters  = array('userid' => $userid); //caution:被邀请人的userid
        $invite->setFilter($filters);
        
        $list     = $invite->toArray(); //拿到了该邀请人邀请到的所有人的信息
        $userData = $list['list'][0];
        $id       = $userData['id'];     
        $ret      = Finance_Api::giveAwards($this->userid, $transAmt); 
        if(!$ret) {
            Base_Log::error(array(
                'msg'      => '领取邀请奖励失败',
                'userid'   => $userid,
                'transAmt' => $transAmt,
            ));
            $errCode = Finance_RetCode::RECEIVE_AWARDS_FAIL;
            $errMsg  = Finance_RetCode::getMsg($errCode);
            $this->outputError($errCode,$errMsg);
            return ;
        }

        //TODO:为什么发完奖励还允许报不成功！不应该是先更新再发奖（一个事务里）
        if(!($awardsLogic->updateAwardsStatus($id,Awards_Logic_Awards::STATUS_FINISH))) {
            Base_Log::error(array(
                'msg'      => '领取邀请奖励失败(更新Awards_Invite表失败)',
                'userid'   => $userid,
                'transAmt' => $transAmt,
            ));
            $errCode = Finance_RetCode::RECEIVE_AWARDS_FAIL;
            $errMsg  = Finance_RetCode::getMsg($errCode);
            $this->outputError($errCode,$errMsg);
            return ;
        }
        
        $this->output();        
        return ;
    }  
}
