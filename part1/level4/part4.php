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
foreach ($json['providers'] as $provider) {
	$providers[$provider['id']] = $provider['price_per_kwh'];
}


//fill temporary array to fast access yearly_consumption
foreach ($json['users'] as $user) {
	$users[$user['id']] = $user['yearly_consumption'],
}

//calculate bills
foreach ($json['contracts'] as $k => $contract) {
	$contracts = [];
	$contracts[$user['id']][] = [
		'yearly_consumption' => $users[$contract['user_id']],
		'discount' => getDiscount($contract['contract_length']),
		'contract_length' => $contract['contract_length'],
		'provider_id' => $contract['provider_id'],
		'price_per_kwh' => $providers[$contract['provider_id']]
	];
}


foreach ($contracts as $k => $contracts_user) {
	$price = 0;

	foreach ($contracts_user as $j => $concract) {
		$price += $concract['yearly_consumption'] * $contract['price_per_kwh'];

		//check if green
		if($contracts['green']){
			$price -= ($concract['yearly_consumption'] * 0.05);
		}
	}
	
	$final_price = round(($price * $discount) / 100, 2);

	$bill = [];
	$bill['id'] = $k+1;
	$bill['user_id'] = $contract['user_id'];
    $bill['price'] = $final_price;

    $bills[] = $bill;
}

//check commissions
if(count($bills)>0){
	foreach ($bills as $k => $bill) {
		$insurance_fee = ($contract['contract_length'] * 365 * 0.05) //TODO what about leap year?

		$selectra_fee = $price - ($price * 0.125);

		$commission = [
	    	"insurance_fee" => $insurance_fee,
	        "provider_fee" => ($price - $selectra_fee),
	        "selectra_fee" => $selectra_fee
	    ];

	    $bills[$k]['commission'] = $commission;
	}

	$output = json_encode($bills);
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

function getSelectraFee($price, $contract_length){
	return $contract_length * 365 * 0.05; //TODO leap year
}