<?php

//get json contents
$string = file_get_contents("./data.json");
//json to array
$json = json_decode($string, true);
//bills array
$bills = [];
//temporary providers array
$providers = [];

if(!is_array($json['providers']) || count($json['providers'])<1){
	 throw new Exception('There are no providers.');
}

if(!is_array($json['users']) || count($json['users'])<1){
	 throw new Exception('There are no users.');
}

//fill temporary array to fast access
foreach ($json['providers'] as $provider) {
	$providers[$provider['id']] = $provider['price_per_kwh'];
}

//calculate bills
foreach ($json['users'] as $k => $user) {
	$bill = [];
	$bill['id'] = $k+1;
	$bill['user_id'] = $user['id'];
    $bill['price'] = $user['yearly_consumption'] * $providers[$user['provider_id']];
    $bills[] = $bill;
}

if(count($bills)>0){
	$output = json_encode($bills);
	file_put_contents("./output.json", $output);
}
