<?php
/**
 * Write by BoWen 2023/9/15 下午3:37
 * Email: gzbw79@163.com
 * 路由查询接口接口-速运类API
 * 顺丰文档：https://open.sf-express.com/Api/ApiDetails?level3=397&interName=%E8%B7%AF%E7%94%B1%E6%9F%A5%E8%AF%A2%E6%8E%A5%E5%8F%A3-EXP_RECE_SEARCH_ROUTES
 */

namespace Gzlbw\sfexpress;

class SearchRoutes extends Handler implements IHandler
{
    protected $serviceCode = 'EXP_RECE_SEARCH_ROUTES';

    public function req(array $msgData, array $options = [])
    {
        $this->timestamp = (int)(microtime(true) * 1000);

        $params = $this->common_params($this->serviceCode);
        $msgData = array_merge($msgData, [
            'language' => 0, // 0=中文
            'trackingType' => 1, // 1=根据顺丰运单号查询,2=根据客户订单号查询
            'methodType' => 1, // 1=标准路由查询，2=定制路由查询
        ]);
        // 合并请求参数
        $params = array_merge($params, ['msgData' => $msgData], $options);
        // 数据签名
        $params['msgDigest'] = $this->sign($params['msgData'], $this->timestamp);
        // 多维数组转成一维
        $this->convert_params($params);
        return $this->fire('/std/service', ['form_params' => $params]);
    }
}