<?php
/**
 * 联系我们
 */
class ContactController extends Base_Controller_Page {

    public function init(){
        $this->setNeedLogin(true);
        parent::init();
    }
    
    /**
     * 借款协议
     */
    public function loanAction() {
    	$loanid = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $userid = $this->userid;
        //若无借款人当前页不合法跳转至错误页
        if($loanid <= 0 || $userid <= 0){
            return $this->redirect('/index/error/error');
        }
        //判断当前借款的状态
        //成交前只显示借款人信息
        
        //成交后（状态还款中及以后），列出所有出借人（除自己外加*）

    }
}