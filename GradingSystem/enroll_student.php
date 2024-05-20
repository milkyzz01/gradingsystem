<?php
// enroll_student.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    include 'db_connection.php';

    // Get student ID from POST request
    $studentID = $_POST['studentID'];
    
    // Start transaction
    $conn->begin_transaction();

    try {
        // Fetch the studentProgram for the given studentID
        $sql = "SELECT studentProgram FROM student_table WHERE studentID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $studentID);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $studentProgram = $row['studentProgram'];

            // Update student status to 'Enrolled'
            $sql = "UPDATE student_table SET studentStatus = 'Enrolled' WHERE studentID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $studentID);

            if ($stmt->execute()) {
                // Determine the grade table and subjects based on the student's program
                $gradeTable = '';
                $subjects = [];

                switch ($studentProgram) {
                    case 'Criminology':
                        $gradeTable = 'criminologygrades_tbl';
                        $subjects = ['Crime_Scene_Investigation', 'Forensic_Psychology', 'Legal_Studies', 'Criminal_Law	', 'Victimology', 'Criminal_Justice'];
                        break;
                    case 'Information Technology':
                        $gradeTable = 'it_grades_tbl';
                        $subjects = ['Programming_Fundamentals', 'Database_Management', 'Web_Development', 'Networking', 'Operating_Systems', 'Information_Security'];
                        break;
                    case 'Computer Science':
                        $gradeTable = 'comsci_grades_tbl';
                        $subjects = ['Algorithms', 'Data_Structures	', 'Software_Engineering', 'Computer_Architecture', 'Artificial_Intelligence', 'Computer_Graphics'];
                        break;
                    case 'Civil Engineer':
                        $gradeTable = 'civil_grades_tbl';
                        $subjects = ['Statics', 'Structural_Analysis', 'Fluid_Mechanics', 'Geotechnical_Engineering', 'Transportation_Engineering', 'Construction_Management'];
                        break;
                    default:
                        // Handle unknown programs, if any
                        echo "Unknown program: " . $studentProgram;
                        $conn->rollback();
                        exit();
                }

                // Prepare the SQL statement for inserting grades
                $subjectColumns = implode(', ', $subjects);
                $placeholders = implode(', ', array_fill(0, count($subjects), '0.00'));
                $sql = "INSERT INTO $gradeTable (studentID, $subjectColumns) VALUES (?, $placeholders)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $studentID);

                if ($stmt->execute()) {
                    // Commit transaction
                    $conn->commit();
                    echo "Student enrolled and grade entry created successfully";
                } else {
                    // Rollback transaction
                    $conn->rollback();
                    echo "Error creating grade entry: " . $conn->error;
                }
            } else {
                // Rollback transaction
                $conn->rollback();
                echo "Error enrolling student: " . $conn->error;
            }
        } else {
            // Rollback transaction if student not found
            $conn->rollback();
            echo "Error: Student not found";
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
