<?php
/**
 * Write by BoWen 2023/9/15 下午1:53
 * Email: gzbw79@163.com
 * 下单接口
 * 顺丰文档：https://open.sf-express.com/Api/ApiDetails?level3=393&interName=%E4%B8%8B%E8%AE%A2%E5%8D%95%E6%8E%A5%E5%8F%A3-EXP_RECE_CREATE_ORDER
 */

namespace Gzlbw\sfexpress;

class CreateOrder extends OrderCommon implements IHandler
{
    protected $serviceCode = 'EXP_RECE_CREATE_ORDER';

    /**
     * 联系信息
     * @var array
     */
    protected $contractInfo = [
        'contactType' => 1,
        'company' => '',
        'contact' => '',
        'tel' => '',
        'mobile' => '',
        'zoneCode' => '',
        'country' => '',
        'province' => '',
        'city' => '',
        'county' => '',
        'address' => '',
        'postCode' => '',
        'email' => '',
        'taxNo' => ''
    ];

    /**
     * 订单号
     * @var string
     */
    protected $order_id = '';

    /**
     * 发件人
     * @var array
     */
    protected $sender = [];

    /**
     * 收件人
     * @var array
     */
    protected $receiver = [];

    /**
     * 订单原始信息
     * @var
     */
    protected $orderSource;

    /**
     * 货物详情
     * @var array
     */
    protected $cargoDetail = [];

    /**
     * 是否需求分配运单号1：分配 0：不分配（若带单号下单，请传值0）
     * @var int
     */
    protected $isGenWaybillNo = 1;

    /**
     * @param array $msgData 业务数据，参照顺丰文档 下订单接口-速运类API 元素<请求> Order
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