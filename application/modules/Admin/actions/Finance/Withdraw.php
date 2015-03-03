<?php
/**
 * 提现列表页
 * @author
 *
 */
class WithdrawAction extends Yaf_Action_Abstract {
    public function execute() {

        $userName = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
        $list     = new Finance_List_Order();
        $list->setFilter(array('type' => Finance_Order_Type::CASH));
        if(!empty($userName)) {
            $user  = new User_List_Login();
            $user->setFilter(array('name' => $userName));
            $listData = $user->toArray();
            $userData = $listData['list'][0];
            $userid   = $userData['userid'];
            $list->appendFilterString("(`userId` = '$userid')");
        }
        $list->appendFilterString("status !=" . Finance_Order_Status::INITIALIZE);
        $list->setPagesize(100);
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
        $this->getView()->assign('arrUser', $arrUser);
    }
}
