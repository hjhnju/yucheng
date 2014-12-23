<?php
include __DIR__ . '/TestBase.php';

/**
 * @author jiangsongfang
 */
class ObjectTest extends TestBase {
    
    public function testEdit() {
        
        //edit
        $id = 1;
        var_dump('edit id=', $id);
        $_GET = array(
            'id' => $id,
        );
        unset($_POST);
        $_REQUEST = $_GET;
        if (!empty($_POST)) {
            var_dump($_POST);exit;
        }
        $body = $this->__requestActionAndParseBody('Edit', 'index');
        $res = json_decode($body, true);
        var_dump($body, $res);
        $this->assertEquals($id, $res['id']);
        
        $res['title'] = $res['title'] . 'edit';
        $title = $res['title'];
        $_POST = $res;
        $_REQUEST = $_POST;
        $body = $this->__requestActionAndParseBody('Edit', 'index');
        $res = json_decode($body, true);
        var_dump($res);
        $this->assertEquals($title, $res['title']);
        
        $res['title'] = str_replace('edit', '', $res['title']);
        $title = $res['title'];
        $_POST = $res;
        $_REQUEST = $_POST;
        $body = $this->__requestActionAndParseBody('Edit', 'index');
        $res = json_decode($body, true);
        var_dump($res);
        $this->assertEquals($title, $res['title']);
    }
}
