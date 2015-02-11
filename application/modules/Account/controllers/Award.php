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
       
        //被邀请人userid
        $inviteeId     = intval($_REQUEST['id']);    
        $canNotErrCode = Finance_RetCode::CAN_NOT_REC_AWARD;
        $canNotErrMsg  = Finance_RetCode::getMsg($canNotErrCode);
        
        //领的是本人的注册奖励
        if($inviteeId === $this->userid) {          
            $regRegist = new Awards_Object_Regist($this->userid);
            Base_Log::notice(array($regRegist->status));
            if(empty($regRegist->status)) {//使用非空字段来判空，不能用userid
                Base_Log::error(array(
                    'userid' => $inviteeId,
                    'msg'    => '用户不在注册奖励表中',
                ));
                return $this->outputError($canNotErrCode,$canNotErrMsg);
            }
            $transAmt = $regRegist->amount;

        } else {
            $invite   = new Awards_Object_Invite(array('userid' => $inviteeId, 'inviterid'=>$this->userid));
            Base_Log::notice(array(
                'inviterid' => $this->userid,
                'inviteeId' => $inviteeId,
                'status'    => $invite->status,
            ));
            
            //使用主键非空字段判空（不能使用userid），过滤掉未达到和已领取
            if(empty($invite->id) || $invite->status===1 || $invite->status===3) {
                Base_Log::error(array(
                    'inviteeId' => $inviteeId,
                    'msg'       => '用户不在邀请奖励表中或未达投资满额或已领取',
                    'status'    => $invite->status,
                ));
                return $this->outputError($canNotErrCode,$canNotErrMsg);
            }
            $id       = $invite->id;
            $transAmt = $invite->amount;
        }    
        
        $failErrCode = Finance_RetCode::RECEIVE_AWARDS_FAIL;
        $failErrMsg  = Finance_RetCode::getMsg($failErrCode);
        $awardsLogic = new Awards_Logic_Awards();
        $db          = Base_Db::getInstance('xjd');
        $db->beginTransaction();
        if($inviteeId === $this->userid) {     
            $registRet = $awardsLogic->updateRegistStatus($inviteeId, Awards_Logic_Awards::STATUS_FINISH);
            if(!$registRet) {
                Base_Log::error(array(
                    'msg'      => '更新Awards_Regist表失败',
                    'userid'   => $inviteeId,
                    'transAmt' => $transAmt,
                ));
                $db->rollBack();    
                return $this->outputError($failErrCode,$failErrMsg);
            }
        } else {
            $inviteRet = $awardsLogic->updateAwardsStatus($id, Awards_Logic_Awards::STATUS_FINISH);
            if(!$inviteRet) {
                Base_Log::error(array(
                    'msg'      => '更新Awards_Invite表失败',
                    'userid'   => $inviteeId,
                    'transAmt' => $transAmt,
                ));
                $db->rollBack();
                return $this->outputError($failErrCode,$failErrMsg);
            }
        }

        //防止并发
        $redis = Base_Redis::getInstance();
        $nxkey = Awards_Keys::getReceiveNxKey($inviteeId, $this->userid);
        $used  = $redis->setnx($nxkey, 1);
        Base_Log::notice(array(
            'key'  => $nxkey,
            'used' => $used,
        ));
        if (empty($used)) {
            $msg = array(
                'inviteeId' => $inviteeId,
                'ownid'     => $this->userid,
                'msg'       => Finance_RetCode::getMsg(Finance_RetCode::RECEIVE_MULTI),
            );
            Base_Log::warn($msg);
            return $this->outputError(Finance_RetCode::RECEIVE_MULTI, Finance_RetCode::getMsg(Finance_RetCode::RECEIVE_MULTI));
        }

        $receRet = Finance_Api::giveAwards($this->userid, $transAmt);
        if(!$receRet) {
            Base_Log::error(array(
                'msg'      => '领取邀请奖励失败',
                'userid'   => $inviteeId,
                'transAmt' => $transAmt,
            ));
            $redis->delete($nxkey);
            $db->rollBack();
            return $this->outputError($failErrCode,$failErrMsg);
        }
        $db->commit();

        return $this->output(); 
    }  
}
