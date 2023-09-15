<?php
/**
 * Write by BoWen 2023/9/15 下午3:19
 * Email: gzbw79@163.com
 * 预下单接口 - 客户通过传入地址信息等参数，校验是否能下单成功
 * 顺丰文档：https://open.sf-express.com/Api/ApiDetails?level3=392&interName=%E9%A2%84%E4%B8%8B%E5%8D%95%E6%8E%A5%E5%8F%A3-EXP_RECE_PRE_ORDER
 */

namespace Gzlbw\sfexpress;

class PreCreateOrder extends OrderCommon implements IHandler
{
    protected $serviceCode = 'EXP_RECE_PRE_ORDER';

    /**
     * @param array $msgData 业务数据，参照顺丰文档 预下单接口-速运类API 元素<请求> Order
     * @param array $options 其它请求参数
     * @return mixed
     * @throws \Exception
     */
    public function req(array $msgData, array $options = [])
    {
        $this->timestamp = $this->get_time();
        $common = $this->common_params($this->serviceCode);
        // 业务数据
        $msgData = array_merge($msgData, [
            'orderId' => $this->order_id,
            'cargoDetails' => $this->cargoDetail,
            'contactInfoList' => [
                $this->sender,
                $this->receiver
            ],
            'orderSource' => $this->orderSource,
            'isGenWaybillNo' => $this->isGenWaybillNo
        ]);
        // 合并请求参数
        $params = array_merge($common, ['msgData' => $msgData], $options);
        // 数据签名
        $params['msgDigest'] = $this->sign($params['msgData'], $this->timestamp);
        // 多维数组转成一维
        $this->convert_params($params);
        return $this->fire('/std/service', ['form_params' => $params]);
    }
}