<?php
include __DIR__ . '/TestBase.php';

/**
 * @author jiangsongfang
 */
class CompanyTest extends TestBase {

    public function testDefaultFeature() {
        $loan_id = 1;
        
        $company = array(
            'school' => '北京师范大学',
            'area' => '北京西城',
            'assets' => '2000万',
            'employers' => '25',
            'years' => '5',
            'funds' => '1000万',
            'students' => 500,
        );
        
        $_POST = $company;
        $_GET['id'] = $loan_id;
        $body = $this->__requestActionAndParseBody('Company', 'index');
        var_dump($body);
        $res = json_decode($body);
        $this->assertEquals(0, $res->status);
    }
}
