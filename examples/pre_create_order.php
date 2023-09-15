<?php
/**
 * Write by BoWen 2023/9/15 下午3:01
 * Email: gzbw79@163.com
 * 预下单接口样例 - 用于校验收件地址是否支持派送和收件
 */
require __DIR__ . "/../vendor/autoload.php";

$handle = new \Gzlbw\sfexpress\PreCreateOrder("XTYSWbTkcFef", "lrgdnHl21IOb6wEmPYB6def9C4JG2Se5", true);
// 业务订单编号
$order_no = date("YmdHi") . rand(1000, 9999);
$handle->setOrderId($order_no);
// 寄件人信息
$handle->addContractInfo("from", "天河区天河南二路丰兴广场", "小李", "", "13800138000", "广东省", "广州市");
// 收件人信息
$handle->addContractInfo("to", "天河区天河路611号摩登百货", "小张", "", "13800138001", "广东省", "广州市");
// 发送请求
$ret = $handle->req([
    'monthlyCard' => '7551234567', // 月结卡号
    'payMethod' => 1, // 付款方式，支持以下值： 1:寄方付 2:收方付 3:第三方付
    'expressTypeId' => 2, // 快件产品类别, https://open.sf-express.com/developSupport/734349?activeIndex=324604
    'remark' => '派件前请电话联系', // 备注
]);

var_dump($ret);

/**
 * 响应数据样例
 * array(4) {
["apiErrorMsg"]=>
string(0) ""
["apiResponseID"]=>
string(32) "00018A97B28A9D3F9F66B7A2B880603F"
["apiResultCode"]=>
string(5) "A1000"
["apiResultData"]=>
string(1710) "{"success":true,"errorCode":"S0000","errorMsg":null,"msgData":{"orderId":"2023091515166449","originCode":"020","destCode":"020","filterResult":2,"remark":"","url":null,"paymentLink":null,"isUpstairs":null,"isSpecialWarehouseService":null,"mappingMark":null,"agentMailno":null,"returnExtraInfoList":null,"waybillNoInfoList":[{"waybillType":1,"waybillNo":"SF7444471039325","boxNo":null,"length":null,"width":null,"height":null,"weight":null,"volume":null}],"routeLabelInfo":[{"code":"1000","routeLabelData":{"waybillNo":"SF7444471039325","sourceTransferCode":"020W","sourceCityCode":"020","sourceDeptCode":"020","sourceTeamCode":"","destCityCode":"020","destDeptCode":"020Z069","destDeptCodeMapping":"","destTeamCode":"006","destTeamCodeMapping":"","destTransferCode":"020W","destRouteLabel":"020W-Z069-006","proName":"","cargoTypeCode":"T6","limitTypeCode":"T6","expressTypeCode":"B1","codingMapping":"H7","codingMappingOut":"","xbFlag":"0","printFlag":"000000000","twoDimensionCode":"MMM={'k1':'020W','k2':'020Z069','k3':'006','k4':'T4','k5':'SF7444471039325','k6':'','k7':'dfa46974'}","proCode":"T 标快","printIcon":"00000000","abFlag":"","destPortCode":"","destCountry":"","destPostCode":"","goodsValueTotal":"","currencySymbol":"","cusBatch":"","goodsNumber":"","errMsg":"","checkCode":"dfa46974","proIcon":"","fileIcon":"","fbaIcon":"","icsmIcon":"","destGisDeptCode":"020Z069","newIcon":null,"sendAreaCode":null,"destinationStationCode":null,"sxLabelDestCode":null,"sxDestTransferCode":null,"sxCompany":null,"newAbFlag":null,"destAddrKeyWord":"","rongType":null,"waybillIconList":null},"message":"SF7444471039325:"}],"contactInfoList":null,"sendStartTm":null,"customerRights":null,"expressTypeId":null}}"
}
 */