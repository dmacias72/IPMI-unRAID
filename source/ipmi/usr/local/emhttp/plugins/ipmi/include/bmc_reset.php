<?
require_once '/usr/local/emhttp/plugins/ipmi/include/ipmi_options.php';

$cmd1 = "ipmi-sensors -f $netopts";
//exec($cmd1, $output1, $return_var1=null);

$cmd2 = "bmc-device --cold-reset $netopts";
//exec($cmd2, $output2, $return_var2=null);
$return_var2 = true;
if($return_var1 || $return_var2){
    $return = ['error' => $output1.PHP_EOL.$output2,
        'success' => false];
}else{
    $return = ['success' => true];
}

echo json_encode($return);
?>