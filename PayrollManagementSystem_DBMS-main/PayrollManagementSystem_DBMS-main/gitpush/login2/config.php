<?php 
   $host = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "project1";  
    $conn = new mysqli($host, $dbusername, $dbpassword, $dbname);
    if($conn->connect_error){
        die("Connection failed".$conn->connect_error);
    }
    echo " ";
    
    ?>