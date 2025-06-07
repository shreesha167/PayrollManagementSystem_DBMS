<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Attendance Management System</title>

    <script>
  const form = document.querySelector('.form-group');
  const messageDiv = document.getElementById('update_message');

  form.addEventListener('submit', (event) => {
    event.preventDefault(); // Prevent default form submission

    fetch('update_attendance.php', {
      method: 'POST',
      body: new FormData(form)
    })
    .then(response => response.text())
    .then(data => {
      messageDiv.textContent = data;
    })
    .catch(error => {
      console.error(error);
      messageDiv.textContent = "An error occurred. Please try again.";
    });
  });
</script>

    </script>
  <style>
    body {
      background-image: url("https://www.wisesecurity.net/wp-content/uploads/2019/11/160-1600491_autodesk-wallpaper-website-background.jpg");
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      font-family: Arial, sans-serif;
      margin: 20px;
      color: #fff;
      background-color: #f4f4f4;

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
    h1, h2 {
      color: #fff;
    }

    .submit {
      padding: 8px 40px;
      margin-left: 100px;
      background-color: #007bff;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .submit:hover {
      background-color: #0069d9;
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
        <th>Days Worked</th>
        
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

        foreach ($attendance as $row) {
          echo "<tr>";
          echo "<td>" . $row['emp_id'] . "</td>";
          echo "<td>" . $row['atten_id'] . "</td>";
          echo "<td>" . $row['days_worked'] . "</td>";
        }

        $conn = null; // Close connection
      ?>
    </tbody>
  </table>

  <h2>Update Attendance</h2>

  <form class="form-group" action="update_attendance.php" method="post">
    <div class="form-group">
      <label for="atten_id">Attendance ID:</label>
      <input type="number" id="atten_id" name="atten_id" required>
    </div>
    <div class="form-group">
      <label for="days_worked">Days Worked:</label>
      <input type="number" step="0.01" id="days_worked" name="days_worked" required>
    </div>
    <form class="form-group" action="update_attendance.php" method="post">
  <div id="update_message"></div>
  <input type="submit" value="Update Attendance" class="submit">
</form>
  </form>

</body>
</html>
