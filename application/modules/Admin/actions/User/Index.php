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
        $arrUser  = User_Api::getPrivUsers($page, $pagesize, $name);
        foreach ($arrUser as $key => $val){
            $objInfo = new User_Object_Info();
            $objInfo->fetch(array('userid'=>$val['userid']));
            $arrUser[$key]['realname'] = $objInfo->realname;
        }
        $this->getView()->assign('arrUser', $arrUser);
    }
}