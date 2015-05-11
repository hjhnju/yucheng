<?php

/**
 * Class Spider_Grab_base
 * 抓取网页基本类
 */
class Spider_Grab_Base{
    public $_path = '/home/work/user/guojl/files/';
    public $_separator = '.html';
    /**
     * @param $path 需要创建的路径
     * @return bool 返回创建成功与否
     */
    public function createDirs($path){
        if (!is_dir($path)){
            if(!$this->createDirs(dirname($path))){
                return false;
            }
            if(!mkdir($path,0777)){
                return false;
            }
        }
        return true;
    }

    /**
     * @param $path 删除文件夹
     * @ 请暂时不要使用，还没测试
     */
    public function deleteDirs($path){
        $dir = dir($path);
        while (false !== ($child = $dir->read())){
            if ($child != '.' && $child != '..'){
                if (is_dir($path.'/'.$child)){
                    $this->deleteDirs($path.'/'.$child);
                }else {
                    unlink($path.'/'.$child);
                }
            }
        }
        $dir->close();
        rmdir($path);
    }

    /**
     * 向文件中写入内容
     * 写入的主要字段有
     * name => 学校的名字
     * introduce 学校的详细介绍
     * type     学校类型，幼儿园还是中小学
     * zip_code 邮编
     * email    邮箱
     * phone    电话
     * address  地址
     * website  官网
     * city     城市
     * regulation 招生简章
     * nature   学校属性 公立，私立 等等
     * weight   学校的重要性，省重点，市重点，等等
     */
    public function writeIntoFile($list) {
        foreach ($list as $school){
            $filename =  $this->_path . '/'. trim($school['name']) . $this->_separator;
            if (file_exists($filename)){
                //如果存在该文件，则需要将信息补充进去
                $content = file_get_contents($filename);
                $data = (array)json_decode($content);
                foreach($school as $key=>$value) {
                    if (trim($data[$key]) == ''){
                        $data[$key] = $value;
                    }
                }
                $school = $data;
            }
            file_put_contents($filename, json_encode($school));
        }
    }
}