<?php
/**
 *
 * 公告模块逻辑
 *
 */
class Infos_Logic_Post {

    // 发布状态:1-已发布,2-未发布
    const STATUS_PUBLISH = 1;
    const STATUS_NOTPUB  = 2;

    // 资讯类型:1-官方公告，2-媒体报道，3-还款公告
    const TYPE_PLATPOST  = 1;
    const TYPE_MEDIA = 2;
    const TYPE_REFUNDPOST = 3;

    public function __construct() {
    }

    /**
     * 获取已发布公告列表
     * @param
     * @return
     */
    public function getList($page = 1, $pagesize = 10,$strType, $status = 1) {
        $list = new Infos_List_Infos();
        $list->setPage($page);
        $list->setPagesize($pagesize);
        //设置查询字段
        $list->setFields(array('title','create_time','id','abstract','author','type','status'));
        $filters = array('status'=>$status, 'type'=>$this->getInfoType($strType));
        $list->setFilter($filters);
        $list->setOrder('publish_time desc');
        $arrRet = $list->toArray();
        foreach ($arrRet['list'] as $key => $val){
            $arrRet['list'][$key]['content'] = $val['abstract']; 
        }        
        return $arrRet;
    }
    
    /**
     * TODO:获取所有公告列表 包括三种类型所有状态的公告
     * @param
     * @return array
     */
	public function getAllList($page=1, $pagesize = 10){
		
		$list = new Infos_List_Infos();
		$list->setPage($page);
		$list->setPagesize($pagesize);
		$list->setFields(array('title','create_time','id','abstract','author','type','status'));
// 		$list->setOrder('publish_time aesc');
		$arrRet = $list->toArray();
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
    public function save($arrPost,$id = 0){
        if(!empty($id)){
            $object               = new Infos_Object_Infos($id);
            $object->title        = isset($arrPost['title']) ? $arrPost['title'] : null;
            $object->abstract     = isset($arrPost['abstract']) ? $arrPost['abstract'] : null;
            $object->author       = isset($arrPost['author']) ? $arrPost['author'] : '兴教贷团队';
            $object->type         = isset($arrPost['type']) ? $arrPost['type'] :self::TYPE_POST;
            $object->status       = self::STATUS_NOTPUB;
            $object->publishTime  = isset($arrPost['publishtime']) ? intval($arrPost['publishtime']) : null;
            $arrCtx               = array('ctx' => $arrPost['ctx']);
            $object->content      = serialize($arrCtx);
            
            $ret = $object->save();
            
            $postid = $ret ? $object->id : false;
            return $postid;
        }
        $object               = new Infos_Object_Infos();
        $object->title        = isset($arrPost['title']) ? $arrPost['title'] : null;
        $object->abstract     = isset($arrPost['abstract']) ? $arrPost['abstract'] : null;
        $object->author       = isset($arrPost['author']) ? $arrPost['author'] : '兴教贷团队';
        $object->type         = isset($arrPost['type']) ? $arrPost['type'] :self::TYPE_POST;
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
    
    /**
     * 删除公告
     * @param int $postid
     * @return boolean
     */
    public function del($postid){
        $postid = intval($postid);       
        $object              = new Infos_Object_Infos($postid);
        $ret = $object->erase();
        return $ret;
    }
    
    /**
     * 取得公告的类型
     * @param str $strType
     * @return string
     */
    private function getInfoType($strType){
		switch ($strType){
			case  'platPost':
			  return self::TYPE_PLATPOST;
			case  'refundPost':
		      return self::TYPE_REFUNDPOST;
		    case  'media':
		      return self::TYPE_MEDIA;
		}	
    }
}
