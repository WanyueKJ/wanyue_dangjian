<?php
/**
 * 请在下面放置任何您需要的应用配置
 */

return array(

    /**
     * 应用接口层的统一参数
     */
    'apiCommonRules' => array(
        //'sign' => array('name' => 'sign', 'require' => true),
        'uid' => array('name' => 'uid', 'type' => 'int', 'default'=>'0'),
        'token' => array('name' => 'token', 'type' => 'string', 'default'=>''),
    ),
    
    /* redis信息 */
    'REDIS_HOST' => "127.0.0.1",
    'REDIS_AUTH' => "123456",
    'REDIS_PORT' => "6379",
    /* 接口签名key */
    'sign_key' => '400d069a791d51ada8af3e6c2979bcd7',
    
    /* 上传方式：0表示本地，1表示 七牛，2表示 腾讯云(预留)，3表示阿里云(预留) */
	'uptype'=> 0,
    
    /**
     * 七牛相关配置
     */
    'Qiniu' =>  array(
        //统一的key
        'accessKey' => '',
        'secretKey' => '',
        //自定义配置的空间
        'space_bucket' => '',
        'space_host' => '',
    ),
		
     /**
     * 本地上传
     */
    'UCloudEngine' => 'local',

    /**
     * 本地存储相关配置（UCloudEngine为local时的配置）
     */
    'UCloud' => array(
        //对应的文件路径
        'host' => 'http://xxxxxxx.com/upload'
    ),
    /**
     * 接口服务白名单，格式：接口服务类名.接口服务方法名
     *
     * 示例：
     * - *.*            通配，全部接口服务，慎用！
     * - Default.*      Api_Default接口类的全部方法
     * - *.Index        全部接口类的Index方法
     * - Default.Index  指定某个接口服务，即Api_Default::Index()
     */
    'service_whitelist' => array(
        'Default.Index',
    ),
);
