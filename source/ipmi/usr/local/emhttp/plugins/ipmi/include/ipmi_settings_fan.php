<?
require_once '/usr/local/emhttp/plugins/ipmi/include/ipmi_check.php';

/* fan control settings */
$fancfg_file = "$plg_path/fan.cfg";
if (file_exists($fancfg_file))
    $fancfg = parse_ini_file($fancfg_file);
$fanctrl    = isset($fancfg['FANCONTROL']) ? htmlspecialchars($fancfg['FANCONTROL']) :'disable';
$fanpoll    = isset($fancfg['FANPOLL'])    ? intval($fancfg['FANPOLL'])              : 6;
$hddpoll    = isset($fancfg['HDDPOLL'])    ? intval($fancfg['HDDPOLL'])              : 18;
$hddignore  = isset($fancfg['HDDIGNORE'])  ? htmlspecialchars($fancfg['HDDIGNORE'])  : '';
$harddrives = isset($fancfg['HARDDRIVES']) ? htmlspecialchars($fancfg['HARDDRIVES']) : 'enable';
$range      = 64;

$fanip   = (isset($fancfg['FANIP']) && ($netsvc === 'enable')) ? htmlspecialchars($fancfg['FANIP']) : htmlspecialchars($ipaddr) ;

/* board info */
if($board === 'ASRock' || $board === 'ASRockRack'){
    //if board is ASRock
    //check number of physical CPUs
    if( $override == 'disable')
      $cmd_count = (intval(trim(shell_exec("/usr/bin/lscpu | grep 'Socket(s):' | awk '{print $2}'"))) < 2) ? 0 : 1;
    else
      $cmd_count = $ocount;

    $board_file = "$plg_path/board.json";
    $board_file_status = (file_exists($board_file));
    $board_json = ($board_file_status) ? json_decode((file_get_contents($board_file)), true) : [];
}elseif($board === 'Supermicro'){
    //if board is Supermicro
    $cmd_count = 0;
    $board_model = ( $override == 'disable') ? intval(shell_exec("dmidecode -qt2|awk -F: '/^\tProduct Name:/{p=\$2} END{print substr(p,3,1)}'")) : $omodel;
    $board_file_status = true;
    if($board_model == '9'){
        $range = 255;
        $board_json = [ 'Supermicro' =>
                [ 'raw'   => '00 30 91 5A 3',
                  'auto'  => '00 30 45 01',
                  'full'  => '00 30 45 01 01',
                  'fans'  => [
                    'FAN1234' => '10',
                    'FANA' => '11'
                  ]
            ]
        ];
    }else{
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
}elseif($board === 'Dell'){
    $board_json = [ 'Dell' =>
            [ 'raw'    => '00 30 30 02 FF',
              'auto'   => '00 30 30 01 01',
              'manual' => '00 30 30 01 00',
              'full'   => '00 30 30 02 FF 64',
              'fans'   => [
                'FAN1234' => '00',
              ]
        ]
    ];
}

// fan network options base64_decode(
$fanopts = ($netsvc === 'enable') ? '-h '.escapeshellarg($fanip).' -u '.escapeshellarg($user).' -p '.
    escapeshellarg($password).' --session-timeout=5000 --retransmission-timeout=1000' : '';
?>