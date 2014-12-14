<?php
/**
 * 后台管理页面controller基础类
 * @author jiangsongfang
 */
class Base_Controller_Admin extends Base_Controller_Response {
    public function init() {
        parent::init();
    }
    
    public function checkLogin() {
        
    }
    
    public function getAdminId() {
        return 1;
    }
}
