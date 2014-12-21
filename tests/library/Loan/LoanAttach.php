<?php
include __DIR__ . '/TestBase.php';

/**
 * @author jiangsongfang
 */
class ObjectTest extends TestBase {

    public function testDefaultFeature() {
        $loan_id = 1;
        
        $attach = array(
            'loan_id' => $loan_id,
            'type' => Loan_Type_Attach::CERTIFICATION,
            'title' => '身份证',
            'url' => 'http://b.hiphotos.baidu.com/news/q%3D100/sign=09a4f4ac2ef5e0fee8188d016c6134e5/4610b912c8fcc3ce3a4632869145d688d53f2085.jpg',
        );
        
        $_POST = $attach;
        $_GET['id'] = $loan_id;
        $body = $this->__requestActionAndParseBody('Attach', 'add');
        $res = json_decode($body);
        $this->assertEquals(0, $res->status);
    }
}
