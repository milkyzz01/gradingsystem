<?php
// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the announcement from the POST data
    $announcement = $_POST['announce'];

    // Validate the announcement (you can add more validation if needed)
    if (empty($announcement)) {
        echo "Announcement cannot be empty";
        exit;
    }

    // Database connection
    include 'db_connection.php'; // Include your database connection script

    // Prepare the SQL statement to insert the announcement
    $sql = "INSERT INTO announcement_table (Announcement) VALUES (?)";

    // Prepare and bind the statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $announcement);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Announcement Posted Successfully";
    } else {
        echo "Error: " . $conn->error;
    }

    // Close the statement and database connection
    $stmt->close();
    $conn->close();
} else {
    // If the request method is not POST, return an error message
    echo "Invalid request method";
}
?>
