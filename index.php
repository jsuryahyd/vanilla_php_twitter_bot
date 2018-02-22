<?php
echo "keys included in to this index.php";
use Codebird\Codebird;

require 'vendor/autoload.php';
include 'keys.php';

//consumer key and secret key respectively.
Codebird::setConsumerKey($keys['codebird_key'],$keys['codebird_secret']);
//get a singleton instance
$cb = Codebird::getInstance();//class Codebird{private $instance=NULL;function getInstance(){if(!self::$instance){$self::$instance=new Codebird();return self::$instance;}}};

//all return values of $cb, by default are objects. lets get in array format
$cb->setReturnFormat(CODEBIRD_RETURNFORMAT_ARRAY);

/*
- since this is not authentication app, the access tokens are to be set here only (not in client side)
- Access Token and access token secret
- Note : Not good practice to set these tokens (consumer secret and access token secret in main code.)
- todo : remove these tokens before pushing this to github
*/
$cb->setToken($keys['twitter_access_token'],$keys['twitter_token_secret']);

/*Action
- function calls are at par with twitter api - just convert underscore syntax of api to camelcases
- more info at https://github.com/jublonet/codebird-php#mapping-api-methods-to-codebird-function-calls
*/
//this function takes last id as argument
try{
    $mentions = $cb->statuses_mentionsTimeline();

}catch (Exception $e){
    echo '<pre>',print_r($e),'</pre>';
    exit;
}

//so $mentions is array containing my tweets and http,rate so to get pure posts
$mentions = array_filter($mentions,function($item,$key){return isset($item['id']);},ARRAY_FILTER_USE_BOTH);

if(!count($mentions) >0){
    return;
}
$tweets =[];
foreach($mentions as $key=>$value){
    $tweets[]= array('id'=>$value['id'],'user_screen_name'=>$value['user']['name'],'text'=>$value['text'],'time'=>$value['created_at']);
};

// echo '<pre>',print_r($tweets),'</pre>';
/*
- now that we got tweets, lets extract each tweet text and send them to monkeylearn
*/
$tweet_texts = array_map(function($tweet){
    return $tweet['text'];
},$tweets);
// echo '<pre>',print_r($tweet_texts),'</pre>';
