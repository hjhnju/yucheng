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
    public function index_v1Action() {

        //投资列表
        $list = Invest_Api::getInvestList(1, 5);
        $list = $list['list'];
        //最新平台公告
        $platPost = Infos_Api::getNewPost(1,5,'platPost');
        //最新还款公告
        $refundPost = Infos_Api::getNewPost(1,5,'refundPost');
        //构建数据
        $arrData               = array();
        $arrData['list']       = $list;
        $arrData['platPost']   = $platPost;
        $arrData['refundPost'] = $refundPost;

        //合并获取最新公告 + 切分title
        $arrData['newpost']    = array();
        foreach ($arrData['platPost'] as $key => $val){
            $title = $val['title'];
            $arrData['platPost'][$key]['title'] = $this->cutstr($title, 29);
            if($val['publish_time'] > strtotime("-7 day")){
                $val['title'] = $this->cutstr($title, 60);
                $arrData['newpost'][] = $val; 
            }
        }
        foreach ($arrData['refundPost'] as $key => $val){
            $title = $val['title'];
            $arrData['refundPost'][$key]['title'] = $this->cutstr($title, 40);
            if($val['publish_time'] > strtotime("-7 day")){
                $val['title'] = $this->cutstr($title, 60);
                $arrData['newpost'][] = $val; 
            }
        }
        if(empty($arrData['newpost'])){
            $arrData['newpost'][] = $arrData['platPost'][0];
        }

        //添加level_name_upperCase   level_name即将值转化为大写然后添加到数组
        foreach ($arrData['list'] as $key => $val){
        	$type = $val['level_name'];
        	$type = strtoupper($type);
        	$arrData['list'][$key]['level_name_upperCase'] = $type;
        }
        //添加amount_rest格式化 如5000->5,000
        foreach ($arrData['list'] as $key => $val){
        	$rest = $val['amount_rest'];
        	$rest = number_format($rest);
        	$arrData['list'][$key]['amount_rest'] = $rest;
        }
        //assign
        $this->getView()->assign('data', $arrData);
    }
    
     /**
     * 融资首页
     */
    public function indexAction() {

        
    }

    /*
     * TODO：文字截取并加点
     * param str $string 要截取的字符串 int $length 截取的长度
     */
    function cutstr($string, $length) {
    	preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $string, $info);
    	$j = 0;
    	$wordscut = '';
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
