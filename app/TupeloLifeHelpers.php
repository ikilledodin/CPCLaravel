<?php

if (! function_exists('tupe_prepareResult')) {
   function tupe_prepareResult($status, $data, $errors,$msg)
   {
       return ['status' => $status,'data'=> $data,'message' => $msg,'errors' => $errors];
   }
}

if (! function_exists('tupe_analyzedevsupport')) {
   function tupe_analyzedevsupport($devsupport)
   {
       // return ['status' => $status,'data'=> $data,'message' => $msg,'errors' => $errors];
		$value = $devsupport;
		$device = array (1,2,4,8,16,32,64);
		$devstr = array('fitbit','jawbone','mymo','Google Fit','Health Kit','Garmin','S Health');
		$i = 0;
		$devicesupport = array();
		foreach ($device as $devop) {
			$result = $value & $devop;
		//		print "result: ".$result." res: ".$res.PHP_EOL;
			$key = $devstr[$i];
		//		print "key: $key".PHP_EOL;
			$result = $result > 0 ? 1:0;
			$devicesupport[] = array("device"=>$key,"value"=>$result);
		//	$devicesupport["$key"] = $result;
			$i++;
		}

		return $devicesupport;
   }
}

if (! function_exists('tupe_analyzefeatures')) {
   function tupe_analyzefeatures($feature_num)
   {
       // return ['status' => $status,'data'=> $data,'message' => $msg,'errors' => $errors];
		$value = $feature_num;
		$features = array (1,2,4,8,16,32);
		$featurestr = array('GroupChat','PushNotification','Rewards','Calendar','MyWeight','InfoStream');
		$i = 0;
		$featuresarray = array();
		foreach ($features as $devop) {
			$result = $value & $devop;
	//		print "result: ".$result." res: ".$res.PHP_EOL;
			$key = $featurestr[$i];
	//		print "key: $key".PHP_EOL;
			$result = $result > 0 ? 1:0;
			$featuresarray[] = array("feature"=>$key,"value"=>$result);
		//	$devicesupport["$key"] = $result;
			$i++;
		}
		
		return $featuresarray;
   }
}

if (! function_exists('tupe_analyzedatashow')) {
   function tupe_analyzedatashow($datashow)
   {
	    $value = $datashow;
		$device = array (1,2,4,8);
		$devstr = array('steps','active-calories','totaldistance','floors-climbed');
		$i = 0;
		$devicesupport = array();
		foreach ($device as $devop) {
			$result = $value & $devop;
			$key = $devstr[$i];
			$result = $result > 0 ? 1:0;
			$devicesupport[] = array("device"=>$key,"value"=>$result);
			$i++;
		}
		
		return $devicesupport;
   }
}
if (! function_exists('hmm_devices_decode_serial')) {
	function hmm_devices_decode_serial($serial){

		$serial = intval($serial);
		$serial = (~$serial) & 0xFFFFFFFF;
		$r = ($serial >> 8) & 0xFF;
		$r |= ($serial << 8) & 0xFF00;
		$r |= ($serial >> 8) & 0xFF0000;
		$r |= ($serial << 8) & 0xFF000000;
		$r = $r ^ 0xA2E9324C;
		return $r;
	}
}
if (! function_exists('hmm_devices_encode_serial')) {
	function hmm_devices_encode_serial($serial){

		$serial = $serial ^ 0xA2E9324C;
		$r = ($serial >> 8) & 0xFF;
		$r |= ($serial << 8) & 0xFF00;
		$r |= ($serial >> 8) & 0xFF0000;
		$r |= ($serial << 8) & 0xFF000000;
		$r = (~$r) & 0xFFFFFFFF;
		
		return $r;
	}
}

if (! function_exists('hmm_devices_decode_serial_hrm')) {
	function hmm_devices_decode_serial_hrm($serial){

		$serial = intval($serial);
		$serial = (~$serial) & 0xFFFFFFFF;
		$r = ($serial >> 8) & 0xFF;
		$r |= ($serial << 8) & 0xFF00;
		$r |= ($serial >> 8) & 0xFF0000;
		$r |= ($serial << 8) & 0xFF000000;
		$r = $r ^ 0xE2E9324C;
		return $r;
	}
}

if (! function_exists('hmm_devices_encode_serial_hrm')) {
	function hmm_devices_encode_serial_hrm($serial){

		$serial = $serial ^ 0xE2E9324C;
		$r = ($serial >> 8) & 0xFF;
		$r |= ($serial << 8) & 0xFF00;
		$r |= ($serial >> 8) & 0xFF0000;
		$r |= ($serial << 8) & 0xFF000000;
		$r = (~$r) & 0xFFFFFFFF;
		
		return $r;
	}

}

if (! function_exists('tupe_search_id')) {

	function tupe_search_id($leaderdata,$param,$key) {
		$index = 0;
		$retindex = 0;
		$found = 0;
		
	//	error_log("searching for: $param\n",3,"/tmp/search.log");
		
		if (count($leaderdata) > 0) {
			foreach($leaderdata as $value) {
				if($value[$key] == $param) {
			//		error_log("found : $param\n",3,"/tmp/search.log");
					$found = 1;
					break;
				}
				$index++;
			}
		}
		
		if($found) {
			$index += 1;
			$retindex = $index;
		//	error_log("found item in index: $index\n",3,"/tmp/search.log");
		}
		
	//	error_log("index is: $retindex\n",3,"/tmp/search.log");
		return $retindex;
	}


}
if (! function_exists('tupe_getAgeGroupName')) {
	function tupe_getAgeGroupName($ageGroup) {
		$agegroupname = '';
	
		switch ($ageGroup) {
			case 1:
			$agegroupname .= '30 and under';
			break;
			case 2:
			$agegroupname .= '31 to 40';
			break;
			case 3:
			$agegroupname .= '41 to 50';
			break;
			case 4:
			$agegroupname .= '51 to 60';
			break;
			case 5:
			$agegroupname .= '61 to 70';
			break;
			case 6:
			$agegroupname .= 'over 70';
			break;
		}
		return $agegroupname;
	}
}
