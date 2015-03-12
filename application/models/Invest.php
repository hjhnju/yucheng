<?php
class InvestModel extends BaseModel{
    private $table = 'invest';
    
    public function invest($uid, $loan_id, $amount) {
        
    }
    
    /**
     * 获取用户的投资列表
     * @param integer $mixStatus, -1全部 1审核中 (2投标中 3放款审核 4打款中) 5回款中 6已完成 9失败
     * @param number $page
     * @param number $pagesize
     * @param string $order
     * @return array <pre>(
     *      'page' => $page,
     *      'pagesize' => $pagesize,
     *      'pageall' => $pageall,
     *      'total' => $total,
     *      'list' => $data,
     *  );</pre>
     */
    public function getUserInvests($uid, $mixStatus, $page = 1, $pagesize = 10, $order = 'id desc') {
        $loan_table = 'loan';
        $offset = ($page - 1) * $pagesize;
        $where = "invest.user_id = '$uid'";
        if ($mixStatus != -1 && is_array($mixStatus)) {
            $status = implode(',', $mixStatus);
            $where .= " and loan.status IN ($status)";
        }elseif ($mixStatus != -1 && is_integer($mixStatus)) {
            $status = $mixStatus;
            $where .= " and loan.status = '$status'";
        }
        $sql = "select invest.*,loan.title,loan.start_time,loan.deadline,loan.status as loan_status  from `$this->table` as invest left join `$loan_table` as loan
                on invest.loan_id = loan.id
                where $where order by invest.$order
                limit $offset, $pagesize";
        Base_Log::debug(array('sql'=>$sql));
        $data = $this->db->fetchAll($sql);
   
        $sql = "select count(*) as total from `$this->table` as invest
                left join `$loan_table` as loan
                on invest.loan_id = loan.id
                where $where";
        $total = $this->db->fetchOne($sql);
        
        $pageall = ceil($total / $pagesize);
        
        $list = array(
            'page' => $page,
            'pagesize' => $pagesize,
            'pageall' => $pageall,
            'total' => $total,
            'list' => $data,
        );
        Base_Log::debug($list);
        return $list;
    }
}
