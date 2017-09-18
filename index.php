<?php
function sanitizeString($var){
	$var = stripslashes($var);
	$var = strip_tags($var);
	$var = htmlentities($var);
	return $var;
}
function cUrl_download($url,$query){
	// Here is the data we will be sending to the service
	$some_data = array(
		'q' => $query,
	);

	$curl = curl_init();
	// $curl = curl_init('http://localhost/echoservice');

	// We POST the data
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $some_data);

	// You can also bunch the above commands into an array if you choose using: curl_setopt_array

	// Send the request
	$result = curl_exec($curl);

	// Free up the resources $curl is using
	curl_close($curl);

	return json_decode($result);
}
function matchTag($arr,$q){
	shuffle($arr);
	$return = $arr[0];
	foreach($arr as $a){
		foreach($a->tags as $t){
			if(strtolower($t) == $q) $return = $a;
		}
	}
	return $return;
}

include_once("keys.php");
$query = sanitizeString( $_GET["q"] );

if($query != ''){
	$result = curl_download("https://soundy.top/api/sounds",$query);
	$find = matchTag($result,$query);
	$soundy = $find->url;

	$result = curl_download("http://api.giphy.com/v1/gifs/search?api_key=".$giphy_api_key."&limit=10&q=".$query,''); 
	$giphy = $result->data[array_rand($result->data)]->images->original_mp4->mp4;
	$giphy = $result->data[array_rand($result->data)]->images->original->url;
}

?><!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en-gb"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en-gb"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en-gb"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en-gb"> <!--<![endif]-->
<head>
	<meta charset="utf-8" />

	<title>Giphy + Sound</title>
	<meta name="robots" content="index, follow" />

	<link rel="shortcut icon" href="favicon.png" type="image/png" />

	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

	<link rel="stylesheet" type="text/css" href="styles.css"/>

	<!--[if IE]>
		<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>
<body <?= ($query=='')?"":"class='search'";?>>
<header>
	<div class="table"><div class="cell">
		<h1>Giphy + Sound</h1>
		<form action="" method="GET">
			<div class="capsule">
				<input placeholder="<?= ($query=='')?"cat":$query;?>" value="<?= ($query=='')?"":$query;?>"  type="text" id="q" name="q" />
				<button>Search</button>
			</div>
		</form>

		<p>Search for something and it will display a giphy with sound.</p>
		<p>An experiment by <a href="http://lochieaxon.com/" target="_blank">Lochie Axon</a></p>
	</div></div>
</header>

<?php if($query != ''){ ?>
<div class="media">
	<div class=""></div>

	<div style="background-image:url(<?=$giphy;?>);" class="main"></div>
	<div style="background-image:url(<?=$giphy;?>);" class="bg"></div>

	<div class="audio">
		<audio autoplay loop controls>
			<source src="<?=$soundy;?>" type="audio/mpeg">
		</audio>
	</div>
<!--
	<div class="main">
		<video width="400" autoplay loop>
			<source src="<?=$giphy;?>" type="video/mp4">
		</video>
	</div>
	<div class="bg">
		<video width="400" autoplay loop>
			<source src="<?=$giphy;?>" type="video/mp4">
		</video>
	</div>
-->
</div>

<?php } ?>
</body>
</html>