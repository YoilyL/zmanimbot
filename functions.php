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
	if (!isset($db)) include_once 'db.php';
}

function getZman($lat, $lon){
	global $db;
	dbcon();
	$res = mysqli_query($db, "SELECT * FROM geo WHERE lat=$lat AND lon=$lon;");
	$indb = mysqli_num_rows($res);
	if ($indb){
		extract(mysqli_fetch_assoc($res));
	} else {
		//get timezone
		
	}
	
	
	
}