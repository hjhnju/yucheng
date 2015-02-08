<?php
/**
 * 用户列表
 * @author huwei
 *
 */
class IndexAction extends Yaf_Action_Abstract {
    public function execute() {
        $name = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
        $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
        $pagesize = isset($_REQUEST['pagesize']) ? $_REQUEST['pagesize'] : 10;
        $arrUser  = User_Api::getPrivUsers($page, $pagesize);
        if(!empty($name)){
            foreach ($arrUser as $key => $val){
                if(($val['name'] !== $name)&&($val['phone'] !== $name)){
                    unset($arrUser[$key]);
                }
            }
        }
        $this->getView()->assign('arrUser', $arrUser);
    }
}