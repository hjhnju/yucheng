<?php
/**
 * 我的奖券页接口
 * @author hejunhua
 */
class AwardapiController extends Base_Controller_Api {
    
    public function init() {
        parent::init();
    }
    
    /**
     * /awardapi/tickets
     * @param $status 奖券状态 1-未达成，2-未使用，3-已使用，4-已过期
     * @param $page 页码
     * @param $pagesize 每页大小
     * @param token 默认csrftoken
     * @return 
     * $data = array(
     *   array(
     *       'id' => 100,
     *       'value' => '11.32',
     *       'src' => '4月推荐奖励',
     *       'valid_time' => 1423589305,
     *       'ticket_type' => 1, //现金券
     *       'enabled' => 1, //是否可兑换
     *       'desc' => '可直接兑换现金',
     *       'pay_time' => 1423589305, //兑换时间
     *       'status' => 1, //1-未达成，2-未使用，3-已使用，4-已过期
     *   ),
     */
    public function ticketsAction() {
        $status = isset($_REQUEST['status']) ? intval($_REQUEST['status']) : 
            Awards_Type_TicketStatus::NOT_USED;
        $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
        $pagesize = isset($_REQUEST['pagesize']) ? intval($_REQUEST['pagesize']) : 10;


        if(Awards_Type_TicketStatus::NOT_USED === $status){
            $status = Awards_Type_TicketStatus::NOT_FINISH . "," . Awards_Type_TicketStatus::NOT_USED;
        }

        $list    = new Awards_TicketList();
        $filter  = array('userid'=> $this->userid);
        $list->setFilter($filter);
        $list->appendFilterString(" status IN ($status)");
        $list->setPage($page);
        $list->setPagesize($pagesize);
        $list->setOrder('status desc, update_time desc');
        $arrData = $list->toArray();
        $arrData['list'] = array();
        $arrObjs = $list->getObjects();
        foreach ($arrObjs as $ticket) {
            $arrTicket                = array();
            $arrTicket['ticketid']    = $ticket->id;
            $arrTicket['amount']      = $ticket->value;
            $arrTicket['valid_time']  = $ticket->validTime;
            $arrTicket['pay_time']    = $ticket->payTime;
            $arrTicket['ticket_type'] = $ticket->ticketType;
            $arrTicket['src']         = $ticket->getSource();
            $arrTicket['enabled']     = intval($ticket->isEnabled());
            if($arrTicket['enabled'] 
                && $ticket->status === Awards_Type_TicketStatus::NOT_FINISH){
                $ticket->status = Awards_Type_TicketStatus::NOT_USED;
                $ticket->save();
            }
            $arrTicket['status'] = $ticket->status;
            $arrTicket['desc']   = $ticket->getDesc();
            $arrData['list'][]   = $arrTicket;
        }
        $this->ajax($arrData);
    }

    /**
     * 兑换或使用奖券
     * /awardapi/exchange
     * @param ticketid 奖券id
     * @param token 默认csrftoken
     */
    public function exchangeAction() {
        $ticketid = isset($_REQUEST['ticketid']) ? intval($_REQUEST['ticketid']) : 0;
        if($ticketid <=0){
            return $this->ajaxError(Base_RetCode::PARAM_ERROR);
        }

        $ticket = new Awards_Ticket($ticketid);
        $bolRet = $ticket->exchange($this->userid);
        
        if(!$bolRet){
            return $this->ajaxError(Awards_RetCode::CANNOT_USE_TICKET, 
                Awards_RetCode::getMsg(Awards_RetCode::CANNOT_USE_TICKET));
        }

        $desc = $ticket->getValueDesc();
        $msg = '您已兑换'.$desc.'成功，可进入账户总览中查看。';
        return $this->ajax(array('msg'=>$msg));

    }
  
}
