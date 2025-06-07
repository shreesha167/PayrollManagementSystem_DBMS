<?php
// Database connection details
$host = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "project1";  

try {
    // Connect to the database
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Retrieve form data
        $emp_id = $_POST['emp_id'];
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $joining_date = $_POST['joining_date'];
        $city = $_POST['city'];
        $states = $_POST['states'];
        $salary = $_POST['salary'];
        $dept_id = $_POST['dept_id'];
        
        // Prepare SQL statement
        $sql = "INSERT INTO employee (emp_id, fname, lname, joining_date, city, states, salary,dept_id) 
                VALUES (:emp_id, :fname, :lname, :joining_date, :city, :states, :salary,:dept_id)";
        $stmt = $conn->prepare($sql);
        
        // Bind parameters
        $stmt->bindParam(':emp_id', $emp_id);
        $stmt->bindParam(':fname', $fname);
        $stmt->bindParam(':lname', $lname);
        $stmt->bindParam(':joining_date', $joining_date);
        $stmt->bindParam(':city', $city);
        $stmt->bindParam(':states', $states);
        $stmt->bindParam(':salary', $salary);
        $stmt->bindParam('dept_id', $dept_id);
        
        // Execute the statement
        if ($stmt->execute()) {
            echo '<script>alert("Employee Added Successfully");';
            echo 'window.location.href = "emp.php";';
echo '</script>'; 
        } else {
            echo "<p>Error adding employee: " . $stmt->errorInfo()[2] . "</p>";
        }
    }
    
    // Retrieve updated employee list
    $sql = "SELECT emp_id, fname, lname, joining_date, city, states, salary,dept_id FROM employee";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll Management System - Manager</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
        }

        section {
            margin: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #333;
            color: #fff;
        }

        h2 {
            color: #333;
        }
    </style>
</head>
<body>
    <header>
        <h1>Payroll Management System</h1>
        <p>Manager Dashboard</p>
    </header>

    <section>
        <h2>Employee Payroll Overview</h2>
        <table>
            <thead>
                <tr>
                    <th>Employee ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Joining Date</th>
                    <th>City</th>
                    <th>States</th>
                    <th>Salary</th>
                    <th>Department ID</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($employees as $employee): ?>
                    <tr>
                        <td><?= $employee['emp_id'] ?></td>
                        <td><?= $employee['fname'] ?></td>
                        <td><?= $employee['lname'] ?></td>
                        <td><?= $employee['joining_date'] ?></td>
                        <td><?= $employee['city'] ?></td>
                        <td><?= $employee['states'] ?></td>
                        <td><?= $employee['salary'] ?></td>
                        <td><?= $employee['dept_id'] ?></td>
                        <td><a href='delete_employee.php?id=<?= $employee['emp_id'] ?>'>Delete</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2>Add New Employee</h2>
        <form action="add_emp1.php" method="POST">
            <label for="emp_id">Employee ID:</label>
            <input type="number" id="emp_id" name="emp_id" required><br>
            <label for="fname">First Name:</label>
            <input type="text" id="fname" name="fname" required><br>
            <label for="lname">Last Name:</label>
            <input type="text" id="lname" name="lname" required><br>
            <label for="joining_date">Joining Date:</label>
            <input type="date" id="joining_date" name="joining_date" required><br>
            <label for="city">City:</label>
            <input type="text" id="city" name="city" required><br>
            <label for="states">States:</label>
            <input type="text" id="states" name="states" required><br>
            <label for="salary">Salary:</label>
            <input type="number" id="salary" name="salary" required><br>
            <label for="dept_id">Dept id:</label>
            <input type="number" id="dept_id" name="dept_id" required><br>
            <input type="submit" value="Add Employee">
        </form>
    </section>
</body>
</html>
