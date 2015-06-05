<?php
/**
 * 天使列表
 * @author huwei
 *
 */
class AngelAction extends Yaf_Action_Abstract {
    public function execute() {
        $name = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
        $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
        $pagesize = isset($_REQUEST['pagesize']) ? $_REQUEST['pagesize'] : 20;
        $angel = new User_List_Login();        
        if(!empty($name)){
            $angel->setFilter(array('name'=>$name,'usertype'=>User_Type_Roles::TYPE_ANGEL));
        }else{
            $angel->setFilter(array('usertype'=>User_Type_Roles::TYPE_ANGEL));
        }
        $angel->setOrder(1);
        $angel->setPage($page);
        $angel->setPagesize($pagesize);        
        $arrUser = $angel->toArray();
        $logic = new Awards_Logic_Invite();
        foreach ($arrUser['list'] as $key => $val){
            $arrUser['list'][$key]['angelcode'] =  $logic->encode($val['userid']);
        }
        $this->getView()->assign('arrUser', $arrUser['list']); 
        $this->getView()->assign('pageall', $arrUser['pageall']);
        $this->getView()->assign('page', $page);
    }
}