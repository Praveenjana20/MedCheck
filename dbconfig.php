<?php
try{
//$host = "mysql8.000webhost.com";
//$user = "a2032355_sa";
//$password = "welcome1";
//$datbase = "a2032355_db";

$host = "localhost";
$user = "root";
$password = "welcome@1";
$datbase = "test_jpr";

//mysqli_connect($host,$user,$password);
$conn = mysqli_connect($host, $user, $password, $datbase); 
//mysql_select_db($datbase);
}
catch(Exception $E){
    
}
?>