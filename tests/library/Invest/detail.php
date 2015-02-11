<?php
include __DIR__ . '/TestBase.php';

class InvestTest extends TestBase {
    function testGetUserInvests() {
        $loan = Loan_Api::getLoanDetail(10);
        var_dump($loan);
    }
}