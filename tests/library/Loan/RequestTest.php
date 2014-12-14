<?php
include __DIR__ . '/TestBase.php';

/**
 * @author jiangsongfang
 */
class ObjectTest extends TestBase {

    public function testDefaultFeature() {
        $body = $this->__requestActionAndParseBody('Request', 'index');
        $this->assertEquals($body, 'request');
        
        $_POST = array(
            'title' => '借款标题',
            'school_type' => '1',
            'use_type' => '1',
            'amount' => '2000000',
            'interest' => '12',
            'period' => 0,
            'name' => 'eric',
            'prov_id' => 5,
            'city_id' => 6,
            'contact' => '133',
            'pay_type' => '1',
            'content' => '借款说明',
            'ajax' => 1,
        );
        $_REQUEST = $_POST;
        $body = $this->__requestActionAndParseBody('Request', 'index');
        $res = json_decode($body);
        //添加成功
        $this->assertEquals($res->status, 0);
        
        //failed test
        $_POST['name'] = '';
        $_REQUEST = $_POST;
        $body = $this->__requestActionAndParseBody('Request', 'index');
        $res = json_decode($body);
        $this->assertNotEquals($res->status, 0);
    }
    
    public function testList() {
        $body = $this->__requestActionAndParseBody('Request', 'list');
        $res = json_decode($body);
        $this->assertTrue($res->total > 1);
        $this->assertTrue(count($res->list) > 1);
        
        //large data
        $_REQUEST['page'] = 10000;
        $body = $this->__requestActionAndParseBody('Request', 'list');
        $res = json_decode($body);
        $this->assertTrue($res->total > 1);
        $this->assertTrue(count($res->list) == 0);
    }
}
