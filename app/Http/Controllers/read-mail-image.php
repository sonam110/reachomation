<?php
header("Content-Type: image/jpeg"); // it will return image 
loggen("trigger");
loggen($_REQUEST['id']);
readfile("https://reachomation.com/b-slide1.jpg");



function loggen($log_msg)
{
    $log_filename = "log";
    $log_file_data = $log_filename.'/log_' . date('d-M-Y') . '.log';
    file_put_contents($log_file_data, $log_msg . "\n", FILE_APPEND);
} 

?>
