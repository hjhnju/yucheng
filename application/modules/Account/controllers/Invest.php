<?php
/**
 * 我的投资页
 * @author lilu
 */
class InvestController extends Base_Controller_Page {

    CONST PAGESIZE   = 10; //每次出来10条数据
    CONST BACKING    = 5;
    CONST TENDERING  = 2;
    CONST ENDED      = 6;
    CONST TENDERFAIL = 9;
	public function init(){
        $this->setNeedLogin(false);
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
		$this->getView()->assign('userinfo',$userInfo);
		
	}
	
	/**
	 * 接口 /account/invest/backing
	 * 调用投资模块Invest_Api获取投资项目列表数据--拉取回款中
	 * @param page begin from 1
	 * @return 标准json
	 * status 0 处理成功
	 * status 1108 获取投资列表失败
	 * data=
	 * { 
	 *     'page'页码
	 *     'pageall':10 总共页码 
	 *     'all' 数据条数
	 *     'list'=
	 *      {
	 *          'proId' 投资项目id
	 *          'investPro' 投资项目名称
	 *          'annlnterestRate' 年利率
	 *          'tenderAmt' 投标金额
	 *          'deadline' 期限
	 *          'tenderTime'投标时间
	 *          'haveBack' 已回款
	 *          'toBeBack' 待回款  
     *       }    
	 *  }
	 * 
	 */
	public function backingAction() {
		$status = self::BACKING;
        $userid = $this->userid;
		$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
		$backingRet = Invest_Api::getUserInvests($userid, $status, $page, self::PAGESIZE);
		//var_dump($backingRet);die;
		$listRet = array();
        $list = $backingRet['list'];
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
	        $listRet[$key]['proId'] = $value[''];
	    	$listRet[$key]['investPro'] = $value[''];
	    	$listRet[$key]['annlnterestRate'] = $value[''];
	    	$listRet[$key]['tenderAmt'] = $value[''];
	    	$listRet[$key]['deadline'] = $value[''];
	    	$listRet[$key]['tenderTime'] = $value[''];
	    	$listRet[$key]['haveBack'] = $value[''];
	    	$listRet[$key]['toBeBack'] = $value[''];	    		    	
	    }
	    $ret = array(
	    	'page' => $page,
	    	'pageall' => $backingRet['pageall'],
	    	'all' => $backingRet['total'],
	    	'list' => $listRet,
	    );
	    $this->output($ret);
	    return ;
	}
	
	/**
	 * 接口 /account/invest/tendering
	 * 调用投资模块Invest_Api获取投资项目列表数据--拉取投标中
	 * @param page begin from 1
	 * status 0 处理成功
	 * status 1108 获取投资列表失败
	 * data=
	 * {
	 *     'page'页码
	 *     'pageall':10 总共页码
	 *     'all' 数据条数
	 *     'list’
	 *      {
	 *         'proId' 投资项目id
	 *         'investPro' 投资项目名称
	 *         'annlnterestRate' 年利率
	 *         'tenderAmt' 投标金额
	 *         'deadline' 期限
	 *         'tenderTime'投标时间
	 *         'tenderProgress' 进度
	 *      }
	 *  }
	 */	
	public function  tenderingAction() {
		$status = self::TENDERING;
        $userid = $this->userid;
		$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
	    $tenderingRet = Invest_Api::getUserInvests($userid, $status, $page, self::PAGESIZE);
	    $listRet = array();
	    $list = $tenderingRet['list'];
	    if(empty($list)) {
	    	$ret = array(
	    		'page'    => $page,
	    		'pageall' => $tenderingRet['pageall'],
	    		'all'     => $tenderingRet['total'],
	    		'list'    => $list,
	    	);
	    	$this->output($ret);
	    	return ;
	    }
	    foreach ($list as $key => $value) {
	    	$listRet[$key]['proId'] = $value[''];
	    	$listRet[$key]['investPro'] = $value[''];
	    	$listRet[$key]['annlnterestRate'] = $value[''];
	    	$listRet[$key]['tenderAmt'] = $value[''];
	    	$listRet[$key]['deadline'] = $value[''];
	    	$listRet[$key]['tenderTime'] = $value[''];
	    	$listRet[$key]['tenderProgress'] = $value[''];
	    }
	    $ret = array(
	    		'page' => $page,
	    		'pageall' => $backingRet['pageall'],
	    		'all' => $backingRet['total'],
	    		'list' => $listRet,
	    );
	    $this->output($ret);
	    return ;
	}
	
	/**
	 * 接口/account/invest/ended 
	 * 调用投资模块Invest_Api获取投资项目列表数据--拉取已结束
	 * @param page begin from 1
	 * status 0 处理成功
	 * status 1108 获取投资列表失败
	 * data=
	 * { 
	 *     'page'页码
	 *     'pageall':10 总共页码 
	 *     'all' 数据条数
	 *     'list'=
	 *      { 
	 *         'proId' 投资项目id
	 *         'investPro' 投资项目名称
	 *         'annlnterestRate' 年利率
	 *         'tenderAmt' 投标金额
	 *         'deadline' 期限
	 *         'tenderTime'投标时间
	 *         'endTime' 合同结束时间
	 *         'totalRetAmt' 总回款
	 *         'totalProfit'总收益
	 *      }
	 * }
	 */
	public function  endedAction() {
		$status = InvestController::ENDED;
		$userid = $this->userid;
	    $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
	    $endedRet = Invest_Api::getUserInvests($userid, $status, $page, self::PAGESIZE);
	    $listRet = array();
	    $list = $endedRet['list'];
	    if(empty($list)) {
	    	$ret = array(
	    		'page' => $page,
	    		'pageall' => $endedRet['pageall'],
	    		'all' => $endedRet['total'],
	    		'list' => $list,
	    	);
	    	$this->output($ret);
	    	return ;
	    }
	    foreach ($list as $key => $value) {
	    	$listRet[$key]['proId'] = $value[''];
	    	$listRet[$key]['investPro'] = $value[''];
	    	$listRet[$key]['annlnterestRate'] = $value[''];
	    	$listRet[$key]['tenderAmt'] = $value[''];
	    	$listRet[$key]['deadline'] = $value[''];
	    	$listRet[$key]['tenderTime'] = $value[''];
	    	$listRet[$key]['endTime'] = $value[''];
	    	$listRet[$key]['totalRetAmt'] = $value[''];
	    	$listRet[$key]['totalProfit'] = $value[''];
	    }
	    $this->output($listRet);
	    return ;
	}
	
	/**
	 * 接口/account/invest/tenderFail
	 * 调用投资模块Invest_Api获取投资项目列表数据--拉取投标失败
	 * @param page begin from 1
	 * status 0 处理成功
	 * status 1108 获取投资列表失败
	 * data=
	 * {
	 *     'page'页码
	 *     'pageall':10 总共页码 
	 *     'all' 数据条数
	 *     'list'=
	 *      { 
	 *         'proId' 投资项目id
	 *         'investPro' 投资项目名称
	 *         'annlnterestRate' 年利率
	 *         'tenderAmt' 投标金额
	 *         'deadline' 期限
	 *         'tenderTime'投标时间
     *         'failReason' 失败原因
	 *      }
	 *  }
	 * 
	 */
	public function tenderFailAction() {
		$status = InvestController::TENDERFAIL;
		$userid = $this->userid;
	    $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
	    $tenderFailRet = Invest_Api::getUserInvests($userid, $status, $page, self::PAGESIZE);
	    $listRet = array();
	    $list = $tenderFailRet['list'];
	    if(empty($list)) {
	    	$ret = array(
	    		'page' => $page,
	    		'pageall' => $tenderFailRet['pageall'],
	    		'all' => $tenderFailRet['total'],
	    		'list' => $list,
	    	);
	    	$this->output($ret);
	    	return ;
	    }
	    foreach ($list as $key => $value) {
	    	$listRet[$key]['proId'] = $value[''];
	    	$listRet[$key]['investPro'] = $value[''];
	    	$listRet[$key]['annlnterestRate'] = $value[''];
	    	$listRet[$key]['tenderAmt'] = $value[''];
	    	$listRet[$key]['deadline'] = $value[''];
	    	$listRet[$key]['tenderTime'] = $value[''];
	    	$listRet[$key]['failReason'] = $value[''];
	    }
	    $ret = array(
	    	'page' => $page,
	    	'pageall' =>$tenderFailRet['pageall'],
	    	'all' => $tenderFailRet['total'],
	    	'list' => $listRet,
	    );
	    $this->output($ret);
	    return ;
	}
	
	/**
	 * 接口 /account/invest/repayplan
	 * 调用投资模块Invest_Api获取某个项目的还款计划
	 * @param $invest_id 项目ID
	 * @return 标准json
	 * status 0 处理成功
	 * status 1109
	 * data =
	 * { 
	 *     { 
	 *         'invester' 投资人用户名
	 *         'list'={ 
	 *             'time' 时间
	 *             'repossPrincipal' 待收本金
	 *             'repossProfit' 待收收益
	 *             'recePrincipal' 已收本金
	 *             'receProfit'已收收益
	 *             'paymentStatus' 还款状态 
	 *             'punitive' 罚息
	 *         }
	 *         'data'={ 
	 *             'repossPrincipal' 待收本金
	 *             'repossProfit' 待收收益
	 *             'recePrincipal' 已收本金
	 *             'receProfit'已收收益
	 *             'punitive' 罚息
	 *         }         
	 *     }
	 *  }
	 */
	public function repayplanAction() {
		$invest_id = $_REQUEST('pro_id');
		$retData = Invest_Api::getRefunds($invest_id);
		$list = array();
		$data = array();
		if(empty($retData)) {
			$ret = array(
				'list' => array(),
				'data' => array(),
			);
			$this->output($ret);
			return ;
		}
		
		foreach ($retData as $key=>$value) {
			$list[$key]['time'] = $value[''];
			$list[$key]['repossPrincipal'] = $value[''];
			$list[$key]['repossProfit'] = $value[''];
			$list[$key]['recePrincipal'] = $value[''];
			$list[$key]['receProfit'] = $value[''];
			$list[$key]['paymentStatus'] = $value[''];
			$list[$key]['punitive'] = $value[''];
		}
		
		$repossPrincipal = 0.00;
		$repossProfit = 0.00;
		$recePrincipal = 0.00;
		$receProfit = 0.00;
		$punitive = 0.00;
		foreach ($list as $key => $value) {
			$repossPrincipal += $value['repossPrincipal'];
			$repossProfit += $value['repossProfit'];
			$recePrincipal += $value['recePrincipal'];
			$receProfit += $value['receProfit'];
			$punitive += $value['punitive'];
		}
		$data = array(
			'repossPrincipal' => $repossPrincipal,
			'repossProfit'    => $repossProfit,
			'recePrincipal'   => $recePrincipal,
			'receProfit'      => $receProfit,
			'punitive'        => $punitive,
		);
		$ret = array(
			'list' => $list,
			'data' => $data,
		);
		$this->output($ret);
		return ;		
	}	
}
