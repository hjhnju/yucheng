<?php
/**
 * 投标记录
 */
class ListController extends Base_Controller_Api {

    protected $needLogin = true;
        
    public function indexAction() {
        $loanId   = intval($_REQUEST['id']);
        $page     = intval($_REQUEST['page']);
        $pagesize = 15;
        if (empty($loanId)) {
            $this->ajaxError(Base_RetCode::PARAM_ERROR);
        }
        
        $list = Invest_Api::getLoanInvests($loanId, $page, $pagesize);

        //用户名加星
        $ownUserid = $this->objUser->userid;
        $retList = array();
        $retList['list'] = array();
        foreach($list['list'] as $arrVal){
            $arrRow = array();
            if($arrVal['user_id'] !== $ownUserid){
                $arrRow['name'] = Base_Util_String::starUserName($arrVal['name']);
            }else{
                $arrRow['name'] = $arrVal['name'];
            }
            $arrRow['create_time'] = $arrVal['create_time'];
            $arrRow['amount'] = $arrVal['amount'];
            $retList['list'][] = $arrRow;
        }
        $retList['page'] = $list['page'];
        $retList['pagesize'] = $list['pagesize'];
        $retList['total'] = $list['total'];
        $retList['pageall'] = $list['pageall'];

        $this->ajax($retList);
    }
}
