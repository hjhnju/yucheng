<?php
/**
 * 后台退出
 * @author jiangsongfang
 *
 */
class IndexAction extends Yaf_Action_Abstract {
    public function execute() {
        echo 'logout';exit();
    }
}