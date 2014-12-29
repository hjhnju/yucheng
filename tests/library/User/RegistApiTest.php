<?php
include __DIR__ . '/TestBase.php';

class RegistApiTest extends TestBase {

    function testIndexAction() {
        $_POST = array(
            'name'    => 'hjhnju',
            'passwd'  => 'abc123',
            'phone'   => '18611015043',
            'vericode'=> '123456',
            'inviter' => '15801190228',
        );
        $_REQUEST = $_POST;
        $ret = $this->__requestActionAndParseBody('registapi', 'index');
        $ret = json_decode($ret);
        print_r($ret);
        $this->assertEquals($ret->status, 0);
        
    }
}