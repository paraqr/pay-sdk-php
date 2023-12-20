<?php

require_once __DIR__ . "/autoload.php";

use \paraqr\payment\Client;
use \paraqr\payment\base\Code;
use \paraqr\payment\base\Error;

$configs = include __DIR__ . '/config.php';
$trading = Client::Trading($configs);

try {
    $response = $trading->webPay(Code::TRADE_PAY_WEB_DIRECT, [
        'out_trade_no'  => 'T7894562123',
        'subject'       => 'Subject',
        'amount'        => intval(1.2 * 100), // positive integer required
        'notify_url'    => 'https://callback.merchant.com/notify',
        'return_url'    => 'https://callback.merchant.com/return',
        'currency_code' => 'TRY',
        'client_ip'     => '212.156.56.93',
    ]);
    print_r($response);
} catch (Error $e) {
    print_r('Error: ' . $e->getMessage());
}