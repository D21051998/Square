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
$list_ids = array();
$sql = "select distinct(event_id) from test_dump";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        array_push($list_ids,$row["event_id"]);
    }
}

// $locations = array('Auckland','Christ Church','Wellington');
// for($i = 0; $i<sizeof($list_ids);$i++){
//     $id = (string)$list_ids[$i];
//     $location = (string) $locations[mt_rand(0,2)];
//     echo "\n".$id." ".$location."\n";
//     $sql = "insert into event_data (event_id, event_location) values ('$id','$location')";
//     if ($conn->query($sql) === TRUE) {
//         echo "New record created successfully";
//     } else {
//         echo "\nError: " . $sql . "<br>" . $conn->error."\n";
//     }
// }
//To write dates of events to the database.
$datestart = strtotime('2017-12-01');
$dateend = strtotime('2017-12-31');
$daystep = 86400;
for($i = 0; $i<sizeof($list_ids);$i++){
    $datebetween = abs(($dateend - $datestart) / $daystep);
    $randomday = rand(0, $datebetween);
    $ev_date = date("Y-m-d", $datestart + ($randomday * $daystep));
    $id = (string)$list_ids[$i];
    $sql = "update event_data set event_date = '$ev_date' where event_id='$id';";
    echo $sql;
    if ($conn->query($sql) === TRUE) {  
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
$conn->close();
?>