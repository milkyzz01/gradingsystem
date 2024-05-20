<?php
// Include your database connection script
include 'db_connection.php';

// Check if the request method is POST and if announcementID is set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['announcementID'])) {
    // Sanitize the input to prevent SQL injection
    $announcementID = mysqli_real_escape_string($conn, $_POST['announcementID']);
    
    // Prepare and execute the DELETE query
    $sql = "DELETE FROM announcement_table WHERE announcementID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $announcementID);

    if ($stmt->execute()) {
        // Deletion successful
        echo "Announcement deleted successfully";
    } else {
        // Error occurred
        echo "Error deleting announcement: " . $conn->error;
    }

    // Close the prepared statement
    $stmt->close();
} else {
    // Handle if announcementID is not set or if the request method is not POST
    echo "Invalid request";
}

// Close the database connection
$conn->close();
?>
