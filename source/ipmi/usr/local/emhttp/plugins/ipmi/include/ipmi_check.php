<?
/* board info */
$boards        = ['ASRock'=>'','ASRockRack'=>'','Supermicro'=>''];
$board         = trim(shell_exec("dmidecode -t 2 | grep 'Manufacturer' | awk -F 'r:' '{print $2}'"));
$board_model   = trim(shell_exec("dmidecode -t 2 | grep 'Product Name' | awk -F 'e:' '{print $2}'"));
$board_status  = array_key_exists($board, $boards);

// create a lockfile for ipmi fan control
$fanctrl_file = "/boot/config/plugins/ipmi/ipmifan";
$fanctrl_lock = (file_exists($fanctrl_file));
if($board_status !== false){
    if(!$fanctrl_lock)
        file_put_contents($fanctrl_file, 'Display Fan Control page if this file exists');
}else{
    if($fanctrl_lock)
        unlink($fanctrl_file);
}
?>