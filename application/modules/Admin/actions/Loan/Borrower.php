<?php
/**
 * 借款客户列表
 * @author hejunhua
 *
 */
class BorrowerAction extends Yaf_Action_Abstract {
    public function execute() {
        $page     = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
        $pagesize = isset($_REQUEST['pagesize']) ? $_REQUEST['pagesize'] : 10;
        
        if(!empty($_POST)){
        	$borrowerName = $_POST['borrower'];
        	$bolRet = User_Api::setBorrower($borrowerName);
        	if(!$bolRet){
        		$this->getView()->assign('error_msg', '设置借款人'.$borrowerName.'错误，请检查用户名是否正确。');
        	}
        }

        $arrUser  = User_Api::getBorrowers($page, $pagesize);
        $this->getView()->assign('arrUser', $arrUser);
    }
}