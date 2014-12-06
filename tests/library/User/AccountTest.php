<?php
require_once SRC_PATH . '/library/User/Account.php';

/**
 * @author hejunhua <hejunhua@baidu.com>
 * @since 2013-12-05
 */
class User_AccountTest extends PHPUnit_Framework_TestCase {

    public function testDefaultFeature() {
        $bolCheck = true;
        $this->assertTrue($bolCheck === true);
    }
}
