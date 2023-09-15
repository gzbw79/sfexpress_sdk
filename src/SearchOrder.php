<?php
/**
 * Write by BoWen 2023/9/15 下午3:32
 * Email: gzbw79@163.com
 * 订单结果查询接口-速运类API
 * 顺丰文档：https://open.sf-express.com/Api/ApiDetails?level3=396&interName=%E8%AE%A2%E5%8D%95%E7%BB%93%E6%9E%9C%E6%9F%A5%E8%AF%A2%E6%8E%A5%E5%8F%A3-EXP_RECE_SEARCH_ORDER_RESP
 */

namespace Gzlbw\sfexpress;

class SearchOrder extends Handler implements IHandler
{
    protected $serviceCode = 'EXP_RECE_SEARCH_ORDER_RESP';

    /**
     * 请求接口
     * @param array $msgData
     * @param array $options
     * @return mixed
     * @throws \Exception
     */
    public function req(array $msgData, array $options = [])
    {
        $this->timestamp = (int)(microtime(true) * 1000);

        $params = $this->common_params($this->serviceCode);
        // 合并请求参数
        $params = array_merge($params, ['msgData' => $msgData], $options);
        // 数据签名
        $params['msgDigest'] = $this->sign($params['msgData'], $this->timestamp);
        // 多维数组转成一维
        $this->convert_params($params);
        return $this->fire('/std/service', ['form_params' => $params]);
    }

    /**
     *
     * @param string $orderId 业务订单编号
     * @param int $searchType 查询类型：1正向单 2退货单
     * @param string $language 响应报文的语言， 缺省值为zh-CN，目前支持以下值zh-CN 表示中文简体， zh-TW或zh-HK或 zh-MO表示中文繁体， en表示英文
     * @return mixed
     */
    public function data(string $orderId, $searchType = 1, $language = 'zh-CN') {
        return $this->req([
            'orderId' => $orderId,
            'searchType' => $searchType,
            'language' => $language
        ]);
    }
}