<?php
include __DIR__ . '/TestBase.php';

class PublishTest extends TestBase {

    public function testPublish() {
        $logic = new Admin_Logic_Loan();
        $res = $logic->publish(1);
        var_dump($res);
    }
}
