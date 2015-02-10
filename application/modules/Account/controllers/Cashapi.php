<?php 
/**
 * 充值提现实现类
 */
class CashapiController extends Base_Controller_Api {
    
    private $transLogic;
    private $huifuid;
    public function init(){
        parent::init();
        $this->transLogic = new Finance_Logic_Transaction();
        $this->huifuid = !empty($this->objUser) ? $this->objUser->huifuid : '';
    }
    
    /**
     * 接口 /account/cashapi/list
     * 获取充值提现列表
     * @param type 0--全部 1--充值  2--提现
     * @param startTime
     * @param endTime
     * @param date 0--今天  1--最近一周  2--1个月  3--3个月  4--半年
     * @param page
     * @return 标准json
     * status 0: 处理成功
     * status 1107:获取充值提现列表失败
     * data
     * {
     *     'page'页码
     *     'pageall':10 总共页码
     *     'all' 数据条数
     *     'list'=
     *         {
     *             'time'      时间
     *             'transType' 交易类型
     *             'serialNo'  交易流水号
     *             'tranAmt'   交易金额
     *             'avalBg'    可用余额
     *         }
     * }
     */
    public function listAction() {
        $queryType = isset($_REQUEST['type']) ? $_REQUEST['type'] : 1;//查询类型  1--全部  2--充值  3--提现
        //时间范围 1：今天 2：最近一周 3：1个月
        $range     = intval($_REQUEST['data']);
        $page      = intval($_REQUEST['page']);//页码
        $pageSize  = 6;
        $startTime = isset($_REQUEST['startTime']) ? intval($_REQUEST['startTime']) : 0;       
        $endTime   = isset($_REQUEST['endTime']) ? intval($_REQUEST['endTime']) : 0;       
        $userid    = $this->userid;
        if($startTime===0 && $endTime===0) {
        	switch ($range) {
        		//今天
        		case 1:
        			$startTime = mktime(0,0,0,date('m'),date('d'),date('Y'));
        			$endTime = time();
        			break;
        		case 2:
        			$startTime = strtotime('-1 week');
        			$endTime = time();
        			break;
        		case 3:
        			$startTime = strtotime('-1 month');
        			$endTime = time();
        			break;
        		case 4:
        			$startTime = strtotime('-2 month');
        			$endTime = time();
        			break;
        		case 5:
        			$startTime = strtotime('-6 month');
        			$endTime = time();
        			break;
        		default:
        			# code...
        			break;
        	}        	 
        }        
        $ret = Finance_Logic_Order::getRecords($userid, $startTime, $endTime, $queryType, 
            $page, $pageSize);
        if(!$ret) {
            Base_Log::error(array(
                'msg'       => '请求充值提现数据',
                'userid'    => $userid,
                'beginTime' => $beginTime,
                'endTime'   => $endTime,
                'queryType' => $queryType,
            ));
            $ret = array(
                'page'     => 0,
                'pagesize' => 0,
                'pageall'  => 0,
                'total'    => 0,
                'list'     => array(),
            );
        }
        return $this->output($ret);
    }
}