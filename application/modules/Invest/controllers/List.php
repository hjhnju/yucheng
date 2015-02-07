<?php
/**
 * 投标记录
 */
class ListController extends Base_Controller_Api {

    protected $needLogin = true;
        
    public function indexAction() {
        $loanId = intval($_REQUEST['id']);
        if (empty($loanId)) {
            $this->ajaxError(Base_RetCode::PARAM_ERROR);
        }
        
        $list = Invest_Api::getLoanInvests($loanId);

        //用户名加星
        $ownUserName = $this->objUser->name;
        $retList = array();
        $retList['list'] = array();
        foreach($list['list'] as $arrVal){
            $arrRow = array();
            if($arrVal['name'] !== $ownUserName){
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
        $retList['totol'] = $list['total'];

        $this->ajax($retList);
    }
}
