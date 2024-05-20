<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "gradingsystem";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query the database to check if the username and password combination exists
    $sql = "SELECT * FROM student_table WHERE studentUsernamne='$username' AND studentPassword='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Authentication successful, set up session and redirect
        $_SESSION['studentUsername'] = $username;
        header("Content-Type: application/json"); // Set response content type to JSON
        echo json_encode(array("success" => true)); // Send JSON response indicating success
        exit();
    } else {
        header("Content-Type: application/json"); // Set response content type to JSON
        echo json_encode(array("success" => false, "message" => "Invalid username or password")); // Send JSON response indicating failure
        exit();
    }
    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="admin-login.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <header>
        <img class="wis-logo-student" src="assets/wislogo.jpg">
    </header>
    <content>
    <div class="login-container">
    <form id="studentloginForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <h2>Student Login</h2>
        <input type="text" placeholder="Username" id="username" name="username" required><br>
        <input type="password" placeholder="Password" id="password" name="password" required><br>
        <button type="submit" class="submit">Login</button>
    </form>
    </div>
</content>
<button class="back" onclick="window.history.back()">Back</button><br><br>


<script>
        // Intercept form submission to prevent default redirect
        document.getElementById('studentloginForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            // Perform form submission via XHR
            var form = this;
            var formData = new FormData(form);

            // Create XHR object
            var xhr = new XMLHttpRequest();

            // Configure POST request
            xhr.open('POST', form.action, true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

            // Define callback function for when the request completes
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Parse the response from the server
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        Swal.fire({
                    title: 'Success!',
                    text: 'Logged In',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    // Reload the page after the SweetAlert is closed
                    if (result.isConfirmed) {
                        window.location.href = 'studentpage.php';
                    }
                });
                    } else {
                        // Display error message with SweetAlert
                        Swal.fire({
                    title: 'Error!',
                    text: 'Error Invalid Username or Password',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                    }
                } else {
                    // Display error message with SweetAlert
                    Swal.fire({
                    title: 'Error!',
                    text: 'Error While Proccessing Ypur Request',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                }
            };
           
            // Send form data
            xhr.send(formData);
        });
    </script>
</body>
</html>