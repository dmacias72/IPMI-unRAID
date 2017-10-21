<?
/* fan control settings */
$fancfg_file = "$plg_path/fan.cfg";
if (file_exists($fancfg_file))
    $fancfg = parse_ini_file($fancfg_file);
$fanctrl    = isset($fancfg['FANCONTROL']) ? htmlspecialchars($fancfg['FANCONTROL']) :'disable';
$fanpoll    = isset($fancfg['FANPOLL'])    ? intval($fancfg['FANPOLL'])              : 6;
$hddpoll    = isset($fancfg['HDDPOLL'])    ? intval($fancfg['HDDPOLL'])              : 18;
$hddignore  = isset($fancfg['HDDIGNORE'])  ? $fancfg['HDDIGNORE']                    : '';
$harddrives = isset($fancfg['HARDDRIVES']) ? $fancfg['HARDDRIVES']                   : 'enable';

$fanip   = (isset($fancfg['FANIP']) && ($netsvc === 'enable')) ? htmlspecialchars($fancfg['FANIP']) : htmlspecialchars($ipaddr) ;

/* board info */
$boards        = ['ASRock'=>'','ASRockRack'=>'','Supermicro'=>''];
$board         = trim(shell_exec("dmidecode -t 2 | grep 'Manufacturer' | awk -F 'r:' '{print $2}'"));
$board_model   = trim(shell_exec("dmidecode -t 2 | grep 'Product Name' | awk -F 'e:' '{print $2}'"));
$board_status  = array_key_exists($board, $boards);

if($board !== 'Supermicro'){
    $cmd_count = (intval(trim(shell_exec("/usr/bin/lscpu | grep 'Socket(s):' | awk '{print $2}'"))) < 2) ? 0 : 1;
    $board_file = "$plg_path/board.json";
    $board_file_status = (file_exists($board_file));
    $board_json = ($board_file_status) ? json_decode((file_get_contents($board_file)), true) : [];
}else{
    $cmd_count = 0;
    $board_file_status = true;
    $board_json = [ 'Supermicro' =>
            [ 'raw'   => '00 30 70 66 01',
              'auto'  => '00 30 45 01',
              'full'  => '00 30 45 01 01',
              'fans'  => [
                'FAN1234' => '00',
                'FANA' => '01'
              ]
        ]
    ];
}

// fan network options
$fanopts = ($netsvc === 'enable') ? '-h '.escapeshellarg($fanip).' -u '.escapeshellarg($user).' -p '.
    escapeshellarg(base64_decode($password)).' --session-timeout=5000 --retransmission-timeout=1000' : '';
?>