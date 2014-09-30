<?php

$giphy_key = 'dc6zaTOxFJmzC';
$word = 'gifit';
$username = 'smygif';
$icon = ':suspect:';
$slack_key = 'YOUR_KEY_HERE';

function checkSize($size){
	if ($size < 900 && $size != 0){
		return true;
	}

	return false;
}

if (isset($_POST['token'])){

	if ($_POST['token'] == $slack_key){

		$searchword = urlencode(str_replace($word.' ', '', $_POST['text']));

		$output = file_get_contents('http://api.giphy.com/v1/gifs/search?q='.$searchword.'&api_key='.$giphy_key.'&limit=50');

		$json = json_decode($output);

		if (count($json->data) < 1){
			
			$return = array(
				'username' => $username,
				'icon_emoji' => $icon,
				'text' => ':troll: Sorry '.$_POST['user_name'].', no results'
			);

		} else {

			$number = rand(0, count($json->data));
			
			while (!checkSize($json->data[$number]->images->original->size/1024)){

				$number = rand(0, count($json->data));
			}

			$return = array(
				'username' => $username,
				'icon_emoji' => $icon,
				'text' => '<'.$json->data[$number]->images->fixed_height->url.'>',
				'size' => $json->data[$number]->images->original->size/1024
			);

		}

		header('Content-Type: application/json');
		echo json_encode($return);

	}
}

?>