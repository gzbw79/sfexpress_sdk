<?php
/**
 * Write by BoWen 2023/9/15 下午4:12
 * Email: gzbw79@163.com
 * 云打印面单打印2.0接口-面单类API
 * 文档：https://open.sf-express.com/Api/ApiDetails?level3=317&interName=%E4%BA%91%E6%89%93%E5%8D%B0%E9%9D%A2%E5%8D%952.0%E6%8E%A5%E5%8F%A3-COM_RECE_CLOUD_PRINT_WAYBILLS
 */

require __DIR__ . "/../vendor/autoload.php";

$handle = new \Gzlbw\sfexpress\CloudPrintWaybills("XTYSWNJt2vpS", "2cIBZs3r1QvOhqYCSoAR8ntmhTgYAqdF", true);
// 添加要打印的面单快递单号
$handle->addWayBills("SF7444471043754");
// 获取面单文件
$ret = $handle->req([
    'templateCode' => 'fm_150_standard_XTYSWNJt2vpS', // 模板编码
    'sync' => true, //
]);
var_dump($ret);