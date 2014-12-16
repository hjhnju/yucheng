<?php
/**
 * 邀请奖励表 列表类
 * @author 
 */
class Awards_List_Invite extends Base_List {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'awards_invite';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'id';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('id', 'userid', 'inviterid', 'status', 'amount', 'memo', 'create_time');

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
    );

}