<?php
/**
 *  列表类
 * @author 
 */
class User_List_Info extends Base_List {
    /**
     * 数据表名
     * @var string
     */
    protected $table = 'user_info';

    /**
     * 主键
     * @var string
     */
    protected $prikey = 'userid';

    /**
     * 对象包含的所有字段
     * @var array
     */
    protected $fields = array('userid', 'usertype', 'realname', 'certificate_type', 'certificate_content', 'headurl', 'huifuid', 'create_time', 'update_time');

    /**
     * 整数类型的字段
     * @var array
     */
    protected $intProps = array(
    );

}