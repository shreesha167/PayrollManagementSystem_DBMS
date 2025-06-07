<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Attendance Management System</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th, td {
      padding: 10px;
      border-bottom: 1px solid #ddd;
      text-align: left;
    }

    th {
      background-color: #f2f2f2;
    }

    .form-group {
      display: flex;
      margin-bottom: 10px;
    }

    .form-group label {
      width: 120px;
      margin-right: 10px;
    }

    input[type="text"], input[type="number"] {
      padding: 5px;
      border: 1px solid #ccc;
      border-radius: 3px;
    }
  </style>
</head>
<body>

  <h1>Attendance Management System</h1>

  <table>
    <thead>
      <tr>
        <th>Employee ID</th>
        <th>Attendance ID</th>
        <th>Days worked</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
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

        // SQL query to fetch attendance data
        $sql = "SELECT e.emp_id, a.atten_id, a.days_worked
                FROM attendance a
                INNER JOIN employee e ON a.emp_id = e.emp_id";

        try {
          $stmt = $conn->prepare($sql);
          $stmt->execute();
          $attendance = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
          echo "Error fetching attendance data: " . $e->getMessage();
          exit();
        }

        // Display attendance data
        foreach ($attendance as $row) {
          echo "<tr>";
          echo "<td>" . $row['emp_id'] . "</td>";
          echo "<td>" . $row['atten_id'] . "</td>";
          echo "<td>" . $row['days_worked'] . "</td>";
          echo "<td>
                  <a href='update_attendance.php?id=" . $row['atten_id'] . "'>Update</a>
                </td>";
          echo "</tr>";
        }

        $conn = null; // Close connection
      ?>
    </tbody>
  </table>

  <h2>Update Attendance</h2>

  <form class="form-group" action="update_attendance.php" method="post">
    <div class="form-group">
      <label for="atten_id">Attendance ID:</label>
      <input type="number" id="atten_id" name="atten_id" required >
    </div>
    <div class="form-group">
      <label for="days_worked">days Worked:</label>
      <input type="number" step="0.01" id="days_worked" name="days_worked" required>
    </div>
    <input type="submit" value="Update Attendance">
  </form>

</body>
</html>
