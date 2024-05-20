<?php
// register.php

// Database connection
include 'db_connection.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['name'];
    $address = $_POST['address'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $program = $_POST['program'];


    // Set default value for studentStatus
    $studentStatus = "Not-Enrolled";

    // Prepare and execute SQL statement to insert data into student_table
    $stmt = $conn->prepare("INSERT INTO student_table (studentName, studentAddress, studentUsernamne, studentPassword, studentStatus, studentProgram) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $address, $username, $password, $studentStatus, $program);

    if ($stmt->execute()) {
        // Registration successful
        echo "Registration successful!";
    } else {
        // Registration failed
        echo "Error: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <link rel="stylesheet" href="registration.css">
    
</head>
<body>
    <div class="container">
    <h1>Westmead International School</h1>
    <div class="form-container">
    <p>Register</p>
    <form action="">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br><br>
        
        <label for="address">Address:</label>
        <input type="text" id="address" name="address" required><br><br>
        
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <!-- New select dropdown for student program -->
        <label for="program">Program:</label>
        <select id="program" name="program" required>
            <option value="">Select Program</option>
            <option value="Information Technology">Information Technology</option>
            <option value="Computer Science">Computer Science</option>
            <option value="Civil Engineer">Civil Engineer</option>
            <option value="Criminology">Criminology</option>
        </select><br><br>

        <div class="buttons">
        <input type="button" value="Register" class="submit-btn" onclick="registerUser()">
        <input type="button" value="Back" onclick="window.history.back()" class="back-btn">
        </div>
    </form>
    </div>
    </div>

    <!-- Include SweetAlert library -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Function to handle form submission
    function registerUser() {
    // Retrieve form data
    var name = document.getElementById('name').value.trim();
    var address = document.getElementById('address').value.trim();
    var username = document.getElementById('username').value.trim();
    var password = document.getElementById('password').value.trim();
    var program = document.getElementById('program').value.trim(); // New field

    // Check if any of the fields is empty
    if (name === '' || address === '' || username === '' || password === '' || program === '') { // New condition for program
        // Display error alert if any field is empty
        swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Please fill in all fields.',
            confirmButtonText: 'OK'
        });
        return; // Exit the function if any field is empty
    }

    // Create XHR object
    var xhr = new XMLHttpRequest();

    // Configure POST request
    xhr.open("POST", "Registration.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    // Define callback function for when the request completes
    xhr.onload = function() {
        if (xhr.status == 200) {
            // Registration successful, trigger SweetAlert
            swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Registration Successful',
                confirmButtonText: 'OK'
            }).then(() => {
                // Redirect to another page if needed
                window.location.href = 'index.php'; // Replace 'login.php' with your desired page
            });
        } else {
            // Registration failed, handle error
            alert('Error: ' + xhr.responseText);
        }
    };

    // Prepare and send request payload
    var params = 'name=' + encodeURIComponent(name) + '&address=' + encodeURIComponent(address) + '&username=' + encodeURIComponent(username) + '&password=' + encodeURIComponent(password) + '&program=' + encodeURIComponent(program); // Include program in params
    xhr.send(params);
}
</script>

</body>
</html>

