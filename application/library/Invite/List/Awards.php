<?php
/**
 * 奖励表 列表类
 * @author 
 */
class Invite_List_Awards extends Base_List {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'invite_awards';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'id';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('id', 'userid', 'type', 'status', 'amount', 'create_time');

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
    );

}