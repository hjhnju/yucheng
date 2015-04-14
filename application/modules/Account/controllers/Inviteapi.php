<?php
/**
 * 我的邀请接口
 * @author hejunhua
 */
class InviteapiController extends Base_Controller_Api {
    
    public function init() {
        parent::init();
    }

    /**
     * 列表
     * @return [type] [description]
     * $inviteInfo = array(
     *       'invitee_awards'     => '50.00',
     *       'invitee_cnt'        => 10,
     *       'invitee_invest_cnt' => 5,
     *       'invitee_list'       => array(
     *           array(
     *               'username'        => 'hj***ju',
     *               'phone'           => '186***043',
     *               'regist_progress' => 1, //已开通
     *               'invested'        => 1, //已投资
     *               'amount'          => '30.00',
     *           ),
     *           array(
     *               'username'        => 'hj***ju2',
     *               'phone'           => '186***043',
     *               'regist_progress' => 0, //未开通
     *               'invested'        => 0, //未投资
     *               'amount'          => null, //暂无奖励
     *           ),
     *       ),
     *   );
     */
    public function listAction() {
        $page     = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
        $pagesize = isset($_REQUEST['pagesize']) ? intval($_REQUEST['pagesize']) : 6;

        $list     = User_Api::getInvitees($this->userid, $page, $pagesize);
        $arrInvId = array();
        foreach ($list['list'] as $row) {
            $arrInvId[] = $row['invitee'];
        }

        $arrAwards   = Awards_Api::getInviteAwards($this->userid, $arrInvId);//获取邀请奖励列表
        $arrUinfos   = User_Api::getInfos($arrInvId);
        $arrIsInvest = Invest_Api::checkIsInvested($arrInvId);
        $arrInvitee  = array();
        foreach ($arrInvId as $invId) {
            $invInfo = array();
            $invInfo['userid']          = $arrUinfos[$invId]['userid'];
            $invInfo['username']        = $arrUinfos[$invId]['displayname'];
            $invInfo['phone']           = Base_Util_String::starPhone($arrUinfos[$invId]['phone']);
            $invInfo['regist_progress'] = isset($arrUinfos[$invId]['huifuid'])? 1:0;
            $invInfo['invested']        = $arrIsInvest[$invId];
            $invInfo['amount']          = $arrAwards[$invId];
            if($invInfo['amount'] === 0){
                $invInfo['amount'] = '暂无奖励';
            }
            $arrInvitee[] = $invInfo;
        }

        $list['list']       = $arrInvitee;
        $this->ajax($list);
    }
}