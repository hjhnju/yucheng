<?php
class InvestModel extends BaseModel{
    private $table = 'invest';
    
    public function invest($uid, $loan_id, $amount) {
        
    }
    
    /**
     * 获取用户的投资列表
     * @param integer $status
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
    public function getUserInvests($uid, $status, $page = 1, $pagesize = 10, $order = 'id desc') {
        $loan_table = 'loan';
        $offset = ($page - 1) * $pagesize;
        $where = "invest.user_id = '$uid'";
        if ($status != -1) {
            $where.= " and loan.status='$status'";
        }
        $sql = "select invest.*, loan.title,loan.start_time,loan.deadline, loan.status as loan_status  from `$this->table` as invest
                left join `$loan_table` as loan
                on invest.loan_id = loan.id
                where $where order by invest.$order
                limit $offset, $pagesize";
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
        return $list;
    }
}