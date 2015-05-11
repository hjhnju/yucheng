<?php
/**
 * 获取大全网站中的信息
 * @author huwei
 *
 */
class Spider_Grab_Daquan extends Spider_Grab_Base
{
    public function __construct($url, $type, $province){
        $this->_path =  $this->_path . $province . '/' . $type;
        $this->_url  = $url;
    }
    public function run()
    {
        $this->createDirs($this->_path);
        $url = $this->_url;
        $pageTotel = $this->getPages($url);
        for ($i = 1; $i <= $pageTotel; $i ++) {
            echo $url."?page=$i";
            $strData = file_get_contents($url."?page=$i");
            $arrData = explode('yxbtbg_w', $strData);
            $max = count($arrData);
            $school = array();
            for ($j = 1; $j < $max; $j ++) {              
                if (strstr($arrData[$j], "暂无相关学校")) {
                    break;
                }
                $arrRet = array(
                    'name'         => $this->getSubstr("<h5>", "<\/h5>", $arrData[$j]),
                    'url'          => $this->getSubstr("href=\"", "\"", $arrData[$j]),
                    'introduce' => $this->getSubstr("<p>", "<\/p>", $arrData[$j])
                );
                $detail = file_get_contents($arrRet['url']);
                unset($arrRet['url']);
                $arrDetail = explode("university_mainl",$detail);
                $arrRet['type'] = $this->getSubstr("所属学段：", "<\/p>", $arrDetail[1]);
                $arrRet['zip_code'] = $this->getSubstr("邮编：", "<\/p>", $arrDetail[1]);
                $arrRet['email'] = $this->getSubstr("邮箱：", "<\/p>", $arrDetail[1]);
                $arrRet['phone'] = $this->getSubstr("电话:", "<\/ol>", $arrDetail[1]);
                if(strstr($arrRet['phone'],"img src=")){
                    $arrRet['phone'] = '暂无电话';
                }
                $arrRet['address'] = $this->getSubstr("地址:", "<\/ol>", $arrDetail[1]);
                $arrRet['website'] = $this->getSubstr("网址:", "<\/ol>", $arrDetail[1]);
                $arrRet['introduce'] = $this->getSubstr("hidden;\"><p>", "<\/p>", $arrDetail[1]);
                $school[] = $arrRet;
            }
            //写入磁盘
            $this->writeIntoFile($school);
        }
    }

    /**
     * 从string中取出strFrom到strTo之间的字符串
     *
     * @param string $strFrom            
     * @param string $strTo            
     * @param string $string            
     * @return string
     */
    function getSubstr($strFrom, $strTo, $string)
    {
        $strPatern = "/" . $strFrom . '([^"]*?)' . $strTo . "/is";
        preg_match($strPatern, $string, $out);
        return $out[1];
    }

    /**
     * 获取从URL给出的网址中抓到的页面数,模式由patern给出
     * 没有匹配的，则表示只有一页
     * @param string $url            
     * @param string $patern            
     * @return integer
     */
    function getPages($url, $patern = '/>([0-9]*)\/([0-9]*)</')
    {
        $string = file_get_contents($url);
        if (preg_match($patern, $string, $match)) {
            return $match[2];
        }
        return 1;
    }
}