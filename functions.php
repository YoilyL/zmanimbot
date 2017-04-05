<?php

function ch($url,$content = ''){
	global $ch;
	if (!isset($ch)) $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if (is_array($content)){
    		curl_setopt($ch, CURLOPT_POST, 1);
      	curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch,CURLOPT_TIMEOUT,1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,1); 
        $result = curl_exec($ch);
        //curl_close($ch);
        return json_decode($result,true);
}

function dbcon(){
	global $db;
	if (!isset($db)) $db = mysqli_connect( config['dbhost'],config['dbuser'],config['dbpass'],config['dbname']);
}

function getZman($lat, $lon, $place = ''){
	global $db;
	dbcon();
	$res = mysqli_query($db, "SELECT * FROM geo WHERE lat=$lat AND lon=$lon;");
	if ($db && mysqli_num_rows($res)){
		extract(mysqli_fetch_assoc($res));
	} else {
		$tz = ch("http://api.geonames.org/timezoneJSON?lat=$lat&lng=$lon&username=".config['gnapi']); //get timezone
		$timezone = $tz['timezoneId'];
      	$datestamp = strtotime($tz['time']);
      	$date = date('m/d/Y', $datestamp);
      	$oures = ch("http://db.ou.org/zmanim/getCalendarData.php?mode=day&timezone=$timezone&dateBegin=$date&lat=$lat&lng=$lon"); //get zmanim
      	$names = [
                  'alos_ma' => 'עלות מוקדם',
                  'talis_ma' => 'משיכיר מג"א',
                  'sunrise' => 'נץ',
                  'sof_zman_shema_ma' => 'סוזק"ש מג"א',
                  'sof_zman_shema_gra' => 'סוזק"ש גר"א',
                  'sof_zman_tefila_ma' => 'סו"ז תפילה מג"א',
                  'sof_zman_tefila_gra' => 'סו"ז תפילה גר"א',
                  'chatzos' => 'חצות היום',
                  'mincha_gedola_ma' => 'מנחה גדולה',
                  'mincha_ketana_gra' => 'מנחה קטנה',
                  'plag_mincha_ma' => 'פלג המנחה',
                  'sunset' => 'שקיעה',
                  'tzeis_42_minutes' => 'צאת הכוכבים',
                  'tzeis_72_minutes' => 'לילה לר"ת',
                ];
                if (!$place){
                      $pln = ch("https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$lon&result_type=political&key=".config['gapikey']);
                      $place = $pln['results'][0]['formatted_address'];
                }
                $dateheb =  iconv('WINDOWS-1255', 'UTF-8',jdtojewish(gregoriantojd(date('m', $datestamp), date('d', $datestamp), date('Y', $datestamp)), true, CAL_JEWISH_ADD_GERESHAYIM ));
                print_r($oures);
    
	}
	
	
	
}