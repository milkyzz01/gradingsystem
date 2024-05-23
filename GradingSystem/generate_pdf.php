<?php
// Retrieve the student program from the URL parameter
$studentProgram = $_GET['program'];

// Include the db_connection.php file for database connection
require_once 'db_connection.php';

// Include the jsPDF library
require_once 'jspdf.php';

// Set up the PDF document
$pdf = new jsPDF();
$pdf->setFontSize(12);

// Define table headers
$headers = array();
if ($studentProgram == 'Criminology') {
    $headers = ['Crime Scene Investigation', 'Forensic Psychology', 'Legal Studies', 'Criminal Law', 'Victimology', 'Criminal Justice'];
} elseif ($studentProgram == 'Information Technology') {
    $headers = ['Programming Fundamentals', 'Database Management', 'Web Development', 'Networking', 'Operating Systems', 'Information Security'];
} elseif ($studentProgram == 'Computer Science') {
    $headers = ['Algorithms', 'Data Structures', 'Software Engineering', 'Computer Architecture', 'Artificial Intelligence', 'Computer Graphics'];
} elseif ($studentProgram == 'Civil Engineer') {
    $headers = ['Statics', 'Structural Analysis', 'Fluid Mechanics', 'Geotechnical Engineering', 'Transportation Engineering', 'Construction Management'];
}

// Query to fetch grades from the database
$sqlGrades = "SELECT " . implode(',', $subjects) . " FROM $gradeTable WHERE studentID='$studentID'";
$resultGrades = $conn->query($sqlGrades);

// Check if there are any grades
if ($resultGrades->num_rows > 0) {
    // Initialize an array to hold table data
    $data = array();

    // Fetch each row of grades
    $row = $resultGrades->fetch_assoc();
    foreach ($subjects as $subject) {
        // Check if the grade is NULL (indicating "INC")
        if ($row[$subject] === '0.00') {
            $data[] = ['INC'];
        } else {
            $data[] = [$row[$subject]];
        }
    }

    // Add the table to the PDF
    $pdf->autoTable($headers, $data);

    // Output the PDF
    $pdf->output('grades.pdf');
} else {
    echo "No grades found.";
}

// Close the database connection
$conn->close();
?>
