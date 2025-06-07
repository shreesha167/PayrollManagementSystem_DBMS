<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll Management System - Department</title>
    <style>
        body {
            background-image: url("https://www.wisesecurity.net/wp-content/uploads/2019/11/160-1600491_autodesk-wallpaper-website-background.jpg");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
        }

        header {
            color: #fff;
            padding: 10px;
            text-align: center;
        }

        section {
            margin: 20px;
            color: #fff;
        }

        table {
            width: 50%;
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
        color: #fff;
        }

        h2 {
            color: #fff;
        }
        .form-group {
  display: auto; /* Arrange elements horizontally */
  margin-bottom: 12px; /* Add space between groups */
  flex-direction: column; /* Stack buttons vertically */
  color: white;
}

.form-group label {
  width: 120px; /* Set label width */
  justify-content: space-between;
  margin-right: 10px; /* Add space between label and input */
  
}

input[type="text"], input[type="number"], input[type="date"] {
  padding: 5px; /* Add padding to input fields */
  border: 1px solid #ccc; /* Add a thin border */
  border-radius: 3px; /* Add rounded corners */
  opacity: 0.7; /* Set transparency */
}
.submit{
    padding: 20px;
    box padding: 20px ;
}
.submit :hover{
    background-color: greenyellow;
}
    </style>
</head>
<body>
<header>
    <h1>Payroll Management System</h1>
    <p>Department Dashboard</p>
</header>

<section>
    <h2>Department Overview</h2>
    <table>
        <thead>
        <tr>
            <th>Department ID</th>
            <th>Department Name</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php
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

        // SQL query to fetch department data
        $sql = "SELECT * FROM department";

        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error fetching department data: " . $e->getMessage();
            exit();
        }

        // Display department data
        foreach ($departments as $department) {
            echo "<tr>";
            echo "<td>" . $department['dept_id'] . "</td>";
            echo "<td>" . $department['dept_name'] . "</td>";
            echo "<td><a href='delete_department.php?id=" . $department['dept_id'] . "'>Delete</a></td>";
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>

    <h2>Add New Department</h2>
            
    <form  class="form-group"  action="add_dept.php" method="post">
        <div class="form-group">
        <label for="dept_id">Department ID:</label>
       
        <input type="number" id="dept_id" name="dept_id" required placeholder="Enter Department ID">
        </div>
        <div class="form-group">
        <label for="dept_name">Department Name:</label>
        <input type="text" id="dept_name" name="dept_name" required placeholder="Enter Department Name">
        </div>
        <div class="submit">
        <input type="submit" value="Add Department">
        </div>
    </form>
</section>
</body>
</html>
