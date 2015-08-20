<?php
// Get any existing copy of our transient data

function tweetlist(){
if ( false === ( $stringarray = get_transient( 'special_query_results' ) ) ) {
ini_set('display_errors', 1);
require_once('TwitterAPIExchange.php');

/** Set access tokens here - see: https://dev.twitter.com/apps/ **/
$settings = array(
    'oauth_access_token' => "238045378-0lcLSnf0268tW4aS6AkXgkOgwnlKk5GOP0i8effj",
    'oauth_access_token_secret' => "PLXY1ka1VKIvwHgxQHGoImM7HZDy1E0OTmD1Ukiq5CXd8",
    'consumer_key' => "JTpSG7gMBTG2Yb7jfHTYHbJdu",
    'consumer_secret' => "EfMzJWZevXSA3HimPcHtGhOCnmfeGot8Hb358ARfKGhtEYJpae"
);



/** Perform a GET request and echo the response **/
/** Note: Set the GET field BEFORE calling buildOauth(); **/
$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
$getfield = '?count=5&user_id=26570972&screen_name=rtCamp';
$requestMethod = 'GET';
$twitter = new TwitterAPIExchange($settings);
$response =  $twitter->setGetfield($getfield)
             ->buildOauth($url, $requestMethod)
             ->performRequest();
	 $arrResults = json_decode($response, true);
    //print_r($arrResults);
	$stringarray = array();
	foreach ($arrResults as $tweet) {
        $user_tweet = $tweet['text'];	
		$string = $user_tweet;
		
if (!preg_match('/[@]/', $string))
{
 $string = preg_replace("/(http:\/\/|(www\.))(([^\s<]{4,68})[^\s<]*)/", '<a href="http://$2$3" target="_blank">$1$2$4</a>', $string);
    $string = preg_replace("/#(\w+)/", "<a href=\"https://twitter.com/search?q=\\1\" target=\"_blank\">#\\1</a>", $string);
  		array_push($stringarray,$string);
}
	}
	
     set_transient( 'special_query_results', $stringarray, 60 );
}

foreach ($stringarray as $tweets) {
echo $tweets."<br/>";
}
 delete_transient( 'special_query_results' );
		}
