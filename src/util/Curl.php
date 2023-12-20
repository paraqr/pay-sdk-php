<?php

namespace paraqr\payment\util;

use paraqr\payment\base\Singleton;

class Curl {
    use Singleton;

    /**
     * @var string
     */
    protected $userAgent = '';

    /**
     * @var int
     */
    protected $timeoutSec = 10;

    /**
     * @var bool
     */
    protected $hasHost = false;

    /**
     * @var bool
     */
    protected $getHeader = false;

    /**
     * @var string
     */
    protected $cookie = '';

    /**
     * @var bool
     */
    protected $noBody = false;

    /**
     * @var bool
     */
    protected $notFollow = false;

    /**
     * @var bool
     */
    protected $getRedirectUrl = false;

    /**
     * @var string
     */
    protected $lastError = null;

    /**
     * @var bool
     */
    protected $reset = true;

    /**
     * @var array
     */
    protected $header = [];

    /**
     * construct
     */
    public function __construct() {
        if (!empty($_SERVER['HTTP_USER_AGENT'])) {
            $this->userAgent = $_SERVER['HTTP_USER_AGENT'];
        }
    }

    /**
     * @param int $sec
     * @return $this;
     */
    public function setTimeout($sec) {
        $this->timeoutSec = $sec;
        return $this;
    }

    /**
     * @param string $str
     * @return $this;
     */
    public function setUserAgent($str) {
        $this->userAgent = $str;
        return $this;
    }

    /**
     * @param array $value
     * @return $this
     */
    public function setHeader($value) {
        $header = $value;
        if (is_array($value) && !isset($value[0])) {
            $header = [];
            foreach ($value as $k => $v) {
                $header[] = "$k:$v";
            }
        }
        $this->header = $header;
        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    public function setCookie($value) {
        $this->cookie = $value;
        return $this;
    }

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options) {
        foreach ($options as $option) {
            $this->$option = true;
        }
        return $this;
    }

    /**
     * @param string $method
     * @param string $url
     * @param array|string $data
     * @param callable $callback
     * @return mixed
     */
    public function xhr($method, $url, $data = null, $callback = null) {
        if (is_callable($data))
            list($data, $callback) = [$callback, $data];

        $method = strtoupper($method);
        if (!empty($data) && ($method === 'GET' || $method === 'DELETE')) {
            $data = (is_array($data) ? http_build_query($data) : $data);
            $url .= (strpos($url, '?') ? '&' : '?') . $data;
            $data = null;
        }

        $ch = curl_init();
        if (!empty($this->userAgent))
            curl_setopt($ch, CURLOPT_USERAGENT, $this->userAgent);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeoutSec);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        //带上cookie访问
        if (!empty($this->cookie)) {
            curl_setopt($ch, CURLOPT_COOKIE, $this->cookie);
        }

        curl_setopt($ch, CURLOPT_HEADER, $this->getHeader);
        curl_setopt($ch, CURLOPT_NOBODY, $this->noBody);
        if (!empty($this->header)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header);
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);

        if (!empty($data)) {
            $data = (is_array($data) ? http_build_query($data) : $data);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        if (strpos($url, 'https://') === 0) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        }
        if ($this->hasHost)
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, !$this->notFollow);

        $content = curl_exec($ch);
        $info = curl_getinfo($ch);
        if ($this->getHeader) {
            $content = explode("\n", trim($content));
        }

        if ($this->getRedirectUrl) {
            $content['redirect_url'] = $info['redirect_url'];
        }

        $this->lastError = curl_error($ch);
        curl_close($ch);

        if ($this->reset) {
            $this->hasHost =
            $this->getHeader =
            $this->notFollow =
            $this->noBody =
            $this->getRedirectUrl =
            $this->cookie = '';
            $this->timeoutSec = 10;
            $this->header = [];
        }

        if (is_callable($callback)) {
            return call_user_func($callback, $content, $info['http_code']);
        }
        return $content;
    }

    /**
     * @param $boolean
     * @return $this
     */
    public function reset($boolean) {
        if (!is_bool($boolean)) {
            foreach ($boolean as $key => $b) {
                $this->$key = $b;
            }
        } else {
            $this->reset = $boolean;
        }
        return $this;
    }

    /**
     * @return string|null
     */
    public function lastError() {
        return $this->lastError;
    }
}
