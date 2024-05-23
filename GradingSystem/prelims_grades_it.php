<?php
// update_grades_it.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    include 'db_connection.php';

    // Get student ID from POST request
    $studentID = $_POST['PrelimsstudentIDIT'];
    $studentName = $_POST['PrelimsstudentNameIT'];
    $Programming = $_POST['Programming'];
    $Database = $_POST['Database'];
    $Web = $_POST['Web'];
    $Networking = $_POST['Networking'];
    $Operating = $_POST['Operating'];
    $Information = $_POST['Information'];

    // Start transaction
    $conn->begin_transaction();

    try {
        // Update grade_table for the specific student
        $sql = "UPDATE itprelims_table SET Programming_Fundamentals = ?, Database_Management = ?, Web_Development = ?, Networking = ?, Operating_Systems = ?, Information_Security = ? WHERE studentID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ddddddi", $Programming, $Database, $Web, $Networking, $Operating, $Information, $studentID);

        if ($stmt->execute()) {
            // Commit transaction
            $conn->commit();
            echo "Grades updated successfully for student $studentName";
        } else {
            // Rollback transaction
            $conn->rollback();
            echo "Error updating grades: " . $conn->error;
        }

        $stmt->close();
    } catch (Exception $e) {
        // Rollback transaction in case of an exception
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }

    $conn->close();
}
?>
