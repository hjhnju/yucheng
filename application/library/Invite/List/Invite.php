<?php
/**
 * 邀请表 列表类
 * @author 
 */
class Invite_List_Invite extends Base_List {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'invite';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'id';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('id', 'userid', 'inviterid', 'create_time');

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
    );

}