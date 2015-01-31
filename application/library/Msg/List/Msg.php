<?php
/**
 * 消息 列表类
 * @author jiangsongfang
 */
class Msg_List_Msg extends Base_List {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'msg';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'mid';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('mid', 'sender', 'receiver', 'title', 'type', 'content', 'status', 'create_time', 'update_time', 'read_time');

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'mid'         => 1,
        'sender'      => 1,
        'receiver'    => 1,
        'status'      => 1,
        'create_time' => 1,
        'update_time' => 1,
        'read_time'   => 1,
    );

    /**
     * 获取数据的对象数组
     * @return array|Msg_Object_Msg[]
     * 返回的是一个数组，每个元素是一个Loan_Object_Attach对象
     */
    public function getObjects() {
        return parent::getObjects('Msg_Object_Msg');
    }

}