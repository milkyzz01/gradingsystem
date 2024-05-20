<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['studentUsername'])) {
    // If not logged in, redirect to the login page
    header("Location: index.php");
    exit();
}
// Retrieve the logged-in student's username from the session
$loggedInUsername = $_SESSION['studentUsername'];

//db connection
include 'db_connection.php';

// Query to fetch student's details based on the username
$sql = "SELECT studentID, studentName, studentAddress, studentStatus, studentProgram FROM student_table WHERE studentUsernamne='$loggedInUsername'";
$result = $conn->query($sql);

//rendered the student details
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $studentID = $row['studentID'];
    $studentName = $row['studentName'];
    $studentAddress = $row['studentAddress'];
    $studentStatus = $row['studentStatus'];
    $studentProgram = $row['studentProgram'];
} else {
    $studentID = "Unknown";
    $studentName = "Unknown";
    $studentAddress = "Unknown";
    $studentStatus = "Unknown";
    $studentProgram = "Unknown";
}

// Determine the grade table and subjects based on the student's program
$gradeTable = '';
$subjects = [];

switch ($studentProgram) {
    case 'Criminology':
        $gradeTable = 'criminologygrades_tbl';
        $subjects = ['Crime_Scene_Investigation', 'Forensic_Psychology', 'Legal_Studies', 'Criminal_Law', 'Victimology', 'Criminal_Justice'];
        break;
    case 'Information Technology':
        $gradeTable = 'it_grades_tbl';
        $subjects = ['Programming_Fundamentals', 'Database_Management', 'Web_Development', 'Networking', 'Operating_Systems', 'Information_Security'];
        break;
    case 'Computer Science':
        $gradeTable = 'comsci_grades_tbl';
        $subjects = ['Algorithms', 'Data_Structures', 'Software_Engineering', 'Computer_Architecture', 'Artificial_Intelligence', 'Computer_Graphics'];
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
$sqlGrades = "SELECT $subjectColumns FROM $gradeTable WHERE studentID='$studentID'";


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="studentpage.css">
    <script>
        function toggleGrades() {
            var gradesTable = document.getElementById('grades-table');
            var showButton = document.getElementById('show-grades-btn');
            if (gradesTable.style.display === 'none' || gradesTable.style.display === '') {
                gradesTable.style.display = 'block';
                showButton.textContent = 'Hide Grades';
            } else {
                gradesTable.style.display = 'none';
                showButton.textContent = 'Show Grades';
            }
        }
    </script>
</head>
<body>
    <header>
        <ul>
            <li><img src="assets/wislogo.jpg" class="logo"></li>
            <li>Westmead International School</li>
        </ul>
        <div class="student-logout">
            <p>Student</p>
            <p><a href="logout.php">Logout</a></p>
        </div>
    </header>
    <content>
        <div class="profile">
          <div class="studentprofile">
          <!-- Insert the profile pic for student -->
          <img class="studentImage" src="assets/4.png">
          <!-- Insert the name for student -->
          <p class="studentNamee"><?php echo $studentName; ?></p>
          </div>
          <div class="studentInfo">
            <p>School: Westmead International School</p>
            <p>Address: <?php echo $studentAddress; ?></p>
            <p>Program: <?php echo $studentProgram; ?></p>
            <p>Type: Student</p>
            <p class="studentStatus">Status: <?php echo $studentStatus; ?></p>
          </div>
        </div>

        <!-- Student Grades -->
        <div class="grades">
            <div class="button-grades">
                <h2>List of Subjects and Grades</h2>
                <button class="show-grades-btn" id="show-grades-btn" onclick="toggleGrades()">Show Grades</button>
            </div>
            <div class="table-grades" id="grades-table">
            <table id="unenrolled-table">
                <thead>
                    <tr>
                        <?php if ($studentProgram == 'Criminology'): ?>
                            <th>Crime Scene Investigation</th>
                            <th>Forensic Psychology</th>
                            <th>Legal Studies</th>
                            <th>Criminal Law</th>
                            <th>Victimology</th>
                            <th>Criminal Justice</th>
                        <?php elseif ($studentProgram == 'Information Technology'): ?>
                            <th>Programming Fundamentals</th>
                            <th>Database Management</th>
                            <th>Web Development</th>
                            <th>Networking</th>
                            <th>Operating Systems</th>
                            <th>Information Security</th>
                        <?php elseif ($studentProgram == 'Computer Science'): ?>
                            <th>Algorithms</th>
                            <th>Data Structures</th>
                            <th>Software Engineering</th>
                            <th>Computer Architecture</th>
                            <th>Artificial Intelligence</th>
                            <th>Computer Graphics</th>
                        <?php elseif ($studentProgram == 'Civil Engineer'): ?>
                            <th>Statics</th>
                            <th>Structural Analysis</th>
                            <th>Fluid Mechanics</th>
                            <th>Geotechnical Engineering</th>
                            <th>Transportation Engineering</th>
                            <th>Construction Management</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    <?php
                     // Retrieve grades from database
                     $resultGrades = $conn->query($sqlGrades);
                     if ($resultGrades->num_rows > 0) {
                         $row = $resultGrades->fetch_assoc();
                         echo "<tr>";
                         foreach ($subjects as $subject) {
                             // Check if the grade is NULL (indicating "INC")
                             if ($row[$subject] === '0.00') {
                                 echo "<td>INC</td>";
                             } else {
                                 echo "<td>{$row[$subject]}</td>";
                             }
                         }
                         echo "</tr>";
                     } else {
                         echo "<tr><td colspan='" . count($subjects) . "'>You need to be enrolled to have grades</td></tr>";
                     }
                     ?>
                    </tr>
                </tbody>
            </table><br><br>
            <table id="unenrolled-table">
                   <thead>
                    <tr><th>Average Grade</th></tr>
                   <thead>
                   <tbody>
                        <?php
                        // Retrieve grades from database
                        $resultGrades = $conn->query($sqlGrades);
                        if ($resultGrades->num_rows > 0) {
                            // Initialize variables for sum, count, and a flag for INC
                            $sum = 0;
                            $count = 0;
                            $hasINC = false;
                            
                            // Fetch each row of grades
                            while ($row = $resultGrades->fetch_assoc()) {
                                echo "<tr>";
                                foreach ($subjects as $subject) {
                                    // Check for INC grade
                                    if ($row[$subject] == 0.00) {
                                        $hasINC = true;
                                    }
                                    $sum += $row[$subject];
                                    $count++;
                                }
                            }
                            // Check if there is an INC grade
                            if ($hasINC) {
                                // Display message if INC is present
                                echo "<tr><td colspan='6'>Average cannot be calculated because you have a grade of INC</td></tr>";
                            } else {
                                // Calculate the average
                                $average = $count > 0 ? $sum / $count : 0;
                                // Display the average below the grades table
                                echo "<tr><td colspan='6'>Average: " . number_format($average, 2) . "</td></tr>";
                            }
                        } else {
                            // If no grades are found, display a message
                            echo "<tr><td colspan='" . count($subjects) . "'>You need to be enrolled to have grades</td></tr>";
                        }
                    ?>
                   </tbody>
            </table>
            </div>
        </div>


         <!-- Announcement Section -->
         <div class="announcement-container">
                <div class="list-announcement">
                    <h2 class="list-announce">List of Announcement</h2>
                    <ol class="announcement-ul">
                       <?php
                       // Include your database connection script
                       include 'db_connection.php';
           
                       // Query to fetch all announcements from announcement_table
                       $sql = "SELECT Announcement FROM announcement_table";
                       $result = $conn->query($sql);
           
                       // Check if there are any announcements
                       if ($result->num_rows > 0) {
                           // Loop through each row and display the announcements
                           while ($row = $result->fetch_assoc()) {
                               echo "<li>" . $row['Announcement'] . "</li>";
                           }
                       } else {
                           echo "<li>No announcements Posted</li>";
                       }
           
                       // Close the database connection
                       $conn->close();
                       ?>
                   </ol>
                </div>
        </div>
    </content>

</body>
</html>