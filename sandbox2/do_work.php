
<?php
ini_set('display_errors', 'On');
header("Access-Control-Allow-Origin: *");
$time_start = microtime(true);
define('username' , "root");
define('password' , "root");
define('dbname' , "square");
define('servername', "localhost");


class Event{
    public $event_id;
    public $date_w;
    public $date;
    public $repetitions;
    public $sn;
    public $st;
    public $td;
    public $tm;
    
    public function __construct($event_id, $repetitions, $date_w,$date,$sn,$st,$td,$tm){
        $this->event_id = (string) $event_id;
        $this->repetitions = (int) $repetitions;
        $this->date_w = (int) $date_w;
        
        $this->date = (int)$date ;
        $this->sn = (int)$sn ;
        $this->st = (int)$st ;
        $this->td = (int)$td ;
        $this->tm = (int)$tm ;
        
    }

}


function isWeekend($date) {
    return (date('N', strtotime($date)) >= 6);
}

function getAnonymousData($location_to_search){
    $list = array();
    $conn = new mysqli(servername, username, password, dbname);    
    $todate = date("Y-m-d");
    $sql = "select distinct test_dump.event_id, event_data.event_date, event_data.event_location from test_dump, event_data where event_data.event_location='$location_to_search' and event_data.event_id = test_dump.event_id and event_data.event_date>='$todate';";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {

            array_push($list, $row["event_id"]);
        }
    }
    $conn->close();

    return $list;
}


function setObjects($list){
    $conn = new mysqli(servername, username, password, dbname);    
    $todate = date("Y-m-d");
    $tomdate = date("Y-m-d", strtotime("1 day"));
    $satdate = date("Y-m-d", strtotime("next Saturday"));
    $sundate = date("Y-m-d", strtotime("next Sunday"));
    $big_list = array();
    $list_td = array();
    $list_tm  = array();
    $list_st = array();
    $list_sn = array();
    $list_other = array();    
    $events = array();
    for($i = 0; $i<sizeof($list);$i++){
        $id = $list[$i];
        $sql = "select count(test_dump.event_id), event_data.event_date from test_dump, event_data where event_data.event_id = '$id' and event_data.event_id = test_dump.event_id;";
        $result = $conn->query($sql);
        $flag_td = 0;
        $flag_tm = 0;
        $flag_st = 0;
        $flag_sn = 0;
        $hits = 0;
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                if($row["event_date"] == $todate){
                    $flag_td = 1;
                }
                if($row["event_date"] == $tomdate){
                    $flag_tm = 1;
                }
                if($row["event_date"] == $satdate){
                    $flag_st = 1;
                }
                if($row["event_date"] == $sundate){
                    $flag_sn = 1;
                }
                
                $start =strtotime((string)  date("Y-m-d"));
                $end = strtotime((string)$row["event_date"] );
                $days_between =(int) floor(abs($end - $start) / 86400);                
                $hits = $row["count(test_dump.event_id)"];

                $end = strtotime((string)$row["event_date"] );
                array_push($events,new Event($id,$hits,$days_between,$row["event_date"],$flag_sn,$flag_st,$flag_td,$flag_tm));
                if($flag_td == 1){
                    $list_td[] = new Event($id,$hits,$days_between,$row["event_date"],$flag_sn,$flag_st,$flag_td,$flag_tm);
                }
                if($flag_tm == 1){
                    $list_tm[] = new Event($id,$hits,$days_between,$row["event_date"],$flag_sn,$flag_st,$flag_td,$flag_tm);
                }
                if($flag_st == 1){
                    $list_st[] = new Event($id,$hits,$days_between,$row["event_date"],$flag_sn,$flag_st,$flag_td,$flag_tm);
                }
                if($flag_sn == 1){
                    $list_sn[] = new Event($id,$hits,$days_between,$row["event_date"],$flag_sn,$flag_st,$flag_td,$flag_tm);
                }
                if($flag_td == 0 && $flag_sn==0 && $flag_st==0 && $flag_tm==0){
                    $list_other[] = new Event($id,$hits,$days_between,$row["event_date"],$flag_sn,$flag_st,$flag_td,$flag_tm);
                }
            }
            
        }

        
    }
   
    $conn->close();
    $big_list[] = $events;
    $big_list[] = $list_td;
    $big_list[] = $list_tm;
    $big_list[] = $list_st;
    $big_list[] = $list_sn;
    $big_list[] = $list_other;
    return $big_list;
}

function compare_hits($a, $b) { 
    if($a->repetitions == $b->repetitions) {
        return 0;
    } 
    return ($a->repetitions > $b->repetitions) ? -1 : 1;
}
function compare_days($a, $b) { 
    if($a->date_w == $b->date_w) {
        return 0;
    } 
    return ($a->date_w < $b->date_w) ? -1 : 1;
} 



$list = getAnonymousData('Auckland');
$list = setObjects($list);

$list_td = $list[1];
$list_tm  = $list[2];
$list_st = $list[3];
$list_sn = $list[4];
$list_other = $list[5];



$final_list = array();
usort($list_td,'compare_hits');
usort($list_tm,'compare_hits');
usort($list_st,'compare_hits');
usort($list_sn,'compare_hits');
$final_list = array_merge($final_list, $list_td);
$final_list = array_merge($final_list, $list_tm);
$final_list = array_merge($final_list, $list_st);
$final_list = array_merge($final_list, $list_sn);


usort($list_other, 'compare_days');
$sorting_done = array();
$sorted = array();
foreach($list_other as $key=>$value){
    $sort_acc = $value->date_w;
    if(!in_array($sort_acc, $sorting_done)){
        $list_events = array();
        
        foreach($list_other as $key2=>$value2){
            if($value2->date_w === $sort_acc)
                array_push($list_events, $value2);
        }
        array_push($sorting_done, $sort_acc);
        usort($list_events, 'compare_hits');
        $sorted = array_merge($sorted, $list_events);
    }
}
$final_list = array_merge($final_list, $sorted);

$new_array = array();
foreach($final_list as $single_event){
   array_push($new_array, $single_event->event_id);
}

$my_json = json_encode($new_array);
$final_json = "{\"event_id\":".$my_json."}";
// echo sizeof($final_list);
echo $final_json;
return $final_json;


?>
