<?php
/**
 * Write by BoWen 2023/9/15 下午4:01
 * Email: gzbw79@163.com
 * 云打印面单打印2.0接口-面单类API
 */

namespace Gzlbw\sfexpress;

class CloudPrintWaybills extends Handler implements IHandler
{
    protected $serviceCode = 'COM_RECE_CLOUD_PRINT_WAYBILLS';

    /**
     * 业务数据
     * @var array
     */
    protected $documents = [];

    public function req(array $msgData, array $options = [])
    {
        $this->timestamp = $this->get_time();

        // 获取公共请求参数
        $params = $this->common_params($this->serviceCode);
        // 合并请求参数
        $msgData = array_merge($msgData, [
            'version' => '2.0',
            'documents' => $this->documents
        ]);
        $params = array_merge($params, ['msgData' => $msgData], $options);
        // 数据签名
        $params['msgDigest'] = $this->sign($params['msgData'], $this->timestamp);
        // 多维数组转成一维
        $this->convert_params($params);
        return $this->fire('/std/service', ['form_params' => $params]);
    }

    /**
     * 除主运单号是必须外其余为条件
     * 一批不要超过20个运单，字段定义参考 2.3.1 模板固定字段
     * @param string $master_no 主运单号(必须)
     * @param array $options 其余参数，参考顺丰文档
     */
    public function addWayBills(string $master_no, array $options = []) {
        $this->documents[] = array_merge([
            'masterWaybillNo' => $master_no,
        ], $options);

        return $this;
    }
}