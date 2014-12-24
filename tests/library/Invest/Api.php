<?php
include __DIR__ . '/TestBase.php';

class ApiTest extends TestBase {
    function testGetUserInvests() {
        $uid = 1;
        $data = Invest_Api::getUserInvests($uid);
        print_r($data);
        
        $data = Invest_Api::getUserInvests($uid, 2);
        print_r($data);
    }

    function testgetUserEarnings() {
        $uid = 1;
        $data = Invest_Api::getUserEarnings($uid);
        print_r($data);

        $data = Invest_Api::getEarningsMonthly($uid);
        print_r($data);
        
        $data = Invest_Api::getEarningsMonthly($uid);
        print_r($data);
    }
}