<?php
// Check if department ID is provided
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // Get department ID from the URL parameter
    $dept_id = $_GET['id'];

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

    // SQL query to delete department
    $sql = "DELETE FROM department WHERE dept_id = :dept_id";
    $stmt = $conn->prepare($sql);

    // Bind parameters and execute the statement
    $stmt->bindParam(":dept_id", $dept_id);

    try {
        $stmt->execute();
        echo '<script>alert("Department Deleted Successfully"); 
        window.location.replace("dept.php");</script>';
        exit();

    } catch (PDOException $e) {
        echo "Error deleting department: " . $e->getMessage();
    }

    // Close the connection
    $conn = null;
} else {
    // Redirect to the homepage or display an error message
    echo "Error: Department ID not provided.";
}
?>
