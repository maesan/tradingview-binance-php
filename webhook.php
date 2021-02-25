<?php
require 'settings.php';
require 'vendor/autoload.php';

// セキュリティーのため、TradingViewのIPからしか受け付けない
$source_ip_list = array(
    '52.89.214.238',
    '34.212.75.30',
    '54.218.53.128',
    '52.32.178.7',
    '127.0.0.1'
);

$source_ip = $_SERVER['REMOTE_ADDR'];

if (in_array($source_ip, $source_ip_list)) {
    // こんな感じのJSONが送られてくる前提
    // {'ticker':'BTCUSDT', 'side':'buyかsell', 'price':'価格'}
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    error_log($json);

    $api = new Binance\API(API_KEY, SECRET_KEY);
    $quantity = 0.001;

    if ($data['side'] == 'buy') {
        $order = $api->buy($data['ticker'], $quantity, 0, "MARKET");
        // $order = $api->buy('BTCUSDT', $quantity, 45000, "LIMIT");
        error_log(print_r($order, true));

    } else if ($data['side'] == 'sell') {
        $order = $api->sell($data['ticker'], $quantity, 0, "MARKET");
        // $order = $api->sell('BTCUSDT', $quantity, 55000, "LIMIT");
        error_log(print_r($order, true));

    }

} else {
    // 指定されたIP以外は403を返す
    header('HTTP', 403);
}