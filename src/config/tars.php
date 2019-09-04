<?php
//http
return [
    //http服务网关
    'registries' => [],
    'log_level' => \Monolog\Logger::INFO,
    'services' => [
        'ShopObj' => [
            'protocolName' => 'http', //http, json, tars or other
            'serverType' => 'http', //http(no_tars default), websocket, tcp(tars default), udp
            'namespaceName' => 'Lxj\Laravel\Tars\\',
            'monitorStoreConf' => [
                'className' => Tars\monitor\cache\SwooleTableStoreCache::class,
                'config' => [
                    'size' => 40960
                ]
            ],
        ]
    ],
    'proto' => [
        'appName' => 'ShopHttp', //根据实际情况替换
        'serverName' => 'ShopService', //根据实际情况替换
        'objName' => 'ShopObj', //根据实际情况替换
    ],
];

////tcp
//return [
//    //http服务网关
//    'registries' => [],
//    'log_level' => \Monolog\Logger::INFO,
//    'services' => [
//        'ShopObj' => [
//            'protocolName' => 'tars', //http, json, tars or other
//            'serverType' => 'tcp', //http(no_tars default), websocket, tcp(tars default), udp
//            'home-api' => '\App\Tars\servant\ShopTcp\ShopService\ShopObj\ShopServant', //根据实际情况替换，遵循PSR-4即可，与tars.proto.php配置一致
//            'home-class' => '\App\Tars\impl\ShopTcp', //根据实际情况替换，遵循PSR-4即可
//        ],
//    ],
//    'proto' => [
//        'appName' => 'ShopTcp', //根据实际情况替换
//        'serverName' => 'ShopService', //根据实际情况替换
//        'objName' => 'ShopObj', //根据实际情况替换
//        'withServant' => true, //决定是服务端,还是客户端的自动生成,true为服务端
//        'tarsFiles' => array(
//            //根据实际情况填写
//            './Shop.tars',
//        ),
//        'dstPath' => '../src/app/Tars/servant', //可替换，遵循PSR-4规则
//        'namespacePrefix' => 'App\Tars\servant', //可替换，遵循PSR-4规则
//    ],
//];
