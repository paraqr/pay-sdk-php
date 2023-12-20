<?php

namespace paraqr\payment\service;

use paraqr\payment\base\Service;

class Account extends Service {

    /**
     * @param $currencyCode
     * @return array|false|null
     */
    public function query($currencyCode) {
        return $this->request('account.query', [
            'biz_content' => "{\"currency_code\":\"$currencyCode\"}"
        ]);
    }
}
