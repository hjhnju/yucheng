<?php
include __DIR__ . '/TestBase.php';

/**
 * @author jiangsongfang
 */
class ObjectTest extends TestBase {

    public function testDefaultFeature() {
        $log = new Loan_Object_Log();
        $log->loanId = 1;
        $log->userId = 2;
        $log->ip = '127.0.0.1';
        $log->content = 'hello kitty';
        $log->save();
        $data = $log->toArray();
        var_dump($data);
        
        $id = $log->id;
        var_dump('update', $id);
        $log->content = 'update hello';
        $log->save();
        $data2 = $log->toArray();
        var_dump($data2);
        $this->assertNotEquals($data, $data2);
        
        var_dump('fetch', $id);
        
        $log = new Loan_Object_Log($id);
        $data3 = $log->toArray();
        var_dump($data3);

        $this->assertEquals($data2, $data3);
        
        $log->remove();
        $log = new Loan_Object_Log($id);
        $this->assertEquals($log->content, null);
    }
}
