<?php
/**
 * 我的投资页
 * @author lilu
 */
class InvestController extends Base_Controller_Response {
	
    CONST PAGEALL    = 10; //10页
    CONST PAGESIZE   = 10; //每次出来10条数据
    CONST BACKING    = 5;
    CONST TENDERING  = 2;
    CONST ENDED      = 6;
    CONST TENDERFAIL = 9;
	public function init(){
        $this->setNeedLogin(false);
        parent::init();
		$this->ajax = true;
	}
	
	/**
	 * 接口/account/invest/index
	 * 渲染入口界面
	 */
	public function indexAction() {
		
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
		$status = InvestController::BACKING;
		//$userId = isset($this->getUserId()) ? $this->getUserId() : 0;

		$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
		//$retData = Invest_Api::getUserInvests($userId, $status, $page, InvestController::PAGESIZE);
	    
		//for test
		//TODO:remove
		$mockdata = array(
			'page'    => $page,
			'pageall' => 10,
            'all'     => 3,
            'list'    => array(
				0 => array(
						'proId'          => 0,
						'investPro'      => '测试用汇款中项目1',
						'annlnterestRate'=> 10,
						'tenderAmt'      => 120.00,
						'deadline'       => 6,
						'tenderTime'     => 1419419678,
						'haveBack'       => 0.20,
						'toBeBack'       => 104.08,
			    ),
				1 => array(
						'proId'          => 1,
						'investPro'      => '测试用汇款中项目2',
						'annlnterestRate'=> 10,
						'tenderAmt'      => 120.00,
						'deadline'       => 6,
						'tenderTime'     => 1419419678,
						'haveBack'       => 0.20,
						'toBeBack'       => 104.08,
				),
				2 => array(
						'proId'          => 2,
						'investPro'      => '测试用汇款中项目3',
						'annlnterestRate'=> 10,
						'tenderAmt'      => 120.00,
						'deadline'       => 6,
						'tenderTime'     => 1419419678,
						'haveBack'       => 0.20,
						'toBeBack'       => 104.08,
				),					
								    
		    ),
		
		);
		$this->output($mockdata);
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
		$status = InvestController::TENDERING;
		//$userId = isset($this->getUserId()) ? $this->getUserId() : 0;
	    $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
	    //$retData = Invest_Api::getUserInvests($userId, $status, $page, InvestController::PAGESIZE);
	    //for test
	    //TODO:remove
	    $mockdata = array(
	    		'page'    => $page,
                'pageall' => 10,
                'all' => 2,
	    		'list'    => array(
	    		    0 => array(
	    		    	'proId'          => 1,
	    		    	'investPro'      => '测试用投标中项目1',
	    		    	'annlnterestRate'=> 12,
	    		    	'tenderAmt'      => 3000.00,
	    		    	'deadline'       => 6,
	    		    	'tenderTime'     => 1419419678,
	    		    	'tenderProgress' => 85,
	    		    ),
	    			1 => array(
	    					'proId'          => 1,
	    					'investPro'      => '测试用投标中项目2',
	    					'annlnterestRate'=> 12,
	    					'tenderAmt'      => 3000.00,
	    					'deadline'       => 6,
	    					'tenderTime'     => 1419419678,
	    					'tenderProgress' => 85,
	    			),    				
	    		),	    
	    );
	    $this->output($mockdata);
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
		//$userId = isset($this->getUserId()) ? $this->getUserId() : 0;
	    $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
	    //$retData = Invest_Api::getUserInvests($userId, $status, $page, InvestController::PAGESIZE);
		//for test
		//TODO:remove
		$mockdata = array(
				'page'    => $page,
                'pageall' => 10,
                'all'     => 2,
				'list'    => array(
				    0 => array(
				    		'proId'          => 1,
				    		'investPro'      => '测试用已结束项目1',
				    		'annlnterestRate'=> 12,
				    		'tenderAmt'      => 3000.00,
				    		'deadline'       => 6,
				    		'tenderTime'     => 1419419678,
				    		'endTime'        => 1419420656,
				    		'totalRetAmt'    => 104.48,
				    		'totalProfit'    => 4.48,
				    ),
					1 => array(
							'proId'          => 1,
							'investPro'      => '测试用已结束项目2',
							'annlnterestRate'=> 12,
							'tenderAmt'      => 3000.00,
							'deadline'       => 6,
							'tenderTime'     => 1419419678,
							'endTime'        => 1419420656,
							'totalRetAmt'    => 104.48,
							'totalProfit'    => 4.48,
				    ),
						
				),
		);
		$this->output($mockdata);
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
		//$userId = isset($this->getUserId()) ? $this->getUserId() : 0;
	    $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
	    //$retData = Invest_Api::getUserInvests($userId, $status, $page, InvestController::PAGESIZE);
		//for test
		//TODO:remove
		$mockdata = array(
				'page'    => $page,
				'pageall' => $this->PAGEALL,
                'all'=>2,
                'list'    => array(
				        0 =>	array(
					 		'proId'          => 1,
					 		'investPro'      => '测试用投标失败项目',
					 		'annlnterestRate'=> 12,
					 		'tenderAmt'      => 3000.00,
					 		'deadline'       => 6,
					 		'tenderTime'     => 1419419678,
					 		'failReason'    => '余额不足导致投标失败',
				        ),     
						1 =>	array(
								'proId'          => 1,
								'investPro'      => '测试用投标失败项目',
								'annlnterestRate'=> 12,
								'tenderAmt'      => 3000.00,
								'deadline'       => 6,
								'tenderTime'     => 1419419678,
								'failReason'    => '余额不足导致投标失败',
						),
				 ),
				 			
		);
		$this->output($mockdata);
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
		//$invest_id = isset($_REQUEST('pro_id')) ? $_REQUEST('pro_id') : 0;
		$retdata = Invest_Api::getRefunds($invest_id);
		$list = array();
		foreach ($retdata as $key=>$value) {
			$list[$key] = array('time'=>$value['create_time'],
			                    'repossPrincipal'=>$value['capital'],
			                    'recePrincipal');
		}
		
		//Invest_Api::getRefunds($invest_id)
		$mockdata = array(
				'page'    => $page,
                'pageall' => 10,
                'all' => 3,
				'list'    => array(
						0 =>    array(
							 	    'time '           => 1419419678,
								    'repossPrincipal' => 0.00,
	                                'repossProfit'    => 0.02,
	                                'recePrincipal'   => 0.00,
	                                'receProfit'      => 0.02,
	                                'paymentStatus'   => 1,
	                                'punitive'        => 0.00,
						        ),
						1 =>    array(
								'time '           => 1419419678,
								'repossPrincipal' => 0.00,
								'repossProfit'    => 0.51,
								'recePrincipal'   => 0.00,
								'receProfit'      => 0.0,
								'paymentStatus'   => 2,
								'punitive'        => 1.00,
						),
						2 =>    array(
								'time '           => 1419419678,
								'repossPrincipal' => 0.00,
								'repossProfit'    => 0.76,
								'recePrincipal'   => 0.00,
								'receProfit'      => 0.00,
								'paymentStatus'   => 3,
								'punitive'        => 0.00,
						),
				    ),
				'data' => array(
				                'repossPrincipal' => 0.00,
				                'repossProfit'    => 1.29,
				                'recePrincipal'   => 0.00,
			            	    'receProfit'      => 0.02,
								'punitive'        => 1.00,
						
						),
				        
				);
		$this->output($mockdata);
		
	}
	
	
}
