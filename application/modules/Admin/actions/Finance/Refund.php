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
        //设置排序方式
        $list->setOrder('promise_time asc');
        //因为目前没有做分页，暂时设置为PHP_INT_MAX
        $list->setPagesize(PHP_INT_MAX);
        //normal 表示正常待还款
        $normal = Loan_Type_Refund::NORMAL;
        //outTime 表示还款超期
        $outTime = Loan_Type_Refund::OUTTIME;
        //设置查询条件（where）
        $list->setFilterString("status in ($normal,$outTime)");
        //加入类型
        $list->joinType(new Loan_Type_Refund(), 'status');
        $list = $list->toArray();
        $arrRefund  = $list['list'];
        $this->getView()->assign('arrRefund', $arrRefund);
    }
}