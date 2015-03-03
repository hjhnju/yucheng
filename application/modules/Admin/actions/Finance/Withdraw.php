<?php
/**
 * 提现列表页
 * @author
 *
 */
class WithdrawAction extends Yaf_Action_Abstract {
    public function execute() {
    	$userName = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
    	$list = new Finance_List_Order();
    	$list->setFilter(array('type' => Finance_Order_Type::CASH));
    	if(!empty($userName)) {
    		$user  = new User_List_Login();
    		$user->setFilter(array('name' => $userName));
    		$listData = $user->toArray();
    		$userData = $listData['list'][0];
    		$userid = $userData['userid'];
    		$list->appendFilterString("(`userId` = '$userid')");
    	}
    	$list->setPagesize(PHP_INT_MAX);
    	$list->setOrder("`create_time` desc");
    	$listData = $list->toArray();
    	$data = $listData['list'];
    	$arrUser = array();
    	foreach ($data as $key => $value) {
    		$userid = $value['userId'];
    		$objUser = User_Api::getUserObject($userid);
    		$arrUser[$key]['userid'] = $userid;
    		$arrUser[$key]['name'] = $objUser->name;
    		$arrUser[$key]['huifuid'] = $objUser->huifuid;
    		$arrUser[$key]['phone'] = $objUser->phone;
    		$arrUser[$key]['orderId'] = $value['orderId'];
    		$arrUser[$key]['transAmt'] = $value['amount'];
    		$arrUser[$key]['orderDate'] = $value['orderDate'];
    		switch ($value['status']) {
    			case 0:
    				$arrUser[$key]['status'] = "订单初始化";
    				break;
    			case 1:
    				$arrUser[$key]['status'] = "订单处理中";
    				break;
    			case 2:
    				$arrUser[$key]['status'] = "失败";
    				break;
    			case 3:
    				$arrUser[$key]['status'] = "成功";
    				break;
    			default:
    				break;
    		}
    	}
    	$this->getView()->assign('arrUser', $arrUser);
    }
}
