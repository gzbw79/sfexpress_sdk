<?php
/**
 * Write by BoWen 2023/9/15 下午3:35
 * Email: gzbw79@163.com
 * 订单结果查询接口
 */

require __DIR__ . "/../vendor/autoload.php";

$handle = new \Gzlbw\sfexpress\SearchOrder("XTYSWbTkcFef", "lrgdnHl21IOb6wEmPYB6def9C4JG2Se5", true);
$ret = $handle->data("2023091515166449");
var_dump($ret);

/**
 * 响应数据样例
 * array(4) {
["apiErrorMsg"]=>
string(0) ""
["apiResponseID"]=>
string(32) "00018A97C54DF13FE1EDD4F4A9445A3F"
["apiResultCode"]=>
string(5) "A1000"
["apiResultData"]=>
string(1333) "{"success":true,"errorCode":"S0000","errorMsg":null,"msgData":{"orderId":"2023091515166449","returnExtraInfoList":null,"waybillNoInfoList":[{"waybillType":1,"waybillNo":"SF7444471039325"}],"origincode":"020","destcode":"020","filterResult":"2","remark":"派件前请电话联系","routeLabelInfo":[{"code":"1000","routeLabelData":{"waybillNo":"SF7444471039325","sourceTransferCode":"020W","sourceCityCode":"020","sourceDeptCode":"020","sourceTeamCode":"","destCityCode":"020","destDeptCode":"020Z069","destDeptCodeMapping":"","destTeamCode":"006","destTeamCodeMapping":"","destTransferCode":"020W","destRouteLabel":"020W-Z069-006","proName":"顺丰标快","cargoTypeCode":"T6","limitTypeCode":"T6","expressTypeCode":"B1","codingMapping":"H7","codingMappingOut":"","xbFlag":"0","printFlag":"000000000","twoDimensionCode":"MMM={'k1':'020W','k2':'020Z069','k3':'006','k4':'T801','k5':'SF7444471039325','k6':'','k7':'b96667f8'}","proCode":"T801","printIcon":"00000000","abFlag":"","destPortCode":"","destCountry":"","destPostCode":"","goodsValueTotal":"","currencySymbol":"","cusBatch":"","goodsNumber":"","errMsg":"","checkCode":"b96667f8","proIcon":"","fileIcon":"","fbaIcon":"","icsmIcon":"","destGisDeptCode":"020Z069","newIcon":null},"message":"SF7444471039325:"}],"contactInfo":null,"clientCode":"XTYSWbTkcFef","serviceList":null}}"
}
 */
