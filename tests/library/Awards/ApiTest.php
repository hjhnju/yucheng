<?php
include __DIR__ . '/TestBase.php';

class ApiTest extends TestBase {

    public function testGetAwards() {
        $userid = 123;
        Awards_Api::getAwards($userid);
        
    }
}
