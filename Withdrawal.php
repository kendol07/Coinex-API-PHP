<?php
function get_sign($params, $secret_key){
    ksort($params);
    $pre_sign_ls = array();
    foreach ($params as $key => $val){
        array_push($pre_sign_ls, "$key=$val");
    }
    array_push($pre_sign_ls, "secret_key=$secret_key");
    $pre_sign_str =  join("&", $pre_sign_ls);
    //echo "$pre_sign_str\n";
    return strtoupper(md5($pre_sign_str));
}

function send_request($url, $params, $sign){
    $headers = [
        'authorization:'.$sign,
        'Content-type: application/json'
    ];
    
    $params=json_encode($params);
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch,CURLOPT_HTTPHEADER, $headers);
    $output=curl_exec($ch);
    return $output;
}
$tonce = round(microtime(true) * 1000);
$url = "https://api.coinex.com/v1/balance/coin/withdraw";
$access_id = ""; //access_id 
$secret_key = ""; //secret_key

//Replace value (actual_amount,coin_address,coin_type)
//coin_address Need approbation in Coinex Plataform
$params = array(
    "access_id" => $access_id,
    "actual_amount" => "1",
    "coin_type" => "ETC",
    "coin_address" => "0xf9ab3fcbe90027f62c6393eb553434343dw",
    "tonce" => $tonce,
    "transfer_method" => "onchain",
);
$sign = get_sign($params, $secret_key);
send_request($url, $params, $sign);
?>
