<?php
/**
 * 活动列表页
 */
class ActivityController extends Base_Controller_Page {

    public function init(){
        $this->setNeedLogin(false);
        parent::init();
    }
    
    /**
     * 活动列表页
     */
    public function indexAction() {
        
    }

    /**
     * 春风化雨，正式上线活动页
     * /activity/spring
     * @assign array()
     *   $data = array(
     *       //1.实时注册播报
     *       'broad_regist' => array(
     *           array('tpl'=>1, 'displayname'=>'hj**jh', 'amount'=>30),
     *           array('tpl'=>2, 'displayname'=>'hj**jh', 'amount'=>30, 'invest'=>1200, 'word'=>'屁颠屁颠'),
     *           array('tpl'=>2, 'displayname'=>'hj**h2', 'amount'=>30, 'invest'=>1500, 'word'=>'高高兴兴'),
     *       ),
     *       //2.实时邀请播报
     *       'broad_invite' => array(
     *           array('tpl'=>1, 'displayname'=>'hj**jh', 'name'=>'小米充电宝'),
     *           array('tpl'=>1, 'displayname'=>'hj**jh', 'name'=>'小米充电宝'),
     *           array('tpl'=>2, 'displayname'=>'hj**jh', 'inviter'=>'hj**h2'),
     *       ),
     *       //3.邀请排行
     *       'top_inviter' => array(
     *           array('displayname'=>'hj**jh', 'count'=>16),
     *           array('displayname'=>'hj**jh', 'count'=>15),
     *           array('displayname'=>'hj**jh', 'count'=>15),
     *       ),
     *       //4.有土豪朋友幸福榜
     *       'top_invite_invest' => array(
     *           array('displayname'=>'hj**jh', 'amount'=>100, 'invest'=>10000, 'word'=>'心里乐开了花'),
     *           array('displayname'=>'hj**jh', 'amount'=>10, 'invest'=>5000, 'word'=>'满心欢喜'),
     *       ),
     *       //5.实时单笔投资排行榜
     *       'top_per_invest' => array(
     *           array('displayname'=>'hj**jh', 'amount'=>100000),
     *           array('displayname'=>'hj**jh', 'amount'=>50000),
     *           array('displayname'=>'hj**jh', 'amount'=>10000),
     *       ),
     *       //6.累计投资排行榜
     *       'top_invest' => array(
     *           array('displayname'=>'hj**jh', 'amount'=>200000),
     *           array('displayname'=>'hj**jh', 'amount'=>100000),
     *           array('displayname'=>'hj**jh', 'amount'=>10000),
     *       ),
     *
     *   );
     */
    public function springAction() {

        //收集用户id
        $arrUid    = array();
        $startTime = strtotime("2015-04-15 00:00:00");
        $endTime   = strtotime("2015-05-15 00:00:00");

        //1.实时注册播报
        $list = new Awards_TicketList();
        $list->setFilter(array(
            'award_type'  => Awards_Type_AwardType::REGIST,
            'ticket_type' => Awards_Type_TicketType::CASH,
        ));
        $list->setFields(array('userid', 'value', 'status'));
        $list->appendFilterString('userid > 0 and create_time >' . $startTime);
        $list->setOrder('update_time desc');
        $list->setPageSize(10);
        $list = $list->toArray();
        $data['broad_regist'] = $list['list'];
        foreach ($data['broad_regist'] as $row) {
            $arrUid[$row['userid']] = 1;
        }
        //2.实时邀请播报
        $sql = "SELECT `userid`, `name` as entity 
                 FROM `awards_entity` WHERE type=2 and userid>0 and create_time>=$startTime and create_time<=$endTime 
                 order by update_time desc limit 0, 20";
        $list1 = Base_Db::getInstance('xjd')->fetchAll($sql);

        $leftCnt = 10 - count($list1);
        $list2 = array();
        if($leftCnt > 0){
            $sql = "SELECT `userid` as inviter, `invitee` as userid 
                 FROM `user_invite` WHERE create_time>=$startTime and create_time<=$endTime 
                 order by update_time desc limit 0,$leftCnt";
            $list2 = Base_Db::getInstance('xjd')->fetchAll($sql);
        }
        $data['broad_invite'] = array_merge($list1, $list2);
        foreach ($data['broad_invite'] as $row) {
            if(isset($row['inviter'])){
                $arrUid[$row['inviter']] = 1;
            }
            $arrUid[$row['userid']] = 1;
        }
        //3.邀请排行
        $sql = "SELECT `userid`, count(`invitee`) as count 
            FROM `user_invite` WHERE create_time>=$startTime and create_time<=$endTime group by userid order by count desc LIMIT 0,8";
        $arrRet = Base_Db::getInstance('xjd')->fetchAll($sql);
        $data['top_inviter'] = $arrRet;
        foreach ($data['top_inviter'] as $row) {
            $arrUid[$row['userid']] = 1;
        }

        //4.有土豪朋友幸福榜
        $sql = "SELECT `userid`, sum(`value`) as amount
            FROM `awards_ticket` WHERE userid>0 and status IN(2,3) and award_type=2
            and create_time>=$startTime and create_time<=$endTime group by userid order by amount desc LIMIT 0,8";
        $arrRet = Base_Db::getInstance('xjd')->fetchAll($sql);
        $data['top_invite_invest'] = $arrRet;
        $activity = new Awards_Activity_Invite201504();
        foreach ($data['top_invite_invest'] as $row) {
            $arrUid[$row['userid']] = 1;
        }

        //5.单笔投资排行
        $sql = "SELECT `user_id` as userid, `amount` 
            FROM `invest` WHERE create_time>=$startTime and create_time<=$endTime order by amount desc LIMIT 0,8";
        $arrRet = Base_Db::getInstance('xjd')->fetchAll($sql);
        $data['top_per_invest'] = $arrRet;
        foreach ($data['top_per_invest'] as $row) {
            $arrUid[$row['userid']] = 1;
        }

        //6.累计投资排行榜
        $sql = "SELECT `user_id` as userid, sum(`amount`) as amount 
            FROM `invest` WHERE create_time>=$startTime and create_time<=$endTime group by userid order by amount desc LIMIT 0,8";
        $arrRet = Base_Db::getInstance('xjd')->fetchAll($sql);
        $data['top_invest'] = $arrRet;
        foreach ($data['top_invest'] as $row) {
            $arrUid[$row['userid']] = 1;
        }

        //拼接用户名
        $arrUinfo = User_Api::getInfos(array_keys($arrUid));
        //1.实时注册播报
        foreach ($data['broad_regist'] as &$row) {
            $row['displayname'] = $arrUinfo[$row['userid']]['displayname'];
            $row['tpl'] = ($row['status'] === 3) ? 2 : 1;
            $row['amount'] = $row['value'];
            if($row['tpl'] === 1){
                $row['word'] = array_rand(array('从天而降'=>1, '飞来'=>1));
            }else{
                $row['word'] = array_rand(array('兴高采烈'=>1, '屁颠屁颠'=>1));
            }
        }

        //2.实时邀请播报
        foreach ($data['broad_invite'] as &$row) {
            $row['displayname'] = $arrUinfo[$row['userid']]['displayname'];
            if(isset($row['inviter'])){
                $row['inviter'] = $arrUinfo[$row['inviter']]['displayname'];
            }
            $row['tpl'] = isset($row['entity']) ? 1 : 2;
        }

        //3.邀请排行
        foreach ($data['top_inviter'] as &$row) {
            $row['displayname'] = $arrUinfo[$row['userid']]['displayname'];
        }
        
        //加入的数据
        $arrResult  = array(
            array('displayname' => Base_Util_String::starUsername('fengzhong'),'count'=>234),
            array('displayname' => Base_Util_String::starUsername('aiqinghai'),'count'=>107),
            array('displayname' => Base_Util_String::starUsername('shijidahe'),'count'=>211),
            array('displayname' => Base_Util_String::starUsername('guoshen'),'count'=>99),
            array('displayname' => Base_Util_String::starUsername('zhonglian'),'count'=>87),
            array('displayname' => Base_Util_String::starUsername('chongming'),'count'=>66),
            array('displayname' => Base_Util_String::starUsername('wuzetianxia'),'count'=>59),
            array('displayname' => Base_Util_String::starUsername('haowangjiao'),'count'=>47),
            array('displayname' => Base_Util_String::starUsername('yongsheng'),'count'=>32),
            array('displayname' => Base_Util_String::starUsername('baihuajifang'),'count'=>11)
        );
        
        $data['top_inviter'] = array_merge($data['top_inviter'], $arrResult);  
        $counts = array();
        foreach ($data['top_inviter'] as $user) {
            $counts[] = $user['count'];
        }
        array_multisort($counts, SORT_DESC , $data['top_inviter']);
        
        $data['top_inviter'] = array_slice($data['top_inviter'],0,8);
        
        //4.有土豪朋友幸福榜
        foreach ($data['top_invite_invest'] as &$row) {
            $row['displayname'] = $arrUinfo[$row['userid']]['displayname'];
            $row['invest'] = $activity->getOriginValue($row['amount']);
        }

        //5.单笔投资排行
        foreach ($data['top_per_invest'] as &$row) {
            $row['displayname'] = $arrUinfo[$row['userid']]['displayname'];
        }

        //6.累计投资排行榜
        foreach ($data['top_invest'] as &$row) {
            $row['displayname'] = $arrUinfo[$row['userid']]['displayname'];
        }

        $this->getView()->assign(array('data'=>$data));
    }
    
}
