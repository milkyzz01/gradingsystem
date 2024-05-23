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
            $gradeTableMidterms = '';
            $gradeTableFinals = '';
            switch ($studentProgram) {
                case 'Criminology':
                    $gradeTable = 'crimprelims_table';
                    $gradeTableMidterms = 'crimmidterm_table';
                    $gradeTableFinals = 'crimfinal_table';
                    break;
                case 'Information Technology':
                    $gradeTable = 'itprelims_table';
                    $gradeTableMidterms = 'itmidterm_table';
                    $gradeTableFinals = 'itfinal_table';
                    break;
                case 'Computer Science':
                    $gradeTable = 'comsciprelims_table';
                    $gradeTableMidterms = 'comscimidterm_table';
                    $gradeTableFinals = 'comscifinal_table';
                    break;
                case 'Civil Engineer':
                    $gradeTable = 'civilprelims_table';
                    $gradeTableMidterms = 'civilmidterm_table';
                    $gradeTableFinals = 'civilfinal_table';
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
                $sqlDeleteGradesMidterms = "DELETE FROM $gradeTableMidterms WHERE studentID = ?";
                $sqlDeleteGradesFinals = "DELETE FROM $gradeTableFinals WHERE studentID = ?";
                $stmtDeleteGrades = $conn->prepare($sqlDeleteGrades);
                $stmtDeleteGradesMidterms = $conn->prepare($sqlDeleteGradesMidterms);
                $stmtDeleteGradesFinals = $conn->prepare($sqlDeleteGradesFinals);
                $stmtDeleteGrades->bind_param("i", $studentID);
                $stmtDeleteGradesMidterms->bind_param("i", $studentID);
                $stmtDeleteGradesFinals->bind_param("i", $studentID);

                // Execute the statement
                if ($stmtDeleteGrades->execute() && $stmtDeleteGradesMidterms->execute() && $stmtDeleteGradesFinals->execute()) {
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
