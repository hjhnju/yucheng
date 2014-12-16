<?php
class Awards_Logic_Regist {

    const TYPE_REGIST = 1;
    const TYPE_INVITE = 2;
    
    /**
     * 给予奖励
     * @param int $userid
     * @param int $type
     * @return 标准json格式 
     * status 0: 奖励插入成功
     * status 1061: 奖励失败
     */
    public function giveAward($userid, $type){
        Invite_Object_Award($arrData); 

    }

    /**
     * Invite_Api::getAwards($userid)
     * 获取奖励列表
     * 1. 获取所有被邀请人的注册状态，投资进度
     * 2. 判断奖励条件是否达成
     * 3. 修改达成条件的状态
     * @param integer $userid
     * @return array
     */
    public function getAwards($userid){
        $userid = intval($userid);
        $refunds = new Invite_List_Awards();
        $filters = array('userid' => $userid);
        $refunds->setFilter($filters);
        $refunds->setOrder('create_time desc');
        $refunds->setPagesize(PHP_INT_MAX);
        $list = $refunds->toArray();
        var_dump($list);
    }
}
