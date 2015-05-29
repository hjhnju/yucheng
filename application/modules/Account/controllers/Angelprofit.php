<?php
/**
 * 天使的收益页
 * @author huwei
 */
class AngelprofitController extends Base_Controller_Page {

    CONST PAGESIZE   = 6; //每次出6条数据
	public function init(){
        parent::init();
        $this->userInfoLogic = new Account_Logic_UserInfo();
        $this->ajax = true;
	}
	
	/**
	 * 接口/account/invest/index
	 * 渲染入口界面
	 */
	public function indexAction() {
		$userInfo = $this->userInfoLogic->getUserInfo($this->objUser);	
		$objShare = new Invest_List_Share();
		$objShare->setFilter(array('to_userid'=>$this->objUser->userid));
		$arrRet = $objShare->toArray();
		$money  = 0.00;
		$arrPerson = array();
		foreach ($arrRet['list'] as $val){
		    $arrPerson[]  = $val['from_userid'];
		    $money       += $val['income'];
		}	
		$arrPerson = array_unique($arrPerson);
		$this->getView()->assign('userinfo',$userInfo);
		$this->getView()->assign('money',$money);
		$this->getView()->assign('person',count($arrPerson));
	}
	
	/**
	 * 获取天使的收益信息
	 */
	public function listAction() {    
		$status     = Invest_Type_InvestStatus::REFUNDING;
		$userid     = $this->userid;
		$page       = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
		
		$objShare = new Invest_Object_Share();
		$objShare->fetch(array('to_userid'=>$userid));
		if(!empty($objShare->id)){
		    $obj = new Invest_List_Refund();	
		    $obj->setFilter(array('user_id'=>$userid));	
		    $obj->setPagesize(self::PAGESIZE);
		    $obj->setPage($page);
		    $backingRet = $obj->toArray();
		    foreach ($backingRet['list'] as $index => $list){
		        $loan = Loan_Api::getLoanInfo($list['loan_id']);
		        $temp[$index]['id']      = $list['invest_id'];
		        $temp[$index]['loan_id'] = $list['loan_id'];
		        $temp[$index]['annlnterestRate'] = $list['tenderAmt'];
		        $temp[$index]['title'] = $loan['title'];
		        $temp[$index]['interest'] = $loan['interest'];
		        $temp[$index]['amount'] = 0;
		        $temp[$index]['create_time'] = $list['create_time'];
		        $temp[$index]['user_id'] = $objShare->fromUserid;
		        $temp[$index]['income'] = $list['amount'];
		        $temp[$index]['status']  = $status;
		    }
		    $backingRet['list']  = $temp;
		}
		
		$objShare = new Invest_List_Share();
		$temp = array();
		$objShare->setFilter(array('to_userid'=>$userid));
		$obj->setPagesize(PHP_INT_MAX);
		$arrShare = $objShare->getData();
		if(!empty($arrShare)){
		    foreach ($arrShare as $index => $list){
		        $loan = Loan_Api::getLoanInfo($list['loan_id']);
		        $temp[$index]['id']      = $list['invest_id'];
		        $temp[$index]['loan_id'] = $list['loan_id'];
		        $temp[$index]['annlnterestRate'] = $list['rate'];
		        $temp[$index]['title'] = $loan['title'];
		        $temp[$index]['interest'] = $loan['interest'];
		        $temp[$index]['amount'] = 0;
		        $temp[$index]['create_time'] = $list['create_time'];
		        $temp[$index]['user_id'] = $list['from_userid'];
		        $temp[$index]['income'] = $list['income'];
		        $temp[$index]['status']  = $loan['status'];
		    }		   
		}
		$backingRet['pageall'] += floor(count($temp)/self::PAGESIZE);
		$backingRet['all']     += count($temp);
		if(count($backingRet['list']) < self::PAGESIZE){
		    $arrTemp = array_slice($temp,($page-1)*self::PAGESIZE,self::PAGESIZE-count($backingRet['list']));
		    if(empty($backingRet['list'])){
		        $backingRet['list'] = $arrTemp;
		    }else{
		        $backingRet['list'] = array_merge($backingRet['list'],$arrTemp);
		    }
		    
		}

		$listRet    = array();
		$list       = $backingRet['list'];
        if(empty($list)) {
        	$ret = array(
        		'page'    => $page,
        		'pageall' => $backingRet['pageall'],
        		'all'     => $backingRet['total'],
        		'list'    => $list,
        	);
        	$this->output($ret);
        	return ;
        }
 	    foreach ($list as $key => $value) {
 	        $user = User_Api::getUserObject($value['user_id']);
 	        $share = new Invest_Object_Share();
 	        $share->fetch(array('invest_id'=>$value['id'],'from_userid'=>$value['user_id']));
			$listRet[$key]['invest_id']       = $value['id'];//invest_id	
			$listRet[$key]['name']            = Base_Util_String::starUsername($user->name);  
			$backingRefund                    = Account_Logic_Repayplan::getRepayplan($value['id'],$value['user_id']);
			$total                            = $backingRefund['total'];	        
			$listRet[$key]['proId']           = $value['loan_id'];
			$loanInfo                         = Loan_Api::getLoanDetail($value['loan_id']);
			$listRet[$key]['investPro']       = $value['title'];
			$listRet[$key]['annlnterestRate'] = $value['interest'];
			$listRet[$key]['interest']        = $share->rate;
			$listRet[$key]['tenderAmt']       = $value['amount'];
			$listRet[$key]['deadline']        = $loanInfo['duration_name'];
			$listRet[$key]['tenderTime']      = $value['create_time'];
			$listRet[$key]['haveBack']        = $value['income'];
			$listRet[$key]['toBeBack']        = 0;
			$listRet[$key]['status']          = $value['status'];		 
 	    }  
	    $ret = array(
			'page'    => $page,
			'pageall' => $backingRet['pageall'],
			'all'     => $backingRet['total'],
			'list'    => $listRet,
	    );
	    $this->output($ret);
        return ;
	}
	
	/**
	 * 还款计划
	 */
	public function repayplanAction() {
	    $investId = intval($_REQUEST['invest_id']);
	    $userid   = $this->userid;
	    $ret = Account_Logic_Repayplan::getRepayplan($investId,$userid);
	    $this->output($ret);
	    return ;
	}
}
