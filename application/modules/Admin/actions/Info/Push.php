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
			switch($val['type']){
				case 1:
					$arrInfos[$key]['type'] = '平台公告';
					break;
				case 2:
					$arrInfos[$key]['type'] = '媒体报道';
					break;
				case 3:
					$arrInfos[$key]['type'] = '还款公告';
					break;	
			}
        }
        $this->getView()->assign('arrInfo', $arrInfos);
    }
}