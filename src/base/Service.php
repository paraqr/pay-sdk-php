<?php

namespace paraqr\payment\base;

use paraqr\payment\util\Curl;
use paraqr\payment\util\Signature;

class Service {

    /**
     * @var string
     */
    protected $version = '1.0';

    /**
     * @var string
     */
    protected $signVer = '1';

    /**
     * @var null|string
     */
    protected $appId = null;

    /**
     * @var null|string
     */
    protected $appPriKey = null;

    /**
     * @var null|string
     */
    protected $pqrPubKey = null;

    /**
     * @var string
     */
    protected $gateway = 'https://gateway.paraqr.com/gateway.do';

    /**
     * @var mixed|null
     */
    protected $config = null;

    /**
     * @param $config
     */
    public function __construct($config = null) {
        $this->setConfig($config);
    }

    /**
     * @param $config
     * @return void
     */
    public function setConfig($config) {
        if (!empty($config['app_id'])) {
            $this->appId = $config['app_id'];
        }
        if (!empty($config['app_secret_key'])) {
            $this->appPriKey = $config['app_secret_key'];
        }
        if (!empty($config['paraqr_pub_key'])) {
            $this->pqrPubKey = $config['paraqr_pub_key'];
        }
        if (!empty($config['gateway'])) {
            $this->gateway = $config['gateway'];
        }
        $this->config = $config;
    }

    /**
     * @return array|null
     */
    public function getConfig() {
        return $this->config;
    }

    /**
     * @param string $method
     * @param array|string|null $data
     * @return array|false|null
     */
    public function request(string $method, $data = null) {
        $data = $this->signature($method, $data);
        $response = Curl::instance()->setHeader(['Content-Type' => 'application/json'])
            ->xhr("POST", $this->gateway, json_encode($data), function ($res, $httpCode) {
                if ($httpCode != 200) {
                    throw new Error(Curl::instance()->lastError());
                }
                return $res;
            });
        return $response ? json_decode($response, true) : false;
    }

    /**
     * @param $method
     * @param $data
     * @return mixed
     */
    public function signature($method, &$data) {
        $data['method'] = $method;
        $data['app_id'] = $this->appId;
        $data['sign_ver'] = $this->signVer;
        $data['version'] = $this->version;
        $data['nonce'] = $this->nonceStr(16);
        $data['timestamp'] = round(microtime(true) * 1000);

        $bizContent = $data['biz_content'] ?? '';
        $document = "app_id={$this->appId}&biz_content=$bizContent&method=$method&nonce={$data['nonce']}&sign_ver={$this->signVer}&timestamp={$data['timestamp']}&version={$data['version']}";
        $data['sign'] = Signature::instance()->sign($document, $this->appPriKey);
        return $data;
    }

    /**
     * @param $data
     * @param array|string $signFields
     * @return bool
     * @throws Error
     */
    public function checkSign($data, $signFields): bool {
        if (empty($data["sign"])) {
            return false;
        }

        $document = '';
        if (is_string($signFields)) {
            $signFields = explode(':', $signFields);
        }
        foreach ($signFields as $field) {
            if (isset($data[$field])) {
                $document .= $data[$field];
            }
        }
        return Signature::instance()->verify($document, $data["sign"], $this->pqrPubKey);
    }

    /**
     * @param int|null $length
     * @return string
     */
    public function nonceStr(int $length): string {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
}
