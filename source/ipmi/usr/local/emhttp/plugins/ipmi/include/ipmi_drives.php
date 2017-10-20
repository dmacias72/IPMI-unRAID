<?php
/* scan directory for type */
function scan_dir($dir, $type = ""){
    $out = array();
    foreach (array_slice(scandir($dir), 2) as $entry){
        $sep   = (preg_match("/\/$/", $dir)) ? "" : "/";
        $out[] = $dir.$sep.$entry ;
    }
    return $out;
}

/* get all hard drives except flash drive */
function get_all_hdds(){
    $hdds = array();
    $flash = preg_replace("/\d$/", "", realpath("/dev/disk/by-label/UNRAID"));
    foreach (scan_dir("/dev/") as $dev) {
        if(preg_match("/[sh]d[a-z]+$/", $dev) && $dev != $flash) {
            $hdds["{$dev}"] = trim(shell_exec("udevadm info --query=property --name={$dev} | grep  ID_SERIAL= | cut -c11-"));
        }
    }
    return $hdds;
}
?>