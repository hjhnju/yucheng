<?php
/**
 * 首页
 */
class IndexController extends Base_Controller_Page {
	//初始化
    public function init(){
        $this->setNeedLogin(false);
        parent::init();
    }
    /**
     * param null
     * @assign array $arrData 
     * $arrData('list','platPost','refundPost','media')
     * 返回数组中包含最新投资列表（list） 最新平台公告 最新媒体公告（page：1，pagesize：4）
     */
    public function indexAction() {

        //投资列表
        $list = Invest_Api::getInvestList(1, 5);
        $list = $list['list'];
        //最新平台公告
        $platPost = Infos_Api::getNewPost(1,4,'platPost');
        //最新还款公告
        $refundPost = Infos_Api::getNewPost(1,4,'refundPost');
        //获取新闻
        $media   = Infos_Api::getNewPost(1,4,'media');
        //构建数据
        $arrData               = array();
        $arrData['list']       = $list;
        $arrData['platPost']   = $platPost;
        $arrData['refundPost'] = $refundPost;
        $arrData['media']      = $media;
        //加入截取后标题放入arryData
        foreach ($arrData['media'] as $key => $val){
        	$title = $val['title'];
        	$titleCut = $this->cutstr($title, 47);
        	$arrData['media'][$key]['title_cut'] = $titleCut;
        }
        foreach ($arrData['platPost'] as $key => $val){
        	$title = $val['title'];
        	$titleCut = $this->cutstr($title, 47);
        	$arrData['platPost'][$key]['title_cut'] = $titleCut;
        }
        foreach ($arrData['refundPost'] as $key => $val){
        	$title = $val['title'];
        	$titleCut = $this->cutstr($title, 47);
        	$arrData['refundPost'][$key]['title_cut'] = $titleCut;
        }
        //添加level_name_upperCase   level_name即将值转化为大写然后添加到数组
        foreach ($arrData['list'] as $key => $val){
        	$type = $val['level_name'];
        	$type = strtoupper($type);
        	$arrData['list'][$key]['level_name_upperCase'] = $type;
        }
        //assign
        $this->getView()->assign('data', $arrData);
    }
    
    /*
     * TODO：文字截取并加点
     * param str $string 要截取的字符串 int $length 截取的长度
     */
    function cutstr($string, $length) {
    	preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $string, $info);
    	for($i=0; $i<count($info[0]); $i++) {
    		$wordscut .= $info[0][$i];
    		$j = ord($info[0][$i]) > 127 ? $j + 2 : $j + 1;
    		if ($j > $length - 3) {
    			return $wordscut." ...";
    		}
    	}
    	return join('', $info[0]);
    }
}
