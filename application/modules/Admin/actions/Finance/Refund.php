<?php
/**
 * 还款列表
 * @author hejunhua
 *
 */
class RefundAction extends Yaf_Action_Abstract {
    public function execute() {

        $list = new Loan_List_Refund();
        $time = time();
        //TODO:列表显示：未来所有状态的还款 ＋ 当前时间前未还的记录
        // $promiseTime = $time + 14*24*3600;
        // $list->setFilterString('promise_time <= '. $promiseTime);
        $list->setOrder('promise_time asc');
        $list->setPagesize(100);
        $list->joinType(new Loan_Type_Refund(), 'status');
        $list = $list->toArray();

        $arrRefund  = $list['list'];
        $this->getView()->assign('arrRefund', $arrRefund);
    }
}