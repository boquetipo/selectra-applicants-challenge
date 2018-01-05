<?php

if (isset($argv[1])) {
    if (pathinfo($argv[1], PATHINFO_EXTENSION) != "json") {
        echo "This is not a valid json file.\n";
    } elseif (isset($argv[2])) {
        if (isset($argv[3])) {
            outputGeneration($argv[3], $argv[1]);
        } else {
            echo "ERROR. Use parameter '-t' (json or human)";
        }
    } else {
        echo "ERROR. Use parameter '-t' (json or human)";
    }
} else {
    echo "ERROR. Use 'data.json' file.\n";
}

function outputJSON($param, $jsonFile) {
    switch ($param) {
        case 'json':
            echo file_get_contents($jsonFile);
            break;
        case 'human':
            echo humanOutout($jsonFile);
            break;
        default:
            echo "Unknown value";
            break;
    }
}

function humanOutout ($jsonFile) {
	$data = json_decode(file_get_contents($jsonFile), true);

	if(count($data)<1){
		throw new Exception("Not a valid file");
	}

    $output = "Number of bills: " . count($data) . "\n\n";
    foreach($data as $k => $bill) {
        $output .= "Bill #" . $k . ":\n";
        $output .= "    User: " . $bill['user_id'] . "\n";
        $output .= "    Price: " . $bill['price'] . "\n";
        $output .= "    Provider fees: " . $bill['commission']['insurance_fee'] . ".\n";
        $output .= "    Insurance fees: " . $d['commission']['provider_fee'] . ".\n";
        $output .= "    Selectra fees: " . $d['commission']['selectra_fee'] . ".\n\n";
    }

    return $output;
}