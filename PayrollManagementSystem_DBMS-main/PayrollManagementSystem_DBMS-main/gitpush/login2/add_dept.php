<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
  // Validate input
  $dept_name = trim($_POST["dept_name"]);  // Trim whitespace

  // Database connection details (replace with your credentials)
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

  // Check if department name already exists
  $sql_check = "SELECT COUNT(*) FROM department WHERE dept_name = :dept_name";
  $stmt_check = $conn->prepare($sql_check);
  $stmt_check->bindParam(":dept_name", $dept_name, PDO::PARAM_STR);
  $stmt_check->execute();
  $count = $stmt_check->fetchColumn();

  if ($count > 0) {
  // ... error handling for duplicate dept_name
} else {
  try {
    // Prepare the INSERT statement with both dept_id and dept_name
    $sql = "INSERT INTO department (dept_id, dept_name) VALUES (:dept_id, :dept_name)";
    $stmt = $conn->prepare($sql);  // Create the prepared statement here

    // Bind parameters after creating the statement
    $stmt->bindParam(":dept_id", $_POST["dept_id"], PDO::PARAM_INT);
    $stmt->bindParam(":dept_name", $dept_name, PDO::PARAM_STR);

    $stmt->execute();
      echo '<script>alert("Department Added Successfully!"); window.location.href="dept.php";</script>'; // Redirect after success
    
  } catch (PDOException $e) {
    // ... error handling for insertion failure
    echo "Error adding department: " . $e->getMessage();
  }
  $conn = null;
}
}
?>



