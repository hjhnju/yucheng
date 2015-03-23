<?php
/**
 * 开放的省份
 * @author jiangsongfang
 *
 */
class Loan_Type_Province extends Base_Type {
     
      /**
       * 北京
       * @var integer
       */
      const BEIJING = 1;
      

      /**
       * 天津
       * @var integer
       */
      const TIANJIN = 21;
      
      /**
       * 上海
       * @var integer
       */
      const SHANGHAI = 40;
      
      /**
       * 重庆
       * @var integer
       */
      const ZHONGQING = 61;
      
      /**
       * 河北省
       * @var integer
       */
      const HEBEISHENG = 102;
      
      /**
       * 山西省
       * @var integer
       */
      const SHANXISHENG = 297;
      
      /**
       * 内蒙古区
       * @var integer
       */
      const NEIMENGGUQU = 439;
      
      /**
       * 辽宁省
       * @var integer
       */
      const LIAONINGSHENG = 561;
      
      /**
       * 吉林省
       * @var integer
       */
      const JILINSHENG = 690;
      
      /**
       * 黑龙江省
       * @var integer
       */
      const HEILONGJIANGSHENG = 768;
      
      /**
       * 江苏省
       * @var integer
       */
      const JIANGSUSHENG = 924;
      
      /**
       * 浙江省
       * @var integer
       */
      const ZHEJIANGSHENG = 1057;
      
      /**
       * 安徽省
       * @var integer
       */
      const ANHUISHENG = 1170;
      
      /**
       * 福建省
       * @var integer
       */
      const FUJIANSHENG = 1310;
      
      /**
       * 江西省
       * @var integer
       */
      const JIANGXISHENG = 1414;
      
      /**
       * 山东省
       * @var integer
       */
      const SHANDONGSHENG = 1536;
      
      /**
       * 河南省
       * @var integer
       */
      const HENANSHENG = 1711;
      
      /**
       * 湖北省
       * @var integer
       */
      const HUBEISHENG = 1905;
      
      /**
       * 湖南省
       * @var integer
       */
      const HUNANSHENG = 2034;
      
      /**
       * 广东省
       * @var integer
       */
      const GUANGDONGSHENG = 2184;
      
      /**
       * 广西区
       * @var integer
       */
      const GUANGXIQU = 2403;
      
      /**
       * 海南省
       * @var integer
       */
      const HAINANSHENG = 2541;
      
      /**
       * 四川省
       * @var integer
       */
      const SICHUANGSHENG = 2570;
      
      /**
       * 贵州省
       * @var integer
       */
      const GUIZHOUSHENG = 2791;
      
      /**
       * 云南省
       * @var integer
       */
      const YUNNANSHENG = 2892;
      
      /**
       * 西藏区
       * @var integer
       */
      const XIZANGQU = 3046;
      
      /**
       * 陕西省
       * 山西省和陕西省拼音重名 所以将陕西省置为SHANXISHENG1
       * @var integer
       */
      const SHANXISHENG1 = 3128;
      
      /**
       * 甘肃省
       * @var integer
       */
      const GANSUSHENG = 3256;
      
      /**
       * 青海省
       * @var integer
       */
      const QINGHAISHENG = 3369;
      
      /**
       * 宁夏区
       * @var integer
       */
      const NINGXIAQU = 3422;
      
      /**
       * 新疆区
       * @var integer
       */
      const XINJIANGQU = 3454;
      
      /**
       * 默认key名
       * @var string
       */
      const DEFAULT_KEYNAME = 'level';
    
      /**
       * 默认类型属性名
       * @var string
       */
      const DEFAULT_FIELD = 'level_name';
    
      /**
       * 状态名
       * @var array
       */
      public static $names = array(
         self::BEIJING             => '北京',
         self::TIANJIN             => '天津',
         self::SHANGHAI            => '上海',
      	 self::ZHONGQING           => '重庆',
      	 self::HEBEISHENG          => '河北省',
      	 self::SHANXISHENG         => '山西省',
      	 self::NEIMENGGUQU         => '内蒙古区',
      	 self::LIAONINGSHENG 	   => '辽宁省',
      	 self::JILINSHENG          => '吉林省',
         self::HEILONGJIANGSHENG   => '黑龙江省',
      	 self::JIANGSUSHENG        => '江苏省',
      	 self::ZHEJIANGSHENG       => '浙江省', 
      	 self::ANHUISHENG          => '安徽省',
         self::FUJIANSHENG         => '福建省',
         self::JIANGXISHENG        => '江西省',
      	 self::SHANDONGSHENG       => '山东省',
      	 self::HENANSHENG          => '河南省',
      	 self::HUBEISHENG          => '湖北省',
      	 self::HUNANSHENG          => '湖南省', 
      	 self::GUANGDONGSHENG      => '广东省',
         self::GUANGXIQU           => '广西区',
         self::HAINANSHENG         => '海南省',
      	 self::SICHUANGSHENG       => '四川省',
      	 self::GUIZHOUSHENG        => '贵州省',
      	 self::YUNNANSHENG         => '云南省', 
      	 self::XIZANGQU            => '西藏区',
         self::SHANXISHENG1        => '陕西省',
         self::GANSUSHENG          => '甘肃省',
      	 self::QINGHAISHENG        => '青海省',
      	 self::NINGXIAQU           => '宁夏区', 
      	 self::XINJIANGQU          => '新疆区',
      );
}