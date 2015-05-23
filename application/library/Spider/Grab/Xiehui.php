<?php
/**
 * 我要搜学网
 */
class Spider_Grab_Xiehui extends Spider_Grab_Base{
    /**
     * 构造函数
     * @param $path 存储下载文件的路径
     * @param $url  网站初始地址
     */
    public function __construct(){
        $this->_province = 'guangxi';
    }

    /**
     * @param
     */
    public function run(){
        $base_path = dirname(__FILE__);
        require_once($base_path."/data/Pinyin.class.php");
        $pinyin = new Pinyin();
        $file = file($base_path."/data/data.txt");
        $arrRet = array();
        foreach($file as $val){
            $arr = preg_split("/[\s]+/", $val);
            $arrRet[] = $arr;
        }
        foreach ($arrRet as $val){
            $type  = $pinyin->strtopin($val[2]);
            $type  = preg_replace("/\s+/","",$type);
            if(in_array($type, array('youeryuan', 'zhongxiaoxue', 'zhongxiaoyou', 'xiaoxue', 'chuzhong', 'xiaoxueyouer', 'xiaoyou'))) {
                if(in_array($type, array('youeryuan', 'zhongxiaoyou', 'xiaoxueyouer', 'xiaoyou'))) {
                    $this->_type = 'kindergarten';
                }else {
                    $this->_type = 'middle';
                }
                $arrData = array(
                    'name'    => $val[0],
                    'address' => $val[1],
                    'type'    => $val[2],
                    'nature'  => '民办',
                    'province' => $this->_province,
                    'type_en' => $this->_type,
                );
                if(empty($val[4])){
                    $arrData['phone']     = $val[3];
                    $arrData['president'] = '';
                }
                else{
                    $arrData['president'] = $val[3];
                    $arrData['phone']     = $val[4];
                }
                $schools[$this->_type][] = $arrData;
            }
        }
        $origin_path =  $this->_path;
        foreach ($schools as $key=>$items){
            $this->_path = $origin_path . 'guangxi/' . $key;
            $this->createDirs($this->_path);
            $this->writeIntoFile($items);
        }
    }
}