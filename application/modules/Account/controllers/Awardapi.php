<?php
/**
 * 我的奖券页接口
 * @author hejunhua
 */
class AwardapiController extends Base_Controller_Api {
    
    CONST PAGESIZE = 10;    

    public function init() {
        parent::init();
    }
    
    /**
     * /awardapi/tickets
     * @param status 奖券状态 1-未达成，2-未使用，3-已使用，4-已过期
     * @param token 默认csrftoken
     */
    public function ticketsAction() {
        $status = isset($_REQUEST['status']) ? intval($_REQUEST['status']) : 
            Awards_Type_TicketStatus::NOT_FETCHED;

        if(Awards_Type_TicketStatus::NOT_FETCHED === $status){
            $data = array(
                array(
                    'ticketid' => 100,
                    'amount' => '11.32',
                    'src' => '4月推荐奖励',
                    'valid_time' => 1423589305,
                    'ticket_type' => 1, //现金券
                    'enabled' => 1, //是否可兑换
                    'desc' => '可直接兑换现金',
                ),
                array(
                    'ticketid' => 101,
                    'amount' => '30.00',
                    'src' => '新手注册奖励',
                    'valid_time' => 1423589305,
                    'ticket_type' => 1, //现金券
                    'enabled' => 0, //是否可兑换
                    'desc' => '累计投资满1000元即可兑换',
                ),
                array(
                    'ticketid' => 102,
                    'amount' => '30.00',
                    'src' => '新手注册奖励',
                    'valid_time' => 1423589305,
                    'ticket_type' => 1, //现金券
                    'enabled' => 0, //是否可兑换
                    'desc' => '累计投资满1000元即可兑换',
                ),
            );
        }elseif (Awards_Type_TicketStatus::EXCHANGED === $status) {
            $data = array(
                array(
                    'ticketid' => 103,
                    'amount' => '11.32',
                    'src' => '4月推荐奖励',
                    'valid_time' => 1423589305,
                    'ticket_type' => 1, //现金券
                    'enabled' => 1, //是否可兑换
                    'pay_time' => 1423589305, //兑换时间
                ),
            );
        }elseif (Awards_Type_TicketStatus::OVER === $status) {
            $data = array(
                array(
                    'ticketid' => 104,
                    'amount' => '11.32',
                    'src' => '4月推荐奖励',
                    'valid_time' => 1423589305,
                    'ticket_type' => 1, //现金券
                    'enabled' => 1, //是否可兑换
                    'pass_time' => 1423589305, //过期时间
                ),
            );
        }
        $this->ajax($data);
    }

    /**
     * /awardapi/exchange
     * @param ticketid 奖券id
     * @param token 默认csrftoken
     */
    public function exchangeAction() {
        //错误信息
        $status = 1085;
        $msg  = '该奖券不能重复领取。';
        $this->ajaxError($status, $msg);
        
        //正确信息
        $data = 30.00;
        $msg = '您已兑换30.00元现金，可进入账户中心查看。';
        $this->ajax($data, $msg);

    }
  
}
