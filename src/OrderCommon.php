<?php
/**
 * Write by BoWen 2023/9/15 下午3:21
 * Email: gzbw79@163.com
 * 订单相关公共类，用于创建或预创建订单使用
 */

namespace Gzlbw\sfexpress;

abstract class OrderCommon extends Handler
{
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
     * 添加收发信息
     * @param string $type 地址类型： 1，寄件方信息 2，到件方信息
     * @param string $address 详细地址
     * @param string $name 姓名
     * @param string $tel 座机号码（座机和手机只能选一项）
     * @param string $mobile 手机号码（座机和手机只能选一项）
     * @param string $province 省名称
     * @param string $city 城市名称
     * @param string $country 国家或地区代码 例如：内地件CN 香港852 参照顺丰API附录《城市代码表》
     * @param array $options 其它可用参数，参照顺丰下订单接口-速运类API ContactInfo元素文档
     * @return $this
     */
    public function addContractInfo(string $type, string $address, string  $name, string $tel, string $mobile, string $province, string $city, string $country = 'CN', array $options = []) {
        $contractType = ($type == "from") ? 1 : 2;
        $tmp = array_merge($this->contractInfo, [
            'contactType' => $contractType,
            'contact' => $name,
            'address' => $address,
            'tel' => $tel,
            'mobile' => $mobile,
            'province' => $province,
            'city' => $city,
            'county' => $country
        ], $options);

        $tmp = $this->filter_empty($tmp);

        if ($contractType == 1) {
            $this->sender = $tmp;
        } else {
            $this->receiver = $tmp;
        }

        return $this;
    }

    /**
     * 添加货物明细
     * @param $name
     * @param string $count
     * @param array $options
     * @return $this
     */
    public function addCargoDetail(string $name, string $count = '',array $options = []) {
        $tmp = array_merge(compact('name', 'count'), $options);
        $tmp = $this->filter_empty($tmp);
        $this->cargoDetail[] = $tmp;
        return $this;
    }

    /**
     * 设置业务单号
     * @param string $orderId
     * @return $this
     */
    public function setOrderId(string $orderId) {
        $this->order_id = $orderId;
        return $this;
    }

    /**
     * 订单平台类型 （对于平台类客户， 如果需要在订单中 区分订单来源， 则可使用此字段） 天猫:tmall， 拼多多：pinduoduo， 京东 : jd 等平台类型编码
     * @param string $src
     * @return $this
     */
    public function orderSource(string $src) {
        $this->orderSource = $src;
        return $this;
    }
}