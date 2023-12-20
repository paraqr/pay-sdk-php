<?php

namespace paraqr\payment;

use paraqr\payment\base\Error;
use paraqr\payment\base\Service;
use paraqr\payment\service\Trading;
use paraqr\payment\service\Account;
use paraqr\payment\service\Transfer;

/**
 * Class Client
 * @method static Trading   Trading(array $config)
 * @method static Account   Account(array $config)
 * @method static Transfer  Transfer(array $config)
 */
class Client extends Service {

    /**
     * @return void
     */
    public function notifySuccess() {
        echo "SUCCESS";
    }

    /**
     * @return void
     */
    public function notifyFailed() {
        echo "FAILED";
    }

    /**
     * @param $notifyData array|string
     * @param $callback callable
     * @return mixed
     * @throws Error
     */
    public function onPaidNotify($notifyData, callable $callback = null) {
        if (is_string($notifyData)) {
            $notifyData = json_decode($notifyData, true);
        }
        if (!$this->checkSign($notifyData, $notifyData['sign_fields'] ?? '')) {
            throw new Error('invalid paid notify signature');
        }
        if ($callback && is_callable($callback)) {
            return call_user_func_array($callback, [$notifyData]);
        }
        return null;
    }

    /**
     * @param string|array $notifyData
     * @param callable $callback
     * @return mixed
     * @throws Error
     */
    public function onRefundedNotify($notifyData, callable $callback = null) {
        return $this->onPaidNotify($notifyData, $callback);
    }

    /**
     * @param string $name
     * @param $config
     * @return mixed
     * @throws Error
     */
    protected static function load(string $name, $config = null) {
        $service = __NAMESPACE__ . "\\service\\{$name}";
        if (!class_exists($service)) {
            throw new Error("class `{$service}` not exists");
        }
        return new $service($config);
    }

    /**
     * @param string $name
     * @param $config
     * @return mixed
     * @throws Error
     */
    public static function __callStatic(string $name, $config = null) {
        return self::load($name, ...$config);
    }
}
