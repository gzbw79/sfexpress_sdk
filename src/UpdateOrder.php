<?php
/**
 * Write by BoWen 2023/9/15 下午3:47
 * Email: gzbw79@163.com
 * 订单确认/取消接口-速运类API
 */

namespace Gzlbw\sfexpress;

class UpdateOrder extends Handler implements IHandler
{
    protected $serviceCode = 'EXP_RECE_UPDATE_ORDER';

    protected $waybillNoInfoList = [];

    public function req(array $msgData, array $options = [])
    {
        $this->timestamp = $this->get_time();

        // 获取公共请求参数
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
     * 取消订单
     * @param string $order_no 业务单号
     * @param string $express_no 快递单号
     * @param int $type 运单号类型 1：母单 2 :子单 3 : 签回单
     * @param array $msgData 其它业务参数
     */
    public function cancel(string $order_no, string $express_no = '', int $type = 1, array $msgData = []) {
        if (!empty($express_no)) {
            $this->waybillNoInfoList[] = ['waybillType' => $type, 'waybillNo' => $express_no];
        }

        return $this->req(array_merge([
            'dealType' => 2,
            'language' => 'zh-CN',
            'orderId' => $order_no,
            'waybillNoInfoList' => $this->waybillNoInfoList
        ], $msgData));
    }

    /**
     * 确认订单
     * @param string $order_no 业务单号
     * @param string $express_no 快递单号
     * @param int $type 运单号类型 1：母单 2 :子单 3 : 签回单
     * @param array $msgData 其它业务参数
     */
    public function confirm(string $order_no, string $express_no = '', int $type = 1, array $msgData = []) {
        if (!empty($express_no)) {
            $this->waybillNoInfoList[] = ['waybillType' => $type, 'waybillNo' => $express_no];
        }

        return $this->req(array_merge([
            'dealType' => 1,
            'language' => 'zh-CN',
            'orderId' => $order_no,
            'waybillNoInfoList' => $this->waybillNoInfoList
        ], $msgData));
    }
}