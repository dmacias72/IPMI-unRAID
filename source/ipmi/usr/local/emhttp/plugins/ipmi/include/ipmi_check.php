<?
/* board info */
$boards = ['ASRock'=>'','ASRockRack'=>'','Supermicro'=>''];
$board  = ( $override == 'disable') ? trim(shell_exec("dmidecode -t 2 | grep 'Manufacturer' | awk -F 'r:' '{print $2}'")) : $oboard;
$board_status  = array_key_exists($board, $boards);
?>