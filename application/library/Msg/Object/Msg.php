<?php
/**
 * 消息
 * @author jiangsongfang
 */
class Msg_Object_Msg extends Base_Object {
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
     * 类名
     * @var string
     */
    const CLASSNAME = 'Msg_Object_Msg';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('mid', 'sender', 'receiver', 'title', 'content', 'status', 'create_time', 'update_time', 'read_time');

    /**
     * 字段与属性隐射关系
     * @var array
     */
    public $properties = array(
        'mid'         => 'mid',
        'sender'      => 'sender',
        'receiver'    => 'receiver',
        'title'       => 'title',
        'content'     => 'content',
        'status'      => 'status',
        'create_time' => 'createTime',
        'update_time' => 'updateTime',
        'read_time'   => 'readTime',
    );

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
     * @param array $data
     * @return Msg_Object_Msg
     */
    public static function init($data) {
        return parent::initObject(self::CLASSNAME, $data);
    }

    /**
     * 消息ID
     * @var integer
     */
    public $mid;

    /**
     * 发送人
     * @var integer
     */
    public $sender;

    /**
     * 接收人
     * @var integer
     */
    public $receiver;

    /**
     * 标题
     * @var string
     */
    public $title;

    /**
     * 消息内容
     * @var string
     */
    public $content;

    /**
     * 状态 0未读 1已读 -1删除
     * @var integer
     */
    public $status;

    /**
     * 发送时间
     * @var integer
     */
    public $createTime;

    /**
     * 更新时间
     * @var integer
     */
    public $updateTime;

    /**
     * 阅读时间
     * @var integer
     */
    public $readTime;

}
