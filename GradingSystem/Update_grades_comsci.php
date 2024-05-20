<?php
// update_grades_comsci.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    include 'db_connection.php';

    // Get student ID from POST request
    $studentID = $_POST['studentIDcomSci'];
    $studentName = $_POST['studentNameComSci'];
    $Algorithms = $_POST['Algorithms'];
    $Structures = $_POST['Structures'];
    $Software = $_POST['Software'];
    $Architecture = $_POST['Architecture'];
    $Intelligence = $_POST['Intelligence'];
    $Graphics = $_POST['Graphics'];

    // Start transaction
    $conn->begin_transaction();

    try {
        // Update grade_table for the specific student
        $sql = "UPDATE comsci_grades_tbl SET Algorithms = ?, Data_Structures = ?, Software_Engineering = ?, Computer_Architecture = ?, 	Artificial_Intelligence = ?, Computer_Graphics = ? WHERE studentID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ddddddi", $Algorithms, $Structures, $Software, $Architecture,  $Intelligence, $Graphics, $studentID);

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
