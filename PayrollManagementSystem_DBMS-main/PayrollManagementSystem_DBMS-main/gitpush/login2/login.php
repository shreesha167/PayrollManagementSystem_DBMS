<?php 

$host="localhost";
$user="root";
$dbpassword="";
$dbname="project1";

// mysql_connect($host,$user,$password);
// mysql_select_db($dbname);
$conn = new mysqli ($host, $user, $dbpassword, $dbname);
try {
    $conn = new mysqli($host, $dbusername, $dbpassword, $dbname);
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    // ... rest of your code ...
} catch (Exception $e) {
    echo "Error connecting to database: " . $e->getMessage();
    // Do something else to handle the error (e.g., log it, redirect to an error page)
}

if(isset($_POST['uname1'])){
    $uname1=$_POST['uname1'];
    $upswd1=$_POST['upswd1'];
    
    $sql="select * from register where uname1='".$uname1."'AND upswd1='".$upswd1."' limit 1";
    
    $result=mysqli_query($conn,$sql);
    
 if(mysqli_num_rows($result) == 1) {
    echo '<script>alert("Login successful!");';
    echo 'window.location.href = "dashboard.html";</script>';
    exit();
}

    }
    else{
        echo '<script>alert(" You Have Entered Incorrect Password)"</scipt>';
        exit();
    }
        
