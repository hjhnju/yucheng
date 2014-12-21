<?php
include __DIR__ . '/TestBase.php';

/**
 * @author jiangsongfang
 */
class GuaranteeTest extends TestBase {

    public function testDefaultFeature() {
        $loan_id = 1;
        
        $guaran = array(
            'name' => '王某某',
            'account' => '北京安顺',
            'age' => '39',
            'marriage' => '1',
            'company_type' => '基础教育学校',
            'job_title' => '校长',
            'income' => '20-30万',
        );
        
        $_POST = $guaran;
        $_GET['id'] = $loan_id;
        $body = $this->__requestActionAndParseBody('Guarantee', 'index');
        var_dump($body);
        $res = json_decode($body);
        $this->assertEquals(0, $res->status);
    }
}
