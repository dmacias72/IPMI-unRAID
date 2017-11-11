<?
/* board info */
$boards        = ['ASRock'=>'','ASRockRack'=>'','Supermicro'=>''];
$board         = trim(shell_exec("dmidecode -t 2 | grep 'Manufacturer' | awk -F 'r:' '{print $2}'"));
$board_model   = trim(shell_exec("dmidecode -t 2 | grep 'Product Name' | awk -F 'e:' '{print $2}'"));
$board_status  = array_key_exists($board, $boards);
?>