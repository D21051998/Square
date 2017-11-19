<pre>
<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "square";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "select event_id from event_data";
$list_ids = array();
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        array_push($list_ids,$row["event_id"]);
    }
}
$conn->close();

$conn = new mysqli($servername, $username, $password, $dbname);
//To Write Locations to database.
// $locations = array();
// array_push($locations, 'Auckland');
// array_push($locations, 'Christ Church');
// array_push($locations, 'Wellington');


// for($i = 0; $i<sizeof($list_ids);$i++){
//     $id = (string)$list_ids[$i];
//     $location = (string) $locations[mt_rand(0,2)];
//     echo "\n".$id." ".$location."\n";
//     $sql = "update event_data set event_location ='$location' where event_id = '$id';";
//     if ($conn->query($sql) === TRUE) {
//         echo "New record created successfully";
//     } else {
//         echo "\nError: " . $sql . "<br>" . $conn->error."\n";
//     }
// }

//To write dates of events to the database.
// $datestart = strtotime('2017-05-01');
// $dateend = strtotime('2017-12-31');
// $daystep = 86400;
// for($i = 0; $i<sizeof($list_att);$i++){
//     $datebetween = abs(($dateend - $datestart) / $daystep);
//     $randomday = rand(0, $datebetween);
//     $ev_date = date("Y-m-d", $datestart + ($randomday * $daystep));
//     $id = (string)$list_att[$i]->event_id;
//     $sql = "insert into event_data values ('$id', '$ev_date');";
//     echo $sql;
//     if ($conn->query($sql) === TRUE) {
//         echo "New record created successfully";
//     } else {
//         echo "Error: " . $sql . "<br>" . $conn->error;
//     }
// }




?>
</pre>