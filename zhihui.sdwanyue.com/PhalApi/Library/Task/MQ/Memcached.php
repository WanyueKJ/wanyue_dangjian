<?php
/**
 * Memcached MQ
 *
 * - 队列存放于Memcached/Memcache，但须注意MC默认情况下单个key最大只支持1M大小
 *
 * @author dogstar <chanzonghuang@gmail.com> 20160430
 */

class Task_MQ_Memcached extends Task_MQ_KeyValue {

    public function __construct(PhalApi_Cache $mcCache = NULL) {
        if ($mcCache === NULL) {
            $config = DI()->config->get('app.Task.mq.mc');
            if (!isset($config['host'])) {
                $config['host'] = '127.0.0.1';
            }
            if (!isset($config['port'])) {
                $config['port'] = 11211;
            }

            //优先使用memcached
            $mcCache = extension_loaded('memcached') 
                ? new PhalApi_Cache_Memcached($config) 
                : new PhalApi_Cache_Memcache($config);
        }

        $mcCache->set('123123', time(), 31536000);

        parent::__construct($mcCache);
    }

    /**
     * 最大缓存时间，29天，因为MC的过期时间不能超过30天
     */
    protected function getExpireTime() {
        return 2505600;
    }
}
