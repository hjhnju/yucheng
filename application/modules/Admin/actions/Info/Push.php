<?php
/**
 * 公告列表
 * @author huwei
 *
 */
class PushAction extends Yaf_Action_Abstract {
    public function execute() {
        $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
        $pagesize = isset($_REQUEST['pagesize']) ? $_REQUEST['pagesize'] : 10;
        $arrInfos  = Infos_Api::getAllPost($page, $pagesize);
        foreach ($arrInfos as $key => $val){
            if(1 == $val['type']){
                $arrInfos[$key]['type'] = '公告';
            }else{
                $arrInfos[$key]['type'] = '媒体报道';
            }
        }
        $this->getView()->assign('arrInfo', $arrInfos);
    }
}