<?php
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

// Get form data
$atten_id = $_POST['atten_id'];
$days_worked = $_POST['days_worked'];

// Prepare SQL statement to update attendance
$sql = "UPDATE attendance SET days_worked = :days_worked WHERE atten_id = :atten_id";

try {
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':days_worked', $days_worked);
  $stmt->bindParam(':atten_id', $atten_id);
  $stmt->execute();

  // Call stored procedure to update salary
  $updateSalarySql = "CALL UpdateSalary(:days_worked)";
  $updateSalaryStmt = $conn->prepare($updateSalarySql);
  $updateSalaryStmt->bindParam(':days_worked', $days_worked, PDO::PARAM_INT);
  $updateSalaryStmt->execute();
  
  echo '<script>alert("Attendance updated successfully"); window.location.href="attendance.php";</script>';
} catch (PDOException $e) {
  echo "Error updating attendance: " . $e->getMessage();
}

$conn = null; // Close connection
?>
