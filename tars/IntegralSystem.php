<?php
//IntegralSystem.IntegralServer.IntegralObj
return [
    'appName' => 'IntegralSystem', //根据实际情况替换
    'serverName' => 'IntegralServer', //根据实际情况替换
    'objName' => 'IntegralObj', //根据实际情况替换
    'withServant' => false, //决定是服务端,还是客户端的自动生成,true为服务端
    'tarsFiles' => array(
        //根据实际情况填写
        __DIR__.'/IntegralSystem.tars',
    ),
    'dstPath' => '../src/app/Tars/cservant', //可替换，遵循PSR-4规则
    'namespacePrefix' => 'App\Tars\cservant', //可替换，遵循PSR-4规则
];
?>