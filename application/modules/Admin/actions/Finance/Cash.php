<?php
/**
 * 提现列表页
 * @author
 *
 */
class CashAction extends Yaf_Action_Abstract {
    
    
    
    public function execute() {
        $pagesize = isset($_REQUEST['pagesize']) ? $_REQUEST['pagesize'] : 20;
        $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
        $userName = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
        $list     = new Finance_List_Order();
        $list->setFilter(array('type' => Finance_Order_Type::NETSAVE));
        if(!empty($userName)) {
            $user  = new User_List_Login();
            $user->setFilter(array('name' => $userName));
            $listData = $user->toArray();
            $userData = $listData['list'][0];
            $userid   = $userData['userid'];
            $list->appendFilterString("(`userId` = '$userid')");
        }
        $list->appendFilterString("status !=" . Finance_Order_Status::INITIALIZE);
        $list->setPage($page);
        $list->setPagesize($pagesize);
        $list->setOrder("`create_time` desc");
        $listData = $list->toArray();
        $data     = $listData['list'];
        $arrUser  = array();
        foreach ($data as $key => $value) {
            $userid  = $value['userId'];
            $objUser = User_Api::getUserObject($userid);
            $arrUser[$key]['userid']    = $userid;
            $arrUser[$key]['name']      = $objUser->name;
            $arrUser[$key]['huifuid']   = $objUser->huifuid;
            $arrUser[$key]['phone']     = $objUser->phone;
            $arrUser[$key]['orderId']   = $value['orderId'];
            $arrUser[$key]['transAmt']  = $value['amount'];
            $arrUser[$key]['orderDate'] = $value['orderDate'];
            $arrUser[$key]['status']    = Finance_Order_Status::getTypeName($value['status']);
        }
        //翻页所需数据 
        $total = ceil($listData['total']/$pagesize);
        $this->getView()->assign('arrUser', $arrUser);
        $this->getView()->assign('page', $page);
        $this->getView()->assign('total', $total);
    }
}
