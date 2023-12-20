<?php

require_once __DIR__ . "/autoload.php";

use \paraqr\payment\Client;
use \paraqr\payment\base\Error;

$configs = include __DIR__ . '/config.php';
$payment = new Client($configs);

//$raw = file_get_contents("php://input");

// use order created as mock data, just for test
$raw = '{
    "amount":120,
    "app_id":"0756350457437985",
    "nonce":"felehc1un1x7chm5",
    "out_trade_no":"T7894562123",
    "pay_form":"<div class=\"pqr-payment\" id=\"pqr-payment\"><iframe src=\"http:\/\/127.0.0.1:7302\/?trade_no=PQ290909a714ccec5da4e×tamp=1703050769767&nonce=3048fs8r\" frameborder=\"0\" width=\"100%!\"(string=http:\/\/127.0.0.1:7302\/?trade_no=PQ290909a714ccec5da4e×tamp=1703050769767&nonce=3048fs8r) height=\"100%!\"(MISSING)><\/iframe><\/div>",
    "serv_fee":0,
    "sign":"iKx1CJMgx77s6nHVkRaHA97DXegXXjKiMB3ygCPxarY6umYem3xajH75wa51efrCrULdicMuqKGCPYvvkF5ar66z6y7DyZJ4f9",
    "sign_fields":"out_trade_no:trade_no:amount:version:nonce:sign_ver:timestamp",
    "sign_ver":"1",
    "timestamp":1703050769768,
    "trade_no":"PQ290909a714ccec5da4e",
    "version":"1.0"
}';

try {
    $payment->onPaidNotify($raw, function ($data) use ($payment) {
        print_r($data);
        $payment->notifySuccess();
    });
} catch (Error $e) {
    print_r('Error: ' . $e->getMessage());
}