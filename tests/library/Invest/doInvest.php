<?php
include __DIR__ . '/TestBase.php';

class InvestTest extends TestBase {
    function testGetUserInvests() {
        $userid = 3;
        $loanId = 10;
        $amount = 43;
        $orderId = 123456789;
        
        // 删除防并发
        $redis = Base_Redis::getInstance();
        $used_key = 'invest_order_' . $orderId;
        $redis->del($used_key);
        
        // 更新投标金额以便进行投标
        $loan = new Loan_Object_Loan($loanId);
        $loan->investAmount = $loan->amount - $amount;
        $loan->status = Loan_Type_LoanStatus::LENDING;
        $loan->save();
        
        // 删除掉原有的投标记录
        $invest = new Invest_Object_Invest();
        $invest->orderId = $orderId;
        $invest->fetch();
        $invest->remove();
        
        // 首次投标应该成功
        $logic = new Invest_Logic_Invest();
        $res = $logic->doInvest($orderId, $userid, $loanId, $amount);
        $this->assertTrue($res);
        
        // 再次投标会因为各种原因失败
        $res = $logic->doInvest($orderId, $userid, $loanId, $amount);
        $this->assertTrue(!$res);
    }
}