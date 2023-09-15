<?php
/**
 * Write by BoWen 2023/9/15 下午1:38
 * Email: gzbw79@163.com
 *
 */

namespace Gzlbw\sfexpress;

use GuzzleHttp\Client;

class Handler
{
    /**
     * 是否沙盒环境
     * @var bool
     */
    protected $sandbox = false;

    /**
     * 顾客编码
     * @var string
     */
    protected $app_id = '';

    /**
     * 校验码
     * @var string
     */
    protected $app_key = '';

    /**
     * 服务代码
     * @var string
     */
    protected $serviceCode = '';

    /**
     * 生产地址
     * @var string
     */
    protected $product_uri = 'https://bspgw.sf-express.com';

    /**
     * 沙盒地址
     * @var string
     */
    protected $sandbox_uri = 'https://sfapi-sbox.sf-express.com';

    /**
     * 临时目录
     * @var string
     */
    protected $temp_path = '';

    /**
     * 请求时间戳
     * @var int
     */
    protected $timestamp;

    /**
     * 数据签名
     * @var string
     */
    protected $sign;

    /**
     * Handler constructor.
     * @param string $app_id 客户编号
     * @param string $app_key 应用验证码(接口签名需要)
     * @param bool $sandbox 是否沙盒模式
     * @param string $temp_path 临时目录路径
     */
    public function __construct(string $app_id, string $app_key, bool $sandbox = false, string $temp_path = '')
    {
        $this->app_id = $app_id;
        $this->app_key = $app_key;
        $this->sandbox = $sandbox;

        $this->temp_path = (empty($temp_path)) ? __DIR__ . "/../temp" : $temp_path;
        if (!file_exists($this->temp_path)) {
            @mkdir($this->temp_path, 0755, true);
        }
    }

    /**
     * 数据签名
     * @param array $data 需要参与签名的数据
     * @param int $timestamp 时间戳
     * @return string
     */
    protected function sign(array $data, int $timestamp = 0) {
        $this->timestamp = ($timestamp > 0) ? $timestamp : $this->get_time();
        $encode = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $sign = sprintf("%s%s%s", $encode, $this->timestamp, $this->app_key);
        $sign = urlencode($sign);
        $sign = md5($sign, true);
        return base64_encode($sign);
    }

    /**
     * 创建UUID
     * @return string
     */
    protected function create_uuid() {
        $chars = md5(uniqid(mt_rand(), true));
        return implode("-", [
            substr ( $chars, 0, 8 ),
            substr ( $chars, 8, 4 ),
            substr ( $chars, 12, 4 ),
            substr ( $chars, 16, 4 ),
            substr ( $chars, 20, 12 )
        ]);
    }

    /**
     * 转换参数，将参数中属于数组的要转成 json
     * @param $params
     * @return void
     */
    public function convert_params(&$params) {
        foreach ($params as &$vo) {
            if (is_array($vo)) {
                $vo = json_encode($vo, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            }
        }
    }

    /**
     * 获取 access_token
     * @param bool $update 是否取最新 token
     * @return string
     */
    public function access_token(bool $update = false) {
        $cache_file = $this->temp_path . "/" . $this->app_id . "_token.json";
        if (file_exists($cache_file) && $update === false) {
            $token = json_decode(file_get_contents($cache_file), true);
            if ((int)$token['expire_at'] > time()) return $token['accessToken'];
        }

        $client = new Client($this->client_config());

        $request = $client->post('/oauth2/accessToken', [
            'query' => [
                'partnerID' => $this->app_id,
                'secret' => $this->app_key,
                'grantType' => 'password'
            ]
        ]);

        /**
         * 成功报文
         * {
                "apiResultCode": "A1000",
                "apiErrorMsg": "success",
                "apiResponseID": "000180E0AC18933F963C52701B18C03F",
                "accessToken": "20D02FC4F63B4A4AA7C9A236EAD5B0A1",
                "expiresIn": 5150
            }
         */
        $body = (string)$request->getBody();
        $response = json_decode($body, true);

        if (empty($response['accessToken'])) {
            throw new \Exception($body);
        }

        // 写入缓存
        $response = array_merge($response, ['expire_at' => time() + $response['expiresIn'] - 20]);
        file_put_contents($cache_file, json_encode($response, JSON_UNESCAPED_UNICODE));

        return $response['accessToken'];
    }

    /**
     * 统一请求方法
     * @param string $uri_path 请求路径
     * @param array $options 请求参数
     * @param string $method 请求方法
     */
    public function fire(string $uri_path, array $options, string $method = 'post') {
        $client = new Client($this->client_config());
        $request = $client->request($method, $uri_path, $options);

        $body = (string)$request->getBody();
        $response = json_decode($body, true);

        if (!$response) throw new \Exception($body);

        return $response;
    }

    /**
     * 组合公共参数
     * @param string $service_code
     * @param bool $update_token
     * @return array
     */
    public function common_params(string $service_code, bool $update_token = false) {
        $token = $this->access_token($update_token);
        $request_id = $this->create_uuid();

        return [
            'partnerID' => $this->app_id,
            'requestID' => $request_id,
            'serviceCode' => $service_code,
            'timestamp' => $this->timestamp,
            'accessToken' => $token,
            'msgDigest' => '',
            'msgData' => ''
        ];
    }

    /**
     * 客户端HTTP通用参数
     * @return array
     */
    private function client_config() {
        return [
            'base_uri' => ($this->sandbox) ? $this->sandbox_uri : $this->product_uri,
            'verify' => false,
            'headers' => [
                'Content-type' => 'application/x-www-form-urlencoded;charset=UTF-8'
            ]
        ];
    }

    /**
     * 返回时间戳
     * @return false|float
     */
    public function get_time() {
        return ceil(microtime(true) * 1000);
    }

    /**
     * 过滤空值参数
     * @param $data
     * @return array
     */
    protected function filter_empty($data) {
        return array_filter($data, function ($v, $k) {
            return !empty($v);
        }, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * 请求接口
     * @param array $msgData
     * @param array $options
     * @return mixed
     * @throws \Exception
     */
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
}