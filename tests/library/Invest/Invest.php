<?php
include __DIR__ . '/TestBase.php';

class InvestTest extends TestBase {
    function testGetUserInvests() {
	    //@TODO fortest
	    $_POST = array(
	        'id' => 1,
	        'amount' => 100,
	    );
	    $_REQUEST = $_POST;
	    $res = $this->__requestActionAndParseBody('confirm', 'index');
	    var_dump($res);
	    
    }
}