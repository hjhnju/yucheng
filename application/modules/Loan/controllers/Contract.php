<?php
/**
 * 借款协议
 */
class ContractController extends Base_Controller_Page {

    public function init(){
        $this->setNeedLogin(true);
        parent::init();
    }
    
    /**
     * 借款协议
     */
    public function indexAction() {
    	$loanid = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $userid = $this->userid;
        //若无借款人当前页不合法跳转至错误页
        if($loanid <= 0 || $userid <= 0){
            return $this->redirect('/index/error/error');
        }
        //判断当前借款的状态，
        //2. 成交前只显示借款人信息，不显示投资人信息
        //3. 成交后（状态还款中及以后），列出所有投资人（除自己外其他用户加*）；
        //   若用户不在投资人列表，“您不是投资人或借款人，无法查看该协议”

    }
}