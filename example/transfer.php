<?php

require_once __DIR__ . "/autoload.php";

use \paraqr\payment\Client;
use \paraqr\payment\base\Error;

$configs = include __DIR__ . '/config.php';
$transfer = Client::Transfer($configs);

try {
    $response = $transfer->transfer([
        'out_trade_no'  => 'T7894562188',
        'subject'       => 'Transfer Test',
        'amount'        => 100,  // positive integer required
        'notify_url'    => 'https://callback.merchant.com/notify',
        'currency_code' => 'TRY',
        'iban_no'       => 'TR04 0006 4000 0011 1111 2345 56',
        'f_name'        => 'AccountFirstName',
        'l_name'        => 'AccountLastName',
    ]);
    print_r($response);
} catch (Error $e) {
    print_r('Error: ' . $e->getMessage());
}