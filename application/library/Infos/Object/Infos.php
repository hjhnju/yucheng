<?php
/**
 * 资讯表
 * @author jiangsongfang
 */
class Infos_Object_Infos extends Base_Object {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'infos';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'id';

    /**
     * 类名
     * @var string
     */
    const CLASSNAME = 'Infos_Object_Infos';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('id', 'status', 'type', 'title', 'abstract', 'content', 'author', 'publish_time', 'create_time', 'update_time');

    /**
     * 字段与属性隐射关系
     * @var array
     */
    public $properties = array(
        'id'          => 'id',
        'status'      => 'status',
        'type'        => 'type',
        'title'       => 'title',
        'abstract'    => 'abstract',
        'content'     => 'content',
        'author'      => 'author',
        'publish_time'=> 'publishTime',
        'create_time' => 'createTime',
        'update_time' => 'updateTime',
    );

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
        'id'          => 1,
        'status'      => 1,
        'type'        => 1,
        'publish_time'=> 1,
        'create_time' => 1,
        'update_time' => 1,
    );

    /**
     * @param array $data
     * @return Infos_Object_Infos
     */
    public static function init($data) {
        return parent::initObject(self::CLASSNAME, $data);
    }

    /**
     * 自增id
     * @var integer
     */
    public $id;

    /**
     * 发布状态:1-未发布，2-已发布
     * @var integer
     */
    public $status;

    /**
     * 资讯类型:1-官方公告，2-媒体报道
     * @var integer
     */
    public $type;

    /**
     * 标题
     * @var string
     */
    public $title;

    /**
     * 
     * @var string
     */
    public $abstract;

    /**
     * 序列化内容
     * @var string
     */
    public $content;

    /**
     * 作者
     * @var string
     */
    public $author;

    /**
     * 发布时间
     * @var integer
     */
    public $publishTime;

    /**
     * 创建时间
     * @var integer
     */
    public $createTime;

    /**
     * 修改时间
     * @var integer
     */
    public $updateTime;

}
