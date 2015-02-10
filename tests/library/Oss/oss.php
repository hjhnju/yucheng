<?php
include __DIR__ . '/TestBase.php';

class OssTest extends TestBase {
    function testOss() {
        $oss = Oss_Adapter::getInstance();
        $filename = __DIR__ . '/TestBase.php';
        $object = 'test.php';
        $res = $oss->writeFile($object, $filename);
        $this->assertTrue($res);

        $res = $oss->writeFile($object, $filename.'1');
        $this->assertTrue(!$res);
	    
        $content = $oss->getContent($object);
        $this->assertTrue(!empty($content));
        
        $res = $oss->writeFileContent($object, 'hello');
        $this->assertTrue($res);

        $content = $oss->getContent($object);
        $this->assertEquals('hello', $content);
        
        $res = $oss->exists($object);
        $this->assertTrue($res);
        
        $res = $oss->remove($object);
        $this->assertTrue($res);
        
        $res = $oss->exists($object);
        $this->assertTrue(!$res);
    }
}