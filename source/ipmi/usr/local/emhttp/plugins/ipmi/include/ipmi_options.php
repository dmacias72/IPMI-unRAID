<?
/* read config files */
$plg_path    = '/boot/config/plugins/ipmi';
$cfg_file    = "$plg_path/ipmi.cfg";
if (is_file($cfg_file))
    $cfg    = parse_ini_file($cfg_file);

/* ipmi network options */
$netsvc   = isset($cfg['NETWORK'])  ? $cfg['NETWORK']  : 'disable';
$ipaddr   = isset($cfg['IPADDR'])   ? $cfg['IPADDR']   : '';
$user     = isset($cfg['USER'])     ? $cfg['USER']     : '';
$password = isset($cfg['PASSWORD']) ? $cfg['PASSWORD'] : '';

/* check if local ipmi driver is loaded */
if($netsvc == 'disable')
    $mod = (file_exists('/dev/ipmi0') || file_exists('/dev/ipmi/0') || file_exists('/dev/ipmidev/0')); // Thanks to ljm42
    //$mod = (trim(shell_exec("modprobe ipmi_si --first-time 2>&1 | grep -q 'Module already in kernel' && echo 1 || echo 0 2> /dev/null")) == 1);

/* options for network access */
$netopts = ($netsvc == 'enable') ? "--always-prefix -h $ipaddr -u $user -p ".
    base64_decode($password)." --session-timeout=5000 --retransmission-timeout=1000" : '';
?>