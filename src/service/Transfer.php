<?php

namespace paraqr\payment\service;

use paraqr\payment\base\Error;
use paraqr\payment\base\Service;

class Transfer extends Service {

    /**
     * @param $tradeData
     * @return array|false|null
     * @throws Error
     */
    public function transfer($tradeData) {
        if (empty($tradeData['out_trade_no'])) {
            throw new Error('merchant transaction number is required');
        } elseif (empty($tradeData['subject'])) {
            throw new Error('transaction subject is required');
        } elseif (empty($tradeData['amount'])) {
            throw new Error('amount is required');
        } elseif (empty($tradeData['notify_url'])) {
            throw new Error('asynchronous notification address is required');
        } elseif (empty($tradeData['currency_code'])) {
            throw new Error('currency code is required');
        } elseif (empty($tradeData['iban_no'])) {
            throw new Error('payee’s iban number is required');
        } elseif (empty($tradeData['f_name'])) {
            throw new Error('payee’s first name is required');
        } elseif (empty($tradeData['l_name'])) {
            throw new Error('payee’s last name is required');
        }
        return $this->request('transfer.transfer', [
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
        return $this->request('transfer.query.detail', [
            'biz_content' => "{\"out_trade_no\":\"$outTradeNo\",\"trade_no\":\"$tradeNo\"}"
        ]);
    }
}
