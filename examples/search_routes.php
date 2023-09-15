<?php
/**
 * Write by BoWen 2023/9/15 下午3:38
 * Email: gzbw79@163.com
 * 路由查询接口
 */

require __DIR__ . "/../vendor/autoload.php";

$handle = new \Gzlbw\sfexpress\SearchRoutes("XTYSWbTkcFef", "lrgdnHl21IOb6wEmPYB6def9C4JG2Se5", true);
$ret = $handle->req([
    'trackingNumber' => ["SF7444471039325"]
]);
var_dump($ret);

/**
 * 响应报文样例
 * array(4) {
["apiErrorMsg"]=>
string(0) ""
["apiResponseID"]=>
string(32) "00018A97CE03FB3FE9D58FC7BD17AB3F"
["apiResultCode"]=>
string(5) "A1000"
["apiResultData"]=>
string(238) "{"success":true,"errorCode":"S0000","errorMsg":null,"msgData":{"routeResps":[{"mailNo":"SF7444471039325","routes":[{"acceptAddress":"深圳市","acceptTime":"2023-09-15 15:46:15","remark":"顺丰速运 已收取快件","opCode":"50"}]}]}}"
}
 */