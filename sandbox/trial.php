<pre>

<?php

$time_start = microtime(true);
define('username' , "root");
define('password' , "root");
define('dbname' , "square");
define('servername', "localhost");


class Events{
    public $event_id;
    public $date_w;
    public $repetitions;

    public function __construct($event_id, $repetitions, $date_w){
        $this->event_id = (string) $event_id;
        $this->repetitions = (int) $repetitions;
        $this->date_w = (int) $date_w;
    }

}


function is_location($event_id, $location_to_match){
    $conn = new mysqli(servername, username, password, dbname);    
    $sql = "select event_location from event_data where event_id = '$event_id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            if($row["event_location"] === $location_to_match){
                $conn->close();
                return true;
            }
        }
    }
    $conn->close();
    return false;
}


function is_date($event_id){

    $conn = new mysqli(servername, username, password, dbname);    
    $sql = "select event_date from event_data where event_id = '$event_id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            if($row["event_date"]> date("Y-m-d")){
                $conn->close();
                return true;
            }
        }
    }
    $conn->close();
    return false;
}

function get_date_by_id($event_id){
    $conn = new mysqli(servername, username, password, dbname);    
    $sql = "select event_date from event_data where event_id = '$event_id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            return $row["event_date"];
        }
    }
    $conn->close();
    return -1;
}



$json_string = file_get_contents("D:\\xampp\\htdocs\\sandbox\\file.json");
$json_a = json_decode($json_string, true);
$list_att = array();
foreach($json_a as $key => $value){

    $count_json_a = count($value);
    for($i = 0; $i<$count_json_a;$i++){
        if(isset($value[$i]['EventID'])){

            //2nd arg will be the desired location of user. 

            if(is_location($value[$i]['EventID'],(string)'Wellington') ){
                $con = is_date($value[$i]['EventID']);
                if($con ){
                    $flag = 0;
                    
                    for($j=0; $j<sizeof($list_att);$j++){
                    
                        if($list_att[$j]->event_id == $value[$i]['EventID']){
                            $list_att[$j]->repetitions += 1;
                            $flag = 1;
                        }
                    
                    }
                    if($flag == 0){
                        $start =strtotime((string)  date("Y-m-d"));
                        $end = strtotime((string)get_date_by_id($value[$i]['EventID']));
                        $days_between =(int) floor(abs($end - $start) / 86400);
                        array_push($list_att,new Events($value[$i]['EventID'], 1, $days_between));
                    }
                }
            }
        }
    }
    
}
echo "\n".sizeof($list_att)."\n";


function compare_days($a, $b) { 
    if($a->date_w == $b->date_w) {
        return 0;
    } 
    return ($a->date_w < $b->date_w) ? -1 : 1;
} 


function compare_hits($a, $b) { 
    if($a->repetitions == $b->repetitions) {
        return 0;
    } 
    return ($a->repetitions < $b->repetitions) ? -1 : 1;
}




usort($list_att, 'compare_days');
$sorting_done = array();
$sorted = array();
foreach($list_att as $key=>$value){
    $sort_acc = $value->date_w;
    if(!in_array($sort_acc, $sorting_done)){
        $list_events = array();
        
        foreach($list_att as $key2=>$value2){
            if($value2->date_w === $sort_acc)
                array_push($list_events, $value2);
        }
        array_push($sorting_done, $sort_acc);
        usort($list_events, 'compare_hits');
        $sorted = array_merge($sorted, $list_events);
    }
}

var_dump($sorted);
$time_end = microtime(true);
$time = $time_end - $time_start;
echo 'Execution time : '.$time.' seconds';

?>

</pre>