<?php
include __DIR__ . '/TestBase.php';

/**
 * @author jiangsongfang
 */
class ObjectTest extends TestBase {

    public function testDefaultFeature() {
        
        $body = $this->__requestActionAndParseBody('Create', 'index');
        $this->assertEquals($body, 'request');
        
        $_POST = array(
            'user_id' => 1,
            'title' => '借点钱买车',
            'pic' => '',
            'content' => '不说明看能不能借到钱',
            'type_id' => 1,
            'cat_id' => 1,
            'duration' => 12,
            'level' => 1,
            'amount' => 200000,
            'interest' => '12.00',
            'guarantee_type' => 1,
            'audit_info' => '经过审核可以借',
            'deadline' => date("Y-m-d H:i:s", time() + 7 * 24 * 3600),
            'status' => '1',
            'create_uid' => 1,
            'ajax' => 1,
        );
        $_REQUEST = $_POST;
        $body = $this->__requestActionAndParseBody('Create', 'index');
        $res = json_decode($body);
        //添加成功
        $this->assertEquals($res->status, 0);
        var_dump($res);
        $id = $res->data->id;
        
        //failed test
        $_POST['title'] = '';
        $_REQUEST = $_POST;
        $body = $this->__requestActionAndParseBody('Create', 'index');
        $res = json_decode($body);
        var_dump($res);
        $this->assertNotEquals($res->status, 0);
    }
}
