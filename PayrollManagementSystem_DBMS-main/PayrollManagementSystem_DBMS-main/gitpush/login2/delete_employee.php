<?php
// Check if the ID is provided
if(isset($_GET['id'])) {
    $emp_id = $_GET['id'];
    
    // Database connection details
    $host = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $dbname = "project1";  

    // Connect to the database
    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Error connecting to database: " . $e->getMessage();
        exit();
    }

    // SQL query to delete employee
    $sql = "DELETE FROM employee WHERE emp_id = :emp_id";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':emp_id', $emp_id);
        $stmt->execute();
         echo '<script>';
          echo '<script>alert("Employee Deleteded Successfully")'; 
echo 'window.location.href = "emp.php";';
echo '</script>';

        exit();
    } catch (PDOException $e) {
        echo "Error deleting employee: " . $e->getMessage();
    }
} else {
    echo "Employee ID not provided.";
}
?>

