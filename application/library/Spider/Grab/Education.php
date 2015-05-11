<?php
/**
 * 北京教委网站
 */
class Spider_Grab_Education extends Spider_Grab_Base{
    //网站地址
    private $_url  = '';
    private $_type = '';

    /**
     * 构造函数
     * @param $path 存储下载文件的路径
     * @param $url  网站初始地址
     */
    public function __construct($url, $type, $province){
        $this->_type = $type;
        $this->_path =  $this->_path . $province . '/' . $type;
        $this->_url  = $url;
    }

    /**
     * @param null
     * @return null
     * 开始下载文件入口
     */
    public function downloadFiles(){
        $this->createDirs($this->_path);
        $http = Base_Network_Http::instance();
        $output = $http->url($this->_url)->exec();

        $list = $this->getSchool($output);
        $this->writeSchoolToFile($list);

        $tag = $this->getSchoolList($output);
        if ('上一篇:无' == $tag){
            echo "done";
        } else{
            $this->getNextPage($tag);
            $this->downloadFiles();
        }
    }

    /**
     * @param string $content 需要匹配的内容
     * @return string 返回是否还有上一页，如果有返回上一页的链接
     */
    public function getSchoolList($content=''){
        $tag = '';
        $pattern = "#<div class=\"dp_xsp\"><ul>.*<li>(.*)</li>.*<li>.*</li>.*</ul></div>#Uis";
        if (preg_match($pattern, $content, $returns)){
            $tag = $returns[1];
        }

        return $tag;
    }

    /**
     * @param string $content 需要匹配的内容
     * @return string  返回上一页的链接地址
     */
    public function getNextPage($content=''){
        $url = '';
        $pattern = "#<a href=\"(.*)\".*>(.*)</a>#Uis";
        if (preg_match($pattern, $content, $returns)){
            $this->_url  = 'http://www.bjedu.gov.cn'.$returns[1];
            $url = $returns;
        }

        return $url;
    }

    public function getSchool($content=''){
        $list = array();
        //返回你要采集的内容
        //$pattern = "@<tbody>(.*)</tbody>@Uis";
        $pattern = "@<table.*>(.*)</table>@Uis";
        //返回你要得到的具体内容
        $pattern_list = "@<tr>(.*)</tr>@Uis";
        if (preg_match($pattern, $content, $returns)){
            preg_match_all($pattern_list, $returns[1], $returns_list);
            array_shift($returns_list[0]);
            foreach ($returns_list[0] as $key=>$item){
                $list[] = $this->getSchoolDetail($item);
            }
        }
        return $list;
    }

    /**
     * @param string $content 一个学校的基本信息
     * @return array 返回详细的学校信息，包括详细页面的信息
     */
    public function getSchoolDetail($content=''){
        $school = array();
        $pattern = "@<td.*>(.*)</td>@Uis";
        if (preg_match_all($pattern, $content, $returns)) {
            $school =  $returns[1];
        }
        return $school;
    }

    /**
     * @param array $list 将该列表中的学校写入到文件中
     */
    public function writeSchoolToFile($list=array()){
        foreach ($list as $item){
            //因为学校数据不一致，结构不统一所以需要做此处理
            if ($this->_type == 'kindergarten'){
                $school[] = array(
                    'name' => strip_tags($item[1]),
                    'address' => strip_tags($item[2]),
                    'zip_code' => strip_tags($item[3]),
                    'city' => strip_tags($item[5]),
                    'type' => strip_tags($item[4]),
                );
            }else {
                $school[] = array(
                    'name' => strip_tags($item[1]),
                    'address' => strip_tags($item[2]),
                    'zip_code' => strip_tags($item[5]),
                    'city' => strip_tags($item[4]),
                    'type' => strip_tags($item[3]),
                );
            }
        }
        $this->writeIntoFile($school);
    }
}