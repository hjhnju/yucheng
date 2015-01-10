<?php
/**
 *
 * 公告模块逻辑
 *
 */
class Infos_Logic_Post {

    // 发布状态:1-未发布，2-已发布
    const STATUS_NOTPUB  = 1;
    const STATUS_PUBLISH = 2;

    // 资讯类型:1-官方公告，2-媒体报道
    const TYPE_POST  = 1;
    const TYPE_MEDIA = 2;

    public function __construct() {
    }

    /**
     * 获取公告列表
     * @param
     * @return
     */
    public function getList($page = 1, $pagesize = 10) {
        $list = new Infos_List_Infos();
        $list->setPage($page);
        $list->setPagesize($pagesize);
        // $filters = array('status'=>self::STATUS_PUBLISH, 'type'=>self::TYPE_POST);
        $filters = array();
        $list->setFilter($filters);
        $list->setOrder('publish_time desc');
        $arrRet = $list->toArray();
        foreach ($arrRet['list'] as $key => $val){
            $content    = unserialize($val['content']);
            unset($val['content']);
            $arrRet['list'][$key]['content'] = $content['ctx'];         
        }        
        return $arrRet;
    }

    /**
     * 获取公告详情
     * @param  $postid
     * @return data=>array('title','ctx','author','publish_time') 
     */
    public function getPost($postid){
        $object     = new Infos_Object_Infos($postid);
        $ret        = $object->toArray();
        $content    = unserialize($ret['content']);
        unset($ret['content']);
        $ret['ctx'] = $content['ctx'];
        return $ret;
    }


    /**
     * 保存公告编辑内容
     * @param $arrPost
     * @return $postid || false
     */
    public function save($arrPost){
        $object               = new Infos_Object_Infos();
        $object->title        = isset($arrPost['title']) ? $arrPost['title'] : null;
        $object->author       = isset($arrPost['author']) ? $arrPost['author'] : '兴教贷团队';
        $object->type         = self::TYPE_POST;
        $object->status       = self::STATUS_NOTPUB;
        $object->publishTime  = isset($arrPost['publishtime']) ? intval($arrPost['publishtime']) : null;
        $arrCtx               = array('ctx' => $arrPost['ctx']);
        $object->content      = serialize($arrCtx);

        $ret = $object->save();

        $postid = $ret ? $object->id : false;
        return $postid;
    }

    /**
     * 发布公告
     * @param   $postid 公告id
     * @return  boolean
     */
    public function publish($postid) {
        $postid = intval($postid);

        $object              = new Infos_Object_Infos($postid);
        $object->status      = self::STATUS_PUBLISH;
        $object->publishTime = time();
        $ret                 = $object->save();
        return $ret;
    }

}
