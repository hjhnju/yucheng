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
     * 
     */
    public function springAction() {
        $data = array(
            //实时注册播报
            'broad_regist' => array(
                array('tpl'=>1, 'displayname'=>'hj**jh', 'amount'=>30),
                array('tpl'=>2, 'displayname'=>'hj**jh', 'amount'=>30, 'invest'=>1200, 'word'=>'屁颠屁颠'),
                array('tpl'=>2, 'displayname'=>'hj**h2', 'amount'=>30, 'invest'=>1500, 'word'=>'高高兴兴'),
            ),
            //实时邀请播报
            'broad_invite' => array(
                array('tpl'=>1, 'displayname'=>'hj**jh', 'inviter'=>'hj**h2', 'entity'=>'小米充电宝'),
                array('tpl'=>2, 'displayname'=>'hj**jh', 'entity'=>'小米充电宝'),
                array('tpl'=>2, 'displayname'=>'hj**jh', 'entity'=>'小米充电宝'),
            ),
            //邀请排行
            'top_inviter' => array(
                array('displayname'=>'hj**jh', 'count'=>16),
                array('displayname'=>'hj**jh', 'count'=>15),
                array('displayname'=>'hj**jh', 'count'=>15),
            ),
            //有土豪朋友幸福榜
            'top_invite_invest' => array(
                array('displayname'=>'hj**jh', 'amount'=>100, 'invest'=>10000, 'word'=>'心里乐开了花'),
                array('displayname'=>'hj**jh', 'amount'=>10, 'invest'=>5000, 'word'=>'满心欢喜'),
            ),
            //实时单笔投资排行榜
            'top_per_invest' => array(
                array('displayname'=>'hj**jh', 'amount'=>100000),
                array('displayname'=>'hj**jh', 'amount'=>50000),
                array('displayname'=>'hj**jh', 'amount'=>10000),
            ),
            //累计投资排行榜
            'top_invest' => array(
                array('displayname'=>'hj**jh', 'amount'=>200000),
                array('displayname'=>'hj**jh', 'amount'=>100000),
                array('displayname'=>'hj**jh', 'amount'=>10000),
            ),

        );
        $this->getView()->assign($data);
    }
    
}
