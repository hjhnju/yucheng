<?php
/**
 * 用户统计
 * @author huwei
 *
 */
class CountAction extends Yaf_Action_Abstract {
    public function execute() {
        $today = strtotime(date("Y-m-d"));
        $userObj = new User_List_Info();
        $userObj->setFilterString("`create_time` >= $today");
        $AddTotal = $userObj->toArray();
        
        $userObj = new User_List_Info();
        $userObj->setFilterString("`create_time` >= $today and `realname` != ''");
        $AddRealName = $userObj->toArray();
        
        $userObj = new User_List_Login();
        $userObj->setFilterString("`create_time` >= $today and `name` = ''");
        $PhoneRegist = $userObj->toArray();
        
        $userObj = new User_List_Info();
        $userObj->setFilterString("`create_time` >= $today and `realname` != ''");
        $PhoneReal = $userObj->toArray();
        $arrUserid1 = array();
        $arrUserid2 = array();
        foreach ($PhoneRegist['list'] as $val){
            $arrUserid1[] = $val['userid'];
        }
        foreach ($PhoneReal['list'] as $val){
            $arrUserid2[] = $val['userid'];
        }
        $arrUserid = array_intersect($arrUserid1,$arrUserid2);
     
        $this->getView()->assign('AddTotal', $AddTotal['total']); 
        $this->getView()->assign('AddRealName', $AddRealName['total']);
        $this->getView()->assign('PhoneRegist', $PhoneRegist['total']);
        $this->getView()->assign('PhoneReal', count($arrUserid));
    }
}