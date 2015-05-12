<?php

/**
 * 北京教委网站
 */
class Spider_Grab_Education extends Spider_Grab_Base
{
    // 网站地址
    private $_url = '';

    private $_match = array(
        '学校标识码' => array(
            'name' => 'school_id',
            'index' => 0
        ),
        '学校名称' => array(
            'name' => 'name',
            'index' => 1
        ),
        '学校地址名称' => array(
            'name' => 'address',
            'index' => 2
        ),
        '邮政编码' => array(
            'name' => 'zip_code',
            'index' => 3
        ),
        '办学类型' => array(
            'name' => 'type',
            'index' => 4
        ),
        '管理部门' => array(
            'name' => 'city',
            'index' => 5
        )
    );

    /**
     * 构造函数
     * 
     * @param $path 存储下载文件的路径            
     * @param $url 网站初始地址            
     */
    public function __construct($url, $type, $province)
    {
        $this->_type = $type;
        $this->_province = $province;
        $this->_path = $this->_path . $province . '/' . $type;
        $this->_url = $url;
    }

    /**
     *
     * @param
     *            null
     * @return null 开始下载文件入口
     */
    public function downloadFiles()
    {
        $this->createDirs($this->_path);
        $http = Base_Network_Http::instance();
        $output = $http->url($this->_url)->exec();
        
        $list = $this->getSchool($output);
        $this->writeSchoolToFile($list);
        
        $tag = $this->getSchoolList($output);
        if ('上一篇:无' == $tag) {
            echo "done";
        } else {
            $this->getNextPage($tag);
            $this->downloadFiles();
        }
    }

    /**
     *
     * @param string $content
     *            需要匹配的内容
     * @return string 返回是否还有上一页，如果有返回上一页的链接
     */
    public function getSchoolList($content = '')
    {
        $tag = '';
        $pattern = "#<div class=\"dp_xsp\"><ul>.*<li>(.*)</li>.*<li>.*</li>.*</ul></div>#Uis";
        if (preg_match($pattern, $content, $returns)) {
            $tag = $returns[1];
        }
        
        return $tag;
    }

    /**
     *
     * @param string $content
     *            需要匹配的内容
     * @return string 返回上一页的链接地址
     */
    public function getNextPage($content = '')
    {
        $url = '';
        $pattern = "#<a href=\"(.*)\".*>(.*)</a>#Uis";
        if (preg_match($pattern, $content, $returns)) {
            $this->_url = 'http://www.bjedu.gov.cn' . $returns[1];
            $url = $returns;
        }
        
        return $url;
    }

    public function getSchool($content = '')
    {
        $list = array();
        // 返回你要采集的内容
        // $pattern = "@<tbody>(.*)</tbody>@Uis";
        $pattern = "@<table.*>(.*)</table>@Uis";
        // 返回你要得到的具体内容
        $pattern_list = "@<tr>(.*)</tr>@Uis";
        if (preg_match($pattern, $content, $returns)) {
            preg_match_all($pattern_list, $returns[1], $returns_list);
            foreach ($returns_list[0] as $key => $item) {
                $list[] = $this->getSchoolDetail($item);
            }
        }
        return $list;
    }

    /**
     *
     * @param string $content
     *            一个学校的基本信息
     * @return array 返回详细的学校信息，包括详细页面的信息
     */
    public function getSchoolDetail($content = '')
    {
        $school = array();
        $pattern = "@<td.*>(.*)</td>@Uis";
        if (preg_match_all($pattern, $content, $returns)) {
            $school = $returns[1];
        }
        return $school;
    }

    /**
     *
     * @param array $list
     *            将该列表中的学校写入到文件中
     */
    public function writeSchoolToFile($list = array())
    {
        foreach ($list as $key => $item) {
            if (! empty($key)) {
                $school = array();
                foreach ($list[0] as $index => $data) {
                    $data = trim(strip_tags($data));
                    $school[$key - 1][$this->_match[$data]['name']] = trim(strip_tags($item[$index]));
                }
                $this->writeIntoFile($school);
            }
        }
    }
}