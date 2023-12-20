<?php

namespace paraqr\payment\service;

use paraqr\payment\base\Code;
use paraqr\payment\base\Error;
use paraqr\payment\base\Service;
use paraqr\payment\base\CardInfo;

class Trading extends Service {

    /**
     * @var string[]
     */
    protected $payMethods = [
        Code::TRADE_PAY_WEB_DIRECT,
        Code::TRADE_PAY_WEB_HPP,
        Code::TRADE_PAY_WEB_3D_PAY,
    ];

    /**
     * @param $tradeData
     * @return array|false|null
     * @throws Error
     */
    public function webPayDirect($tradeData) {
        return $this->webPay(Code::TRADE_PAY_WEB_DIRECT, $tradeData);
    }

    /**
     * @param $tradeData
     * @return array|false|null
     * @throws Error
     */
    public function webPayHpp($tradeData) {
        return $this->webPay(Code::TRADE_PAY_WEB_HPP, $tradeData);
    }

    /**
     * @param $tradeData
     * @return array|false|null
     * @throws Error
     */
    public function webPay3dPay($tradeData) {
        return $this->webPay(Code::TRADE_PAY_WEB_3D_PAY, $tradeData);
    }

    /**
     * @param string $method
     * @param $tradeData
     * @return array|false|null
     * @throws Error
     */
    public function webPay(string $method, $tradeData) {
        if (!in_array($method, $this->payMethods)) {
            throw new Error('pay method not supported.');
        } elseif (empty($tradeData['out_trade_no'])) {
            throw new Error('merchant transaction number is required');
        } elseif (empty($tradeData['subject'])) {
            throw new Error('transaction subject is required');
        } elseif (empty($tradeData['amount'])) {
            throw new Error('amount is required');
        } elseif (empty($tradeData['notify_url'])) {
            throw new Error('asynchronous notification address is required');
        } elseif (empty($tradeData['return_url'])) {
            throw new Error('synchronize redirect address is required');
        } elseif (empty($tradeData['currency_code'])) {
            throw new Error('currency code is required');
        } elseif (empty($tradeData['client_ip'])) {
            throw new Error('user client ip is required');
        } elseif (!is_int($tradeData['amount']) || $tradeData['amount'] <= 0) {
            throw new Error('amount must be a positive integer');
        }

        if (!empty($tradeData['card_info']) && $tradeData['card_info'] instanceof CardInfo) {
            $tradeData['card_info'] = $tradeData['card_info']->toArray();
        }
        return $this->request($method, [
            'biz_content' => json_encode($tradeData, JSON_UNESCAPED_UNICODE)
        ]);
    }

    /**
     * @param string|null $outTradeNo
     * @param string|null $tradeNo
     * @return array|false|null
     * @throws Error
     */
    public function query(string $outTradeNo = null, string $tradeNo = null) {
        if (empty($outTradeNo) && empty($tradeNo)) {
            throw new Error('`out_trade_no` or `trade_no` at least one of these two parameters is required');
        }
        return $this->request('trade.query.order', [
            'biz_content' => "{\"out_trade_no\":\"$outTradeNo\",\"trade_no\":\"$tradeNo\"}"
        ]);
    }

    /**
     * @param string $description
     * @param string|null $outTradeNo
     * @param string|null $tradeNo
     * @return array|false|null
     * @throws Error
     */
    public function close(string $description, string $outTradeNo = null, string $tradeNo = null) {
        if (empty($outTradeNo) && empty($tradeNo)) {
            throw new Error('`out_trade_no` or `trade_no` at least one of these two parameters is required');
        }
        return $this->request('trade.close', [
            'biz_content' => "{\"out_trade_no\":\"$outTradeNo\",\"description\":\"$description\"}"
        ]);
    }

    /**
     * @param string $description
     * @param string|null $outTradeNo
     * @param string|null $tradeNo
     * @return array|false|null
     * @throws Error
     */
    public function cancel(string $description, string $outTradeNo = null, string $tradeNo = null) {
        if (empty($outTradeNo) && empty($tradeNo)) {
            throw new Error('`out_trade_no` or `trade_no` at least one of these two parameters is required');
        }
        return $this->request('trade.cancel', [
            'biz_content' => "{\"out_trade_no\":\"$outTradeNo\",\"description\":\"$description\"}"
        ]);
    }

    /**
     * @param $refundInfo
     * @return array|false|null
     * @throws Error
     */
    public function refund($refundInfo) {
        if (empty($refundInfo['out_trade_no']) && empty($refundInfo['trade_no'])) {
            throw new Error('`out_trade_no` or `trade_no` at least one of these two parameters is required');
        } elseif (empty($refundInfo['description'])) {
            throw new Error('refund description is required');
        } elseif (empty($refundInfo['amount'])) {
            throw new Error('refund amount is required');
        } elseif (empty($refundInfo['out_refund_no'])) {
            throw new Error('merchant refund trade number is required');
        }

        return $this->request('trade.refund.refund', [
            'biz_content' => json_encode($refundInfo, JSON_UNESCAPED_UNICODE)
        ]);
    }

    /**
     * @param string $outRefundNo
     * @param string|null $outTradeNo
     * @param string|null $tradeNo
     * @return array|false|null
     * @throws Error
     */
    public function refundDetail(string $outRefundNo, string $outTradeNo = null, string $tradeNo = null) {
        if (empty($outTradeNo) && empty($tradeNo)) {
            throw new Error('`out_trade_no` or `trade_no` at least one of these two parameters is required');
        }

        return $this->request('trade.refund.detail', [
            'biz_content' => "{\"out_trade_no\":\"$outTradeNo\",\"out_refund_no\":\"$outRefundNo\"}"
        ]);
    }

    /**
     * @param string|null $outTradeNo
     * @param string|null $tradeNo
     * @return array|false|null
     * @throws Error
     */
    public function refundQuery(string $outTradeNo = null, string $tradeNo = null) {
        if (empty($outTradeNo) && empty($tradeNo)) {
            throw new Error('`out_trade_no` or `trade_no` at least one of these two parameters is required');
        }
        return $this->request('trade.refund.query', [
            'biz_content' => "{\"out_trade_no\":\"$outTradeNo\",\"trade_no\":\"$tradeNo\"}"
        ]);
    }
}
