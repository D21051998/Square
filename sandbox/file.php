<?php

$my_file = 'file.json';
$handle = fopen($my_file, 'a') or die('Cannot open file:  '.$my_file);


for($i = 0;$i<100000;$i++){
    $data = '{
        "Timestamp": "2017-10-12 11:10:35 +0000",
        "UserTimeZone":"IndiaStandardTime",
        "AnonymousID": "WA95625080 768*541",
        "UserID": "",
        "API version":"Square",
        "EventID":"'.mt_rand(1000,2000) .'",
        "EventName":"My Gig",
        "EventTimestamp":"2017-11-24 20:00:00"
    },';
    fwrite($handle, $data);
}




?>