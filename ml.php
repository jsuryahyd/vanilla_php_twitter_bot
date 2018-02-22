<?php
use MonkeyLearn\client as MonkeyLearnClient;
require 'vendor/autoload.php';
include 'index.php';
include 'ml.php';

//sentiment analysis with monkeylearn

//instantiate with api key from account : https://app.monkeylearn.com/main/my-account/tab/api-keys/
$ml = new MonkeyLearnClient($keys['codebird_monkeylearn_key']);

/*
- get a response of each text(amounts to a query for each tweet)
- @module_id(here tweet analysis),@array of texts/tweets
- don't know what third parameter is for.
*/
$analysis_array = $ml->classifiers->classify('cl_qkjxv9Ly',$tweet_texts,false);

// echo '<pre>',print_r($analysis_array),'</pre>'; //the response was saved to mlResponse.txt for reference.
