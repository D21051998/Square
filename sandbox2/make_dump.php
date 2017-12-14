<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "square";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
for( $i=0; $i<100000 ; $i++){
    echo $i."\n";
    $val = mt_rand(1000,2000);
    $sql = "INSERT INTO `test_dump` (`id`, `db_time`, `api_time`, `user_time`, `time_zone`, `anon_id`, `user_id`, `api`, `location_str`, `event_id`, `action`, `submitted_text`, `date_offset`, `search_query`, `scroll_offset`, `events_loaded`) VALUES ($i, '2017-12-12 07:03:15', '2017-12-12 07:03:15 +0000', '2017-12-12 12:33:13 GMT+5:30 IndiaStandardTime', 'IndiaStandardTime', 'WA53060679 335*541', 378, 'Square_getEventDetails', 'Wellington', $val , 'Details', NULL, NULL, NULL, NULL, 'EventName: Simply Meditate ~ EventTimestamp: 2017-12-13 17:30:00')";   
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    }
}
$conn->close();
?>