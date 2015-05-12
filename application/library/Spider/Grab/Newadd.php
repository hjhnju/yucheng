<?php

/**
 * 俊华新给的数据
 */
class Spider_Grab_Newadd extends Spider_Grab_Base
{

    /**
     * 构造函数
     * 
     * @param $path 存储下载文件的路径            
     * @param $url 网站初始地址            
     */
    public function __construct()
    {
        $this->_province = 'beijing';
        $this->_type     = 'kindergarten';
    }

    /**
     *
     * @param            
     *
     */
    public function run()
    {
        $base_path = dirname(__FILE__);
        $file = file($base_path . "/data/newdata.csv");
        $arrRet = array();
        foreach ($file as $val) {
            $val = iconv('GB2312', 'UTF-8', $val);
            $arr = explode(",",$val);
            $arrRet[] = $arr;
        }
        foreach ($arrRet as $val) {
            $arrData = array(
                'name'     => $val[2],
                'type'     => $val[3],
                'phone'    => $val[4],
                'zip_code' => $val[5],
                'address'  => $val[6],
                'province' => $this->_province,
                'type_en'  => $this->_type
            );
            $schools[$this->_type][] = $arrData;
        }
        $origin_path = $this->_path;
        foreach ($schools as $key => $items) {
            $this->_path = $origin_path . 'beijing/' . $key;
            $this->createDirs($this->_path);
            $this->writeIntoFile($items);
        }
    }
}