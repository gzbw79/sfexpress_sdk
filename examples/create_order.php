<?php
/**
 * Write by BoWen 2023/9/15 下午3:01
 * Email: gzbw79@163.com
 * 下订单接口-速运类API
 */
require __DIR__ . "/../vendor/autoload.php";

$handle = new \Gzlbw\sfexpress\CreateOrder("XTYSWbTkcFef", "lrgdnHl21IOb6wEmPYB6def9C4JG2Se5", true);
// 业务订单编号
$order_no = date("YmdHi") . rand(1000, 9999);
$handle->setOrderId($order_no);
// 寄件人信息
$handle->addContractInfo("from", "天河区天河南二路丰兴广场", "小李", "", "13800138000", "广东省", "广州市");
// 收件人信息
$handle->addContractInfo("to", "天河区天河路611号摩登百货", "小张", "", "13800138001", "广东省", "广州市");
// 货物信息
$handle->addCargoDetail("文件 1份", "1", [
    'amount' => 10, // 声明价值
    'currency' => 'CNY' // 价值结算货币
]);
// 发送请求
$ret = $handle->req([
    'monthlyCard' => '7551234567', // 月结卡号
    'payMethod' => 1, // 付款方式，支持以下值： 1:寄方付 2:收方付 3:第三方付
    'expressTypeId' => 2, // 快件产品类别, https://open.sf-express.com/developSupport/734349?activeIndex=324604
    'remark' => '派件前请电话联系', // 备注
    'isReturnRoutelabel' => 1, // 是否返回路由标签： 默认1， 1：返回路由标签， 0：不返回；除部分特殊用户外，其余用户都默认返回
    'isReturnSignBackRoutelabel' => 0, // 是否返回签回单路由标签： 默认0， 1：返回路由标签， 0：不返回
    'totalWeight' => 1.000 // 订单货物总重量（郑州空港海关必填）， 若为子母件必填， 单位千克， 精确到小数点后3位，如果提供此值， 必须>0 (子母件需>6)
]);

var_dump($ret);

/**
 * 响应数据样例
 * array(4) {
["apiErrorMsg"]=>
string(0) ""
["apiResponseID"]=>
string(32) "00018A97BCE25C3FE59E04A28E84E33F"
["apiResultCode"]=>
string(5) "A1000"
["apiResultData"]=>
string(349) "{"success":true,"errorCode":"S0000","errorMsg":null,"msgData":[{"serviceDate":"2023-09-15","startTime":"2023-09-15 08:00:00","endTime":"2023-09-15 21:30:00"},{"serviceDate":"2023-09-16","startTime":"2023-09-16 08:00:00","endTime":"2023-09-16 21:30:00"},{"serviceDate":"2023-09-17","startTime":"2023-09-17 08:00:00","endTime":"2023-09-17 21:30:00"}]}"
}
 */