<?php

require 'index.php';
require_once 'ml.php';

//server,username,password,dbname
$db = new mysqli('localhost','root','','twitter_tracking');

// echo '<pre>',print_r($tweets),'</pre>';
//reply emojis
$happyEmojis = ["U+1F600", "U+1F601", "U+1F602", "U+1F923", "U+1F603", "U+1F604", "U+1F605", "U+1F606", "U+1F609", "U+1F60A", "U+1F60B", "U+1F60E", "U+1F60D", "U+1F618", "U+1F617", "U+1F619", "U+1F61A", "U+263A", "U+1F642", "U+1F917", "U+1F929"];
$sadEmojis = ["U+1F641", "U+1F616", "U+1F61E", "U+1F61F", "U+1F624", "U+1F622", "U+1F62D", "U+1F626", "U+1F627", "U+1F628", "U+1F629", "U+1F92F", "U+1F62C", "U+1F630", "U+1F631", "U+1F633", "U+1F92A", "U+1F635", "U+1F621"];
$neutralEmojis = ["U+1F914", "U+1F928", "U+1F610", "U+1F611", "U+1F636", "U+1F644", "U+1F60F", "U+1F623", "U+1F625", "U+1F62E", "U+1F910", "U+1F62F", "U+1F62A", "U+1F62B", "U+1F634", "U+1F60C", "U+1F61B", "U+1F61C", "U+1F61D", "U+1F924", "U+1F612", "U+1F613", "U+1F614", "U+1F615", "U+1F643", "U+1F911", "U+1F632"];

foreach ($tweets as $key=>$tweet){
    //determine which emoji to send
    $analysis = strtolower($analysis_array->result[$key][0]['label']);
     switch ($analysis){
         case 'positive':
            $emojiset = $happyEmojis;
            break;
        case 'negative':
            $emojiset = $sadEmojis;
            break;
        case 'neutral':
            $emojiset = $neutralEmojis;
            break;
    }

     //reply - send tweet
$cb->statuses_update(array(
//refer by name and append a random emoji from analysed category(positive,negative,..etc.)
'status' => '@'.$tweet['user_screen_name']." ".html_entity_decode($emojiset[rand(0,count($emojiset)-1)],0,'UTF-8'),
'in_reply_to_status_id'=>$tweet['id']));


     //since reply is sent, save this tweet as latest in database.
     $save_query = $db->prepare('insert into tweet_ids (tweet_id,date_tweeted,ml_analysis) values (?,?,?)');
     $d =date("Y-m-d H:i:s");
     $save_query->bind_param('iss',$tweet['id'],$d,$analysis);
     $save_query->execute();


}

echo $db->affected_rows;
