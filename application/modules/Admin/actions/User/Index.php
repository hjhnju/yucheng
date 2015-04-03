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
        $pagesize = isset($_REQUEST['pagesize']) ? $_REQUEST['pagesize'] : 20;
        $list     = User_Api::getPrivUsers($page, $pagesize, $name);
        $arrUser  = $list['list'];
        $PageAll  = $list['pageall'];
        foreach ($arrUser as $key => $val){
            $objInfo = new User_Object_Info();
            $objInfo->fetch(array('userid'=>$val['userid']));
            $arrUser[$key]['realname'] = $objInfo->realname;
        }
        $this->getView()->assign('arrUser', $arrUser); 
        $this->getView()->assign('pageall', $PageAll);
        $this->getView()->assign('page', $page);
    }
}