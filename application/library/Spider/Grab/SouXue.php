<?php
/**
 * 我要搜学网
 */
class Spider_Grab_SouXue extends Spider_Grab_Base{
    //网站地址
    private $_url  = '';

    /**
     * 构造函数
     * @param $path 存储下载文件的路径
     * @param $url  网站初始地址
     */
    public function __construct($url, $type, $province){
        $this->_path =  $this->_path . $province . '/' . $type;
        $this->_province = $province;
        $this->_type = $type;
        $this->_url  = $url;
    }

    public function downloadFiles(){
        $this->createDirs($this->_path);
        //获得一个页面的所有学校信息
        $output = $this->getContent($this->_url);
        $page_size = $this->getSubstr('<span.*down.*>共', '</span>', $output);
        for($page=2; $page<=$page_size[0]; $page++) {
            $url =  $this->_url . '&page='.$page;
            echo $url;
            $this->doDownload($url);
        }
        $this->doDownload($this->_url);
    }

    public function doDownload($url) {
        if($url == '') {
            $url = $this->_url;
        }
        //获得一个页面的所有学校信息
        $output = $this->getContent($url);
        $this->getPageSchool($output);
    }

    public function getContent($url){
        if($url != '' && !is_array($url)) {
            $http = Base_Network_Http::instance();
            $output = $http->url($url)->exec();
            $output = mb_convert_encoding($output, "utf-8", "gbk");
        }

        return $output;
    }

    /**
     * @param string $output
     * @return 返回该页面上所有的学校
     */
    public function getPageSchool($output=''){
        $names   = $this->getSubstr('<a.*id="dsadas">', '</a>', $output);
        $natures = $this->getSubstr('性质:<b>', '</b>', $output);
        $weights = $this->getSubstr('属性:<b>', '</b>', $output);
        $addresses = $this->getSubstr('学校地址:<b>', '</b>', $output);
        $phones = $this->getSubstr('联系电话:<b>', '</b>', $output);
        $urls = $this->getSubstr('<h3><a href="(.*)" title*', '</a></h3>', $output);
        $types = $this->getSubstr('类型:<b>', '</b>', $output);

        foreach($names as $key=>$value) {
            $detail =  $this->getProfileSchool($urls[$key]);
            $schools[] = array(
                'name' => trim($value),
                'nature' => $natures[$key],
                'weight' => $weights[$key],
                'address' => $addresses[$key],
                'phone' => $phones[$key],
                'introduce' => $detail['introduce'],
                'regulation' => $detail['regulations'],
                'type' => $types[$key],
                'province' => $this->_province,
                'type_en'  => $this->_type,
            );
        }
        $this->writeIntoFile($schools);
    }

    public function getSubstr($strFrom, $strTo, $string){
        $pattern = "@$strFrom(.*)$strTo@Uis";
        preg_match_all($pattern, $string, $out);

        return $out[1];
    }

    public function getProfileSchool($url=''){
        if($url != '') {
            $output = $this->getContent($url);
            //$website = $this->getSubstr('网站:<span id=.*>', '</span>', $output);  //可以不用取了，js添加的

            $introduce_url = $this->getSubstr('<div.*school_kz fr.*><a href="(.*)"', '阅读全文</a>', $output);
            $introduce = $this->getIntroduceInfo($introduce_url);

            $regulations_url = $this->getSubstr('<div.*school_atr.*>招生简章</div>.*<div.*school_t.*>.*<a href="(.*)"', '</a>', $output);
            $regulations_content = $this->getContent($regulations_url[0]);
            $regulations = $this->getSubstr('<div class="nr_m">', '<div.*nr_ws_nr.*>', $regulations_content);

            $detail = array(
                'introduce' => $introduce,
                'regulations' => $regulations[0]
            );
        }

        return $detail;
    }

    public function getIntroduceInfo($url=''){
        $output = $this->getContent($url);
        $introduce = $this->getSubstr('<div class="nr_m">.*<p>', '</p>', $output);

        return $introduce[0];
    }

}