<?php
function get_sign($params, $secret_key){
    ksort($params);
    $pre_sign_ls = array();
    foreach ($params as $key => $val){
        array_push($pre_sign_ls, "$key=$val");
    }
    array_push($pre_sign_ls, "secret_key=$secret_key");
    $pre_sign_str =  join("&", $pre_sign_ls);
    return strtoupper(md5($pre_sign_str));
}

function send_request($url, $params, $sign){
    $query = http_build_query($params);
    $url = "$url?$query";
    $headers = [
        'authorization:'.$sign,
    ];
    $ch = curl_init($url);
    curl_setopt($ch,CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output=curl_exec($ch);
    return $output;
}
$tonce = round(microtime(true) * 1000);
$url = "https://api.coinex.com/v1/balance/info";
$access_id = ""; //access_id
$secret_key = ""; //secret_key


$params = array(
    "access_id" => $access_id,
    "tonce" => $tonce,
);
$sign = get_sign($params, $secret_key);
$json=json_decode(send_request($url, $params, $sign),true);
echo $json['data']['USDT']['available'];
?>
