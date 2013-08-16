<?php
	header("Access-Control-Allow-Origin: *");
	ini_set("memory_limit","1G");
	
	// if there POST was set...
	if(isset($_POST) &&
	   !empty($_POST)){

		// if the required key => value pairs are present...
		if(isset($_POST['key']) && !empty($_POST['key']) &&
		   isset($_POST['img']) && !empty($_POST['img'])){
		   	$key = $_POST['key'];
		   	$base64String = urldecode($_POST['img']);
		   	$url = $_POST['url'];
		   	$title = $_POST['title'];
		   	$domain = $_POST['domain'];
		   	$referrer = $_POST['referrer'];

		   	//get the timestamp for the filename
		   	$DateTime = new DateTime();
		   	$filename = $DateTime->format(DateTime::ISO8601);

		   	//decode the image and save it
		   	$base64String = str_replace('data:image/png;base64,', '', $base64String);
		   	$image = base64_decode($base64String);
		   	file_put_contents("history/images/$filename.png", $image);
			echo "Image saved as " . $filename;

			//save the metadata in a sidecart JSON file
			$json = new stdClass();
			$json->title = $title;
			$json->domain = $domain;
			$json->url = $url;
			if($referrer != "") $json->referrer = $referrer;
			$json->ip = $_SERVER['REMOTE_ADDR'];
			if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) $json->forward_from = $_SERVER['HTTP_X_FORWARDED_FOR'];
			$json = json_encode($json);
			file_put_contents("history/metadata/$filename.json", $json);

		}else echo get_error("invalid POST values");
	}

	function get_error($error_message){
		return "{ \"error\" : \"$error_message\" }";
	}

?>