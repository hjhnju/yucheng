<?php
include __DIR__ . '/TestBase.php';

/**
 * @author jiangsongfang
 */
class ObjectTest extends TestBase {

    public function testDefaultFeature() {
        $loan_id = 1;
        
        $audit = array(
            'audit' => array(
                0 => array(
                    'id' => 5,
                    'type' => Loan_Type_Audit::COMPANY,
                    'name' => '实地认证',
                    'status' => 1,
                    'comment' => '通过',
                ),
                1 => array(
                    'id' => 1,
                    'type' => Loan_Type_Audit::GUARANTEE,
                    'name' => '身份证',
                    'status' => 1,
                    'comment' => '通过',
                ),
            ),
        );
        
        $_POST = $audit;
        $_GET['id'] = $loan_id;
        $body = $this->__requestActionAndParseBody('Audit', 'index');
        var_dump($body);
        $res = json_decode($body);
        $this->assertEquals(0, $res->status);
    }
}
