<?php
include __DIR__ . '/TestBase.php';

class InvestTest extends TestBase {
    function testGetUserInvests() {
        $uid = 15;
        $total = Invest_Api::getUserInvestTotal($uid);
        $this->assertTrue(100 <= $total);
        
        $uid = 15;
        $total = Invest_Api::getUserInvestTotal($uid, time());
        $this->assertTrue(100 > $total);
        
        $uid = 1;
        $total = Invest_Api::getUserInvestTotal($uid);
        $this->assertEqual(0, $total);
    }
}