<?php
/**
 * 为User_Api中的对外接口实现提供方法
 * @author huwei04
 *
 */
class User_Logic_ImageCode {

    /**
     * 验证图片验证码
     * @param  string $strType 自定义验证码类型，e.g. login, editemail
     * @param  string $code 验证码
     * @return  boolean 
     */
    public static function checkCode($strType, $code){

        $storeCode = Base_Redis::getInstance()->get(User_Keys::getImageCodeKey($strType));
        $bolRet    = strtolower($storeCode) === strtolower($code) ? true : false;

        return $bolRet;
    }
    
    /**
     * $im_x,$im_y指明验证码图片的长和宽
     * 生成图片验证码
     */
    public static function genImage($strType, $im_x=162, $im_y=35){
        //存储验证码
        $text = self::makeRand(4);
        Base_Redis::getInstance()->set(User_Keys::getImageCodeKey($strType), $text);

        $im       = imagecreatetruecolor($im_x,$im_y);
        $text_c   = ImageColorAllocate($im, mt_rand(0,100),mt_rand(0,100),mt_rand(0,100));
        $tmpC0    = mt_rand(100,255);
        $tmpC1    = mt_rand(100,255);
        $tmpC2    = mt_rand(100,255);
        $buttum_c = ImageColorAllocate($im,$tmpC0,$tmpC1,$tmpC2);
        imagefill($im, 16, 13, $buttum_c);

        $font = APP_PATH . '/htdocs/font/t1.ttf';
        for ($i=0;$i<strlen($text);$i++){
            $tmp   = substr($text,$i,1);
            $array = array(-1,1);
            $p     = array_rand($array);
            $an    = $array[$p]*mt_rand(1,10);//角度
            $size  = 28;
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
        imageDestroy($im);

        return $distortion_im;
    }
    
    private static function makeRand($length="32"){//验证码文字生成函数
        $str    = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $result = "";
        for($i=0;$i<$length;$i++){
            $num[$i] = rand(0,25);
            $result .= $str[$num[$i]];
        }
        return $result;
    }
}