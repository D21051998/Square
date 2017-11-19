<pre>
<?php

class Events{
    public $event_id;
    public $date_w;

    public function_construct($event_id){
        $this->event_id = (string) $event_id;
    }

}
function clean_list($map){
    // Database Logic to check if the id is on specfic date and location.
    return $map;
}
$json_string = file_get_contents("D:\\xampp\\htdocs\\sandbox\\file.json");
$json_a = json_decode($json_string, true);
$list_att = array();
foreach($json_a as $key => $value){

    $count_json_a = count($value);
    for($i = 0; $i<$count_json_a;$i++){
        if(isset($value[$i]['EventID'])){
            //var_dump($value[$i]['EventID']);
            array_push($list_att,$value[$i]['EventID']);
        }
    }
    
}
//var_dump($list_att);
$map = array();
foreach($list_att as $item){
    if(array_key_exists($item, $map)){
        $map[$item] = $map[$item]+1;
    }else{
        $map[$item] = 1;
    }
}

//var_dump($map);
$map = clean_list($map);
krsort($map);
var_dump($map);
?>
</pre>