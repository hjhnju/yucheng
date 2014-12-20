<?php
/**
 * 对外的API接口
 */
class User_Api{

    const REG_NAME = 'name';
    const REG_EMAIL = 'email';
    const REG_PHONE = 'phone';
    const REG_REALNAME = 'realname';
    const REG_PASSWD = 'passwd';
    const LAST_TIME = 5;     //验证码过期时间,5分钟
    
    protected static $_arrRegMap = array(
        self::REG_PASSWD         => '/^[a-zA-Z0-9!@#$%^&\'\(\({}=+\-]{6,20}$/',
        self::REG_NAME           => '/^([a-zA-Z])+[-_.0-9a-zA-Z]{4,19}$/',
        self::REG_EMAIL          => '/^[_.0-9a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,3}$/',
        self::REG_PHONE          => '/^(13[0-9]|15[0|3|6|7|8|9]|18[6|0|8|9])\d{8}$/',
        self::REG_REALNAME       => '/^[\x7f-\xff]{2,4}$/',
    );
    
    /**
     * 检验$value是否是$type规定的类型
     * 正则匹配验证：返回1为成功，其它为失败
    */
    public static function checkReg($type, $value){
        $ret = preg_match(self::$_arrRegMap[$type],$value);
        if($ret == User_RetCode::REG_FORMAT_WRONG) {
            return 1;
        }
        return 0;
    }
    
    /**
     * $im_x,$im_y指明验证码图片的长和宽
     * 生成图片验证码
     */
    public static function getAuthImage($im_x=160,$im_y=40){
        $text = self::makeRand(4);
        $im = imagecreatetruecolor($im_x,$im_y);
        $text_c = ImageColorAllocate($im, mt_rand(0,100),mt_rand(0,100),mt_rand(0,100));
        $tmpC0=mt_rand(100,255);
        $tmpC1=mt_rand(100,255);
        $tmpC2=mt_rand(100,255);
        $buttum_c = ImageColorAllocate($im,$tmpC0,$tmpC1,$tmpC2);
        imagefill($im, 16, 13, $buttum_c);
        $font = APP_PATH.'/htdocs/font/t1.ttf';
        for ($i=0;$i<strlen($text);$i++){
            $tmp =substr($text,$i,1);
            $array = array(-1,1);
            $p = array_rand($array);
            $an = $array[$p]*mt_rand(1,10);//角度
            $size = 28;
            imagettftext($im, $size, $an, 15+$i*$size, 35, $text_c, $font, $tmp);
        }
    
        $distortion_im = imagecreatetruecolor ($im_x, $im_y);
    
        imagefill($distortion_im, 16, 13, $buttum_c);
        for ( $i=0; $i<$im_x; $i++) {
            for ( $j=0; $j<$im_y; $j++) {
                $rgb = imagecolorat($im, $i , $j);
                if( (int)($i+20+sin($j/$im_y*2*M_PI)*10) <= imagesx($distortion_im)&& (int)($i+20+sin($j/$im_y*2*M_PI)*10) >=0 ) {
                    imagesetpixel ($distortion_im, (int)($i+10+sin($j/$im_y*2*M_PI-M_PI*0.1)*4) , $j , $rgb);
                }
            }
        }
        //加入干扰象素;
        $count = 160;//干扰像素的数量
        for($i=0; $i<$count; $i++){
            $randcolor = ImageColorallocate($distortion_im,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
            imagesetpixel($distortion_im, mt_rand()%$im_x , mt_rand()%$im_y , $randcolor);
        }
    
        $rand = mt_rand(5,30);
        $rand1 = mt_rand(15,25);
        $rand2 = mt_rand(5,10);
        for ($yy=$rand; $yy<=+$rand+2; $yy++){
            for ($px=-80;$px<=80;$px=$px+0.1){
                $x=$px/$rand1;
                if ($x!=0){
                    $y=sin($x);
                }
                $py=$y*$rand2;
                imagesetpixel($distortion_im, $px+80, $py+$yy, $text_c);
            }
        }
    
        //设置文件头;
        Header("Content-type: image/png");
    
        //以PNG格式将图像输出到浏览器或文件;
        Imagepng($distortion_im);
    
        //销毁一图像,释放与image关联的内存;
        ImageDestroy($distortion_im);
        ImageDestroy($im);
    }
    
    public static function  makeRand($length="32"){//验证码文字生成函数
        $str="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $result="";
        for($i=0;$i<$length;$i++){
            $num[$i]=rand(0,25);
            $result.=$str[$num[$i]];
        }
        return $result;
    }
    
    /**
     * 获取短信验证码信息
     */
    public static function sendSmsCode($strPhone){
        $srandNum = rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
        $arrArgs = array($srandNum, self::LAST_TIME);
        $tplid   = Base_Config::getConfig('sms.tplid.vcode', CONF_PATH . '/sms.ini');
        $bResult = Base_Sms::getInstance()->send($strPhone,$tplid, $arrArgs);
        $now = time();
        Yaf_Session::getInstance()->set("vericode".$strPhone,$srandNum.",".$now);
        if(!empty($bResult)){
            return User_RetCode::SUCCESS;
        }
        return User_RetCode::GETVERICODE_FAIL;
    }
    
    /**
     * 验证用户输入的短信验证码是否正确
     */
    public static function checkSmscode($strPhone,$strVeriCode){
        $strStoredCode = Yaf_Session::getInstance()->get("vericode".$strPhone);
        $arrData = explode(",",$strStoredCode);
        $time = time() - $arrData[1];
        if(($strVeriCode == $arrData[0])&&($time <= 60*self::LAST_TIME)){
            return User_RetCode::SUCCESS;
        }
        return User_RetCode::VERICODE_WRONG;
    }
    
    public static function checkRealName($uid,$strRealName) {
        return true;
    }
    
    public static function setRealName($uid,$strRealName){
        return true;
    }
    
    public static function setEmail($uid,$strEmail){
        return true;
    }
    
    public static function setPasswd($uid,$strPasswd){
        return true;
    }
    
    /**
     * 判断用户的登录状态
     * 若用户处于登录状态，则返回uid，否则返回0
     */
    public static function checkLogin(){
        if(Yaf_Session::getInstance()->has("LOGIN")){
            $uid = Yaf_Session::getInstance()->get("LOGIN");
        }
        return $uid;
    }
}