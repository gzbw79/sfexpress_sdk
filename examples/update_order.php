<?php
/**
 * Write by BoWen 2023/9/15 下午3:53
 * Email: gzbw79@163.com
 * 订单确认/取消接口-速运类API
 */

require __DIR__ . "/../vendor/autoload.php";

$handle = new \Gzlbw\sfexpress\UpdateOrder("XTYSWbTkcFef", "lrgdnHl21IOb6wEmPYB6def9C4JG2Se5", true);
// 确认订单
$ret = $handle->confirm("2023091515166449");
var_dump($ret);
/**
 * 报文响应样例
 * array(4) {
["apiErrorMsg"]=>
string(0) ""
["apiResponseID"]=>
string(32) "00018A97D907F13FDD618D201CAB603F"
["apiResultCode"]=>
string(5) "A1000"
["apiResultData"]=>
string(74) "{"success":false,"errorCode":"8037","errorMsg":"已消单","msgData":null}"
}
 */

// 撤销订单
$ret = $handle->cancel("2023091515166449");
var_dump($ret);
/**
 * 报文响应样例
 * array(4) {
["apiErrorMsg"]=>
string(0) ""
["apiResponseID"]=>
string(32) "00018A97D895433FCB8D4B1C739C303F"
["apiResultCode"]=>
string(5) "A1000"
["apiResultData"]=>
string(198) "{"success":true,"errorCode":"S0000","errorMsg":null,"msgData":{"orderId":"2023091515166449","waybillNoInfoList":[{"waybillType":1,"waybillNo":"SF7444471039325"}],"resStatus":2,"extraInfoList":null}}"
}
 */