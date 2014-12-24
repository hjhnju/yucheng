<?php
/**
 * 资讯表 列表类
 * @author 
 */
class Infos_List_Infos extends Base_List {
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
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('id', 'status', 'type', 'title', 'content', 'author', 'publish_time', 'create_time');

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
    );

}