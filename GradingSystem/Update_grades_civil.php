<?php
// update_grades_civil.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    include 'db_connection.php';

    // Get student ID from POST request
    $studentID = $_POST['studentIDcivil'];
    $studentName = $_POST['studentNameCivil'];
    $Statics = $_POST['Statics'];
    $Structural = $_POST['Structural'];
    $Fluid = $_POST['Fluid'];
    $Geotechnical = $_POST['Geotechnical'];
    $Transportation = $_POST['Transportation'];
    $Construction = $_POST['Construction'];

    // Start transaction
    $conn->begin_transaction();

    try {
        // Update grade_table for the specific student
        $sql = "UPDATE civil_grades_tbl SET Statics = ?, Structural_Analysis = ?, Fluid_Mechanics = ?, Geotechnical_Engineering = ?, Transportation_Engineering = ?, Construction_Management = ? WHERE studentID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ddddddi", $Statics, $Structural, $Fluid, $Geotechnical, $Transportation, $Construction, $studentID);

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
