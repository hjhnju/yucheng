<?php
include __DIR__ . '/TestBase.php';

/**
 * @author jiangsongfang
 */
class ObjectTest extends TestBase {

    public function testSuccess() {
        $res = Loan_Api::lendSuccess(1);
        var_dump($res);
    }
}
