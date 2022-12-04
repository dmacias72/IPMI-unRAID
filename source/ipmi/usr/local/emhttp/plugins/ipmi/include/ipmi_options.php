<?
/* read config files */
$plg_path    = '/boot/config/plugins/ipmi';
$cfg_file    = "$plg_path/ipmi.cfg";
if (file_exists($cfg_file))
    $cfg    = parse_ini_file($cfg_file);

/* ipmi network options */
$netsvc    = isset($cfg['NETWORK'])   ? htmlspecialchars($cfg['NETWORK'])   : 'disable';
$ipaddr    = isset($cfg['IPADDR'])    ? htmlspecialchars($cfg['IPADDR'])    : '';
$user      = isset($cfg['USER'])      ? htmlspecialchars($cfg['USER'])      : '';
$password  = isset($cfg['PASSWORD'])  ? htmlspecialchars($cfg['PASSWORD'])  : '';
$override  = isset($cfg['OVERRIDE'])  ? htmlspecialchars($cfg['OVERRIDE'])  : 'disable';
$oboard    = isset($cfg['OBOARD'])    ? htmlspecialchars($cfg['OBOARD'])  : '';
$omodel    = isset($cfg['OMODEL'])    ? htmlspecialchars($cfg['OMODEL'])  : '';
$ocount    = isset($cfg['OCOUNT'])    ? htmlspecialchars($cfg['OCOUNT'])  : '0';
$ignore    = isset($cfg['IGNORE'])    ? htmlspecialchars($cfg['IGNORE'])    : '';
$dignore   = isset($cfg['DIGNORE'])   ? htmlspecialchars($cfg['DIGNORE'])   : '';
$devignore = isset($cfg['DEVIGNORE']) ? htmlspecialchars($cfg['DEVIGNORE']) : '';
$devs      = isset($cfg['DEVS'])      ? htmlspecialchars($cfg['DEVS'])      : 'enable';

/* check if local ipmi driver is loaded */
$ipmi = (file_exists('/dev/ipmi0') || file_exists('/dev/ipmi/0') || file_exists('/dev/ipmidev/0')); // Thanks to ljm42

/* options for network access */

$password_decode = base64_decode($password) ; 

$password_decode = escapeshellarg($password) ;
$netopts = ($netsvc === 'enable') ? '--always-prefix -h '.escapeshellarg($ipaddr).' -u '.escapeshellarg($user).' -p '.
    $password_decode." --session-timeout=5000 --retransmission-timeout=1000" : '';
?>