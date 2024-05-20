<?php
// update_grades.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    include 'db_connection.php';

    // Get student ID from POST request
    $studentID = $_POST['studentID'];
    $studentName = $_POST['studentName'];
    $CrimeScene = $_POST['CrimeScene'];
    $Forensic = $_POST['Forensic'];
    $Legal = $_POST['Legal'];
    $Criminal = $_POST['Criminal'];
    $Victomology = $_POST['Victimology'];
    $Justice = $_POST['Justice'];

    // Start transaction
    $conn->begin_transaction();

    try {
        // Update grade_table for the specific student
        $sql = "UPDATE criminologygrades_tbl SET Crime_Scene_Investigation = ?, Forensic_Psychology = ?, Legal_Studies = ?, Criminal_Law = ?, Victimology = ?, Criminal_Justice = ? WHERE studentID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ddddddi", $CrimeScene, $Forensic, $Legal, $Criminal, $Victomology, $Justice, $studentID);

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
