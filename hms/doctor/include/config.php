<?php
// define('DB_SERVER','localhost');
// define('DB_USER','queu_cristel');
// define('DB_PASS' ,'3t9BPa9@hx81m9es');
// define('DB_NAME', 'queu_hospital');
define('DB_SERVER','localhost');
define('DB_USER','root');
define('DB_PASS' ,'');
define('DB_NAME', 'hms');
$con = mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
// Check connection
if (mysqli_connect_errno())
{
 echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
?>