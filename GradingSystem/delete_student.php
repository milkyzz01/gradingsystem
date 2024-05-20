<?php
// delete_student.php

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include your database connection script
    include 'db_connection.php';

    // Get the studentID from the POST data
    $studentID = $_POST['studentID'];

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Query to fetch student's program based on the studentID
        $sqlGetProgram = "SELECT studentProgram FROM student_table WHERE studentID = ?";
        $stmtGetProgram = $conn->prepare($sqlGetProgram);
        $stmtGetProgram->bind_param("i", $studentID);

        // Execute the statement
        if ($stmtGetProgram->execute()) {
            // Get the student's program
            $stmtGetProgram->bind_result($studentProgram);
            $stmtGetProgram->fetch();
            $stmtGetProgram->close();

            // Determine the grade table based on the student's program
            $gradeTable = '';
            switch ($studentProgram) {
                case 'Criminology':
                    $gradeTable = 'criminologygrades_tbl';
                    break;
                case 'Information Technology':
                    $gradeTable = 'it_grades_tbl';
                    break;
                case 'Computer Science':
                    $gradeTable = 'comsci_grades_tbl';
                    break;
                case 'Civil Engineer':
                    $gradeTable = 'civil_grades_tbl';
                    break;
                default:
                    // Handle unknown programs, if any
                    echo "Unknown program: " . $studentProgram;
                    $conn->rollback();
                    exit();
            }

            // Delete the student from student_table
            $sqlDeleteStudent = "DELETE FROM student_table WHERE studentID = ?";
            $stmtDeleteStudent = $conn->prepare($sqlDeleteStudent);
            $stmtDeleteStudent->bind_param("i", $studentID);

            // Execute the statement
            if ($stmtDeleteStudent->execute()) {
                // Delete the student's grades from the corresponding grade table
                $sqlDeleteGrades = "DELETE FROM $gradeTable WHERE studentID = ?";
                $stmtDeleteGrades = $conn->prepare($sqlDeleteGrades);
                $stmtDeleteGrades->bind_param("i", $studentID);

                // Execute the statement
                if ($stmtDeleteGrades->execute()) {
                    // Commit the transaction
                    $conn->commit();
                    echo "Student and associated grades deleted successfully.";
                } else {
                    // Rollback the transaction if there's an error
                    $conn->rollback();
                    echo "Error deleting student's grades: " . $conn->error;
                }
            } else {
                // Rollback the transaction if there's an error
                $conn->rollback();
                echo "Error deleting student: " . $conn->error;
            }

            // Close the prepared statements
            $stmtDeleteStudent->close();
            $stmtDeleteGrades->close();
        } else {
            // Rollback the transaction if there's an error fetching the student's program
            $conn->rollback();
            echo "Error fetching student's program: " . $conn->error;
        }
    } catch (Exception $e) {
        // Rollback the transaction if there's an exception
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }

    // Close the database connection
    $conn->close();
}
?>
