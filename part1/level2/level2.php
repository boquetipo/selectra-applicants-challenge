<?php

//get json contents
$string = file_get_contents("./data.json");
//json to array
$json = json_decode($string, true);
//bills array
$bills = [];
//temporary providers array
$providers = [];
//temporary users array
$users = [];

if(!is_array($json['contracts']) || count($json['contracts'])<1){
	 throw new Exception('There are no contracts.');
}

if(!is_array($json['providers']) || count($json['providers'])<1){
	 throw new Exception('There are no providers.');
}

if(!is_array($json['users']) || count($json['users'])<1){
	 throw new Exception('There are no users.');
}

//fill temporary array to fast access price_per_kwh
$providers = array_column($json['providers'], 'price_per_kwh', 'id');

//fill temporary array to fast access yearly_consumption
$users = array_column($json['users'], 'yearly_consumption', 'id');

//calculate bills
foreach ($json['contracts'] as $k => $contract) {
	$contracts = [];
	$contracts[$user['id']][] = [
		'yearly_consumption' => $users[$contract['user_id']],
		'discount' => getDiscount($contract['contract_length']),
		'price_per_kwh' => $providers[$contract['provider_id']]
	];
}


foreach ($contracts as $k => $contracts_user) {
	$price = 0;

	foreach ($contracts_user as $j => $concract) {
		$price += $concract['yearly_consumption'] * $contract['price_per_kwh'];
	}
	
	$final_price = round(($price * $discount) / 100, 2);

	$bill = [];
	$bill['id'] = $k+1;
	$bill['user_id'] = $contract['user_id'];
    $bill['price'] = $final_price;
    $bills[] = $bill;
}

if(count($bills)>0){
	$output = json_encode(["bills" => $bills]);
	file_put_contents("./output.json", $output);
}


function getDiscount($lenght){
	switch(lenght){
		case 0:
		case 1:
			return 10;
		case 2:
		case 3:
			return 20;
		break;
		default:
			return 25;
	}
}