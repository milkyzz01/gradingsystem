<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['adminUsername'])) {
    // If not logged in, redirect to the login page
    header("Location: index.php");
    exit();
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="adminPage.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<script>
         function enrollStudent(studentID) {
        // Create a new XMLHttpRequest object
        var xhr = new XMLHttpRequest();
        
        // Configure it: POST-request to the 'enroll_student.php' endpoint
        xhr.open("POST", "enroll_student.php", true);
        
        // Set up the request headers
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        
        // Define what happens on successful data submission
        xhr.onload = function () {
            if (xhr.status == 200) {
                Swal.fire({
                    title: 'Success!',
                    text: xhr.responseText,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    // Reload the page after the SweetAlert is closed
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: 'Error: ' + xhr.statusText,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        };
        
        // Define what happens in case of an error
        xhr.onerror = function () {
            // Replace the regular alert with SweetAlert
            Swal.fire({
                title: 'Error!',
                text: 'Request failed',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        };
        
        // Set up the request payload
        var payload = "studentID=" + studentID;
        // Send the request
        xhr.send(payload);
    }
    </script>
<body>
<header>
        <ul>
            <li><img src="assets/wislogo.jpg" class="logo"></li>
            <li>Westmead International School</li>
        </ul>
        <div class="student-logout">
            <p class="announce-popup" onclick="showPopup2()">Announcement</p>
            <p><a href="logout.php">Logout</a></p>
        </div>
    </header>
    <content>
        <div class="profile">
          <div class="adminprofile">
          <!-- Insert the profile pic for admin -->
          <?php
          include 'db_connection.php';
          // Query to select the image path from the database
          $result = $conn->query("SELECT adminImg FROM admin_table WHERE adminIDD = 2"); // Change 'your_table' and 'id' as per your table structure
          if ($result->num_rows > 0) {
              $row = $result->fetch_assoc();
              $imagePath = $row['adminImg'];
              // Display the image using HTML <img> tag
              echo '<img src="' . $imagePath . '" alt="Image" class="adminPic">';
          } else {
              echo "Image not found.";
          }
          ?>
          <!-- Insert the name for admin -->
          <?php
          include 'db_connection.php';
          // Query to select the image path from the database
          $result = $conn->query("SELECT adminName FROM admin_table WHERE adminIDD = 2"); // Change 'your_table' and 'id' as per your table structure
          if ($result->num_rows > 0) {
              $row = $result->fetch_assoc();
              $adminName = $row['adminName'];
              // Display the image using HTML <img> tag
              echo '<p class="adminName">' . $adminName . '</p>';
          } else {
              echo "name not found.";
          }
          ?>
          </div>
          <div class="adminInfo">
            <p>School: Westmead International School</p>
            <p>Address: Alangilan Batangas City</p>
            <p>Age: 33</p>
            <p class="studentStatus">Type: Professor</p>
          </div>
        </div>

        <!-- Displayed the list of student who is unenrolled -->
        <div class="list-unenrolled">
        <div>
        <h2 class="list1">List of Students Who Registered</h2>
        <div style="overflow:auto; height:180px">
        <table id="unenrolled-table" style="overflow:auto; height:180px">
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Student Name</th>
                        <th>Student Program</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    // Query to fetch unenrolled students from student_table
                    $sql = "SELECT studentID, studentName, studentProgram FROM student_table WHERE studentStatus = 'Not-Enrolled'";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['studentID'] . "</td>";
                            echo "<td>" . $row['studentName'] . "</td>";
                            echo "<td>" . $row['studentProgram'] . "</td>";
                            echo "<td><button class='adds-students-btn' onclick='enrollStudent(" . $row['studentID'] . ")'>Enroll</button></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No unenrolled students found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            </div>
            </div>
        </div>

        <!-- Displayed the list of student who is enrolled in Criminology -->
        <div class="list-unenrolleddd">
        <div>
        <h2 class="list-enrolled-title">List of Enrolled Criminology Students</h2>
        <div style="overflow:auto;">
        <table id="unenrolled-table" style="overflow:auto; height:180px">
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Student Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                   // Query to fetch enrolled students from student_table
                   $sql = "SELECT studentID, studentName FROM student_table WHERE studentStatus = 'Enrolled' AND studentProgram = 'Criminology'";
                   $result = $conn->query($sql);
                   
                   if ($result->num_rows > 0) {
                       while ($row = $result->fetch_assoc()) {
                           echo "<tr>";
                           echo "<td>" . $row['studentID'] . "</td>";
                           echo "<td>" . $row['studentName'] . "</td>";
                           echo "<td>";
                           echo "<div class='buttons-enrolled-un'><button class='adds-grade-btn' onclick='showPopupCrimPrelims(" . $row['studentID'] . ", \"" . $row['studentName'] . "\")'>Prelims</button>";
                           echo "<div class='buttons-enrolled-un'><button class='adds-grade-btn' onclick='showPopupCrimMidterm(" . $row['studentID'] . ", \"" . $row['studentName'] . "\")'>Midterm</button>";
                           echo "<div class='buttons-enrolled-un'><button class='adds-grade-btn' onclick='showPopupCrimFinals(" . $row['studentID'] . ", \"" . $row['studentName'] . "\")'>Finals</button>";
                           echo "<button class='delete-student-btn' onclick='deleteStudent(" . $row['studentID'] . ")'>Delete Student</button></div>";
                           echo "</td>";
                           echo "</tr>";
                       }
                   } else {
                       echo "<tr><td colspan='3'>No enrolled students found</td></tr>";
                   }
                   ?>
                </tbody>
            </table>
            </div>
            </div>
        </div>

        <!-- Displayed the list of student who is enrolled in Information Tech -->
        <div class="list-unenrolleddd">
        <div>
        <h2 class="list-enrolled-title">List of Enrolled IT Students</h2>
        <div style="overflow:auto;">
        <table id="unenrolled-table" style="overflow:auto; height:180px">
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Student Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                   // Query to fetch enrolled students from student_table
                   $sql = "SELECT studentID, studentName FROM student_table WHERE studentStatus = 'Enrolled' AND studentProgram = 'Information Technology'";
                   $result = $conn->query($sql);
                   
                   if ($result->num_rows > 0) {
                       while ($row = $result->fetch_assoc()) {
                           echo "<tr>";
                           echo "<td>" . $row['studentID'] . "</td>";
                           echo "<td>" . $row['studentName'] . "</td>";
                           echo "<td>";
                           echo "<div class='buttons-enrolled-un'><button class='adds-grade-btn' onclick='showPopupITprelims(" . $row['studentID'] . ", \"" . $row['studentName'] . "\")'>Prelims</button>";
                           echo "<div class='buttons-enrolled-un'><button class='adds-grade-btn' onclick='showPopupITmidterm(" . $row['studentID'] . ", \"" . $row['studentName'] . "\")'>Midterm</button>";
                           echo "<div class='buttons-enrolled-un'><button class='adds-grade-btn' onclick='showPopupITFinals(" . $row['studentID'] . ", \"" . $row['studentName'] . "\")'>Finals</button>";
                           echo "<button class='delete-student-btn' onclick='deleteStudent(" . $row['studentID'] . ")'>Delete Student</button></div>";
                           echo "</td>";
                           echo "</tr>";
                       }
                   } else {
                       echo "<tr><td colspan='3'>No enrolled students found</td></tr>";
                   }
                   ?>
                </tbody>
            </table>
            </div>
            </div>
        </div>

        <!-- Displayed the list of student who is enrolled in Com Sci -->
        <div class="list-unenrolleddd">
        <div>
        <h2 class="list-enrolled-title">List of Enrolled Computer Science Students</h2>
        <div style="overflow:auto;">
        <table id="unenrolled-table" style="overflow:auto; height:180px">
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Student Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                   // Query to fetch enrolled students from student_table
                   $sql = "SELECT studentID, studentName FROM student_table WHERE studentStatus = 'Enrolled' AND studentProgram = 'Computer Science'";
                   $result = $conn->query($sql);
                   
                   if ($result->num_rows > 0) {
                       while ($row = $result->fetch_assoc()) {
                           echo "<tr>";
                           echo "<td>" . $row['studentID'] . "</td>";
                           echo "<td>" . $row['studentName'] . "</td>";
                           echo "<td>";
                           echo "<div class='buttons-enrolled-un'><button class='adds-grade-btn' onclick='showPopupComsciPrelims(" . $row['studentID'] . ", \"" . $row['studentName'] . "\")'>Prelims</button>";
                           echo "<div class='buttons-enrolled-un'><button class='adds-grade-btn' onclick='showPopupComsciMidterm(" . $row['studentID'] . ", \"" . $row['studentName'] . "\")'>Midterm</button>";
                           echo "<div class='buttons-enrolled-un'><button class='adds-grade-btn' onclick='showPopupComsciFinals(" . $row['studentID'] . ", \"" . $row['studentName'] . "\")'>Finals</button>";
                           echo "<button class='delete-student-btn' onclick='deleteStudent(" . $row['studentID'] . ")'>Delete Student</button></div>";
                           echo "</td>";
                           echo "</tr>";
                       }
                   } else {
                       echo "<tr><td colspan='3'>No enrolled students found</td></tr>";
                   }
                   ?>
                </tbody>
            </table>
            </div>
            </div>
        </div>

        <!-- Displayed the list of student who is enrolled in Civil Engr -->
        <div class="list-unenrolleddd">
        <div>
        <h2 class="list-enrolled-title">List of Enrolled Civil Engineering Students</h2>
        <div style="overflow:auto;">
        <table id="unenrolled-table" style="overflow:auto; height:180px">
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Student Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                   // Query to fetch enrolled students from student_table
                   $sql = "SELECT studentID, studentName FROM student_table WHERE studentStatus = 'Enrolled' AND studentProgram = 'Civil Engineer'";
                   $result = $conn->query($sql);
                   
                   if ($result->num_rows > 0) {
                       while ($row = $result->fetch_assoc()) {
                           echo "<tr>";
                           echo "<td>" . $row['studentID'] . "</td>";
                           echo "<td>" . $row['studentName'] . "</td>";
                           echo "<td>";
                           echo "<div class='buttons-enrolled-un'><button class='adds-grade-btn' onclick='showPopupCivilPrelims(" . $row['studentID'] . ", \"" . $row['studentName'] . "\")'>Prelims</button>";
                           echo "<div class='buttons-enrolled-un'><button class='adds-grade-btn' onclick='showPopupCivilMidterm(" . $row['studentID'] . ", \"" . $row['studentName'] . "\")'>Midterm</button>";
                           echo "<div class='buttons-enrolled-un'><button class='adds-grade-btn' onclick='showPopupCivilFinals(" . $row['studentID'] . ", \"" . $row['studentName'] . "\")'>Finals</button>";
                           echo "<button class='delete-student-btn' onclick='deleteStudent(" . $row['studentID'] . ")'>Delete Student</button></div>";
                           echo "</td>";
                           echo "</tr>";
                       }
                   } else {
                       echo "<tr><td colspan='3'>No enrolled students found</td></tr>";
                   }
                   ?>
                </tbody>
            </table>
            </div>
            </div>
        </div>


        <!-- Popup Div for crim PRELIMS  -->
        <div id="popupCrimPrelims" class="popup">
        <form id="grade-prelims-crim" onsubmit="event.preventDefault(); submitFormCrimPrelims();"> 
             <h2 class="input-grades">Input Prelims Grades</h2>
             <label for="math">Crime Scene Investigation Grade:</label>
             <select id="CrimeScene" name="CrimeScene" required>
                 <option value="1.00">1.00</option>
                 <option value="1.25">1.25</option>
                 <option value="1.50">1.50</option>
                 <option value="1.75">1.75</option>
                 <option value="2.00">2.00</option>
                 <option value="2.25">2.25</option>
                 <option value="2.50">2.50</option>
                 <option value="2.75">2.75</option>
                 <option value="3.00">3.00</option>
                 <option value="5.00">5.00</option>
                 <option value="INC">INC</option>
             </select><br><br>
             
             <label for="filipino">Forensic Psychology Grade:</label>
             <select id="Forensic" name="Forensic" required>
                 <option value="1.00">1.00</option>
                 <option value="1.25">1.25</option>
                 <option value="1.50">1.50</option>
                 <option value="1.75">1.75</option>
                 <option value="2.00">2.00</option>
                 <option value="2.25">2.25</option>
                 <option value="2.50">2.50</option>
                 <option value="2.75">2.75</option>
                 <option value="3.00">3.00</option>
                 <option value="5.00">5.00</option>
                 <option value="INC">INC</option>
             </select><br><br>
             
             <label for="english">Legal Studies Grade:</label>
             <select id="Legal" name="Legal" required>
                 <option value="1.00">1.00</option>
                 <option value="1.25">1.25</option>
                 <option value="1.50">1.50</option>
                 <option value="1.75">1.75</option>
                 <option value="2.00">2.00</option>
                 <option value="2.25">2.25</option>
                 <option value="2.50">2.50</option>
                 <option value="2.75">2.75</option>
                 <option value="3.00">3.00</option>
                 <option value="5.00">5.00</option>
                 <option value="INC">INC</option>
             </select><br><br>
             
             <label for="science">Criminal Law Grade:</label>
             <select id="Criminal" name="Criminal" required>
                 <option value="1.00">1.00</option>
                 <option value="1.25">1.25</option>
                 <option value="1.50">1.50</option>
                 <option value="1.75">1.75</option>
                 <option value="2.00">2.00</option>
                 <option value="2.25">2.25</option>
                 <option value="2.50">2.50</option>
                 <option value="2.75">2.75</option>
                 <option value="3.00">3.00</option>
                 <option value="5.00">5.00</option>
                 <option value="INC">INC</option>
             </select><br><br>
             
             <label for="pe">Victimology Grade:</label>
             <select id="Victimology" name="Victimology" required>
                 <option value="1.00">1.00</option>
                 <option value="1.25">1.25</option>
                 <option value="1.50">1.50</option>
                 <option value="1.75">1.75</option>
                 <option value="2.00">2.00</option>
                 <option value="2.25">2.25</option>
                 <option value="2.50">2.50</option>
                 <option value="2.75">2.75</option>
                 <option value="3.00">3.00</option>
                 <option value="5.00">5.00</option>
                 <option value="INC">INC</option>
             </select><br><br>
             
             <label for="ap">Criminal Justice Grade:</label>
             <select id="Justice" name="Justice" required>
                 <option value="1.00">1.00</option>
                 <option value="1.25">1.25</option>
                 <option value="1.50">1.50</option>
                 <option value="1.75">1.75</option>
                 <option value="2.00">2.00</option>
                 <option value="2.25">2.25</option>
                 <option value="2.50">2.50</option>
                 <option value="2.75">2.75</option>
                 <option value="3.00">3.00</option>
                 <option value="5.00">5.00</option>
                 <option value="INC">INC</option>
             </select><br><br>
             <input type="hidden" id="CrimPrelimsstudentID" name="CrimPrelimsstudentID">
             <input type="hidden" id="CrimPrelimsstudentName" name="CrimPrelimsstudentName">
             
             <input class="grade-submit" type="submit" value="Submit">
             <button class="grade-back" type="button" onclick="hidePopupCrimPrelims()">Close</button>
         </form>
        </div>
        <!-- Overlay crim prelims -->
        <div id="overlayCrimPrelims" class="overlay"></div>

        <!-- Popup midterm crim -->
        <div id="popupCrimMidterm" class="popup">
        <form id="grade-Midterm-crim" onsubmit="event.preventDefault(); submitFormCrimMidterm();"> 
             <h2 class="input-grades">Input Prelims Grades</h2>
             <label for="math">Crime Scene Investigation Grade:</label>
             <select id="CrimeScene" name="CrimeScene" required>
                 <option value="1.00">1.00</option>
                 <option value="1.25">1.25</option>
                 <option value="1.50">1.50</option>
                 <option value="1.75">1.75</option>
                 <option value="2.00">2.00</option>
                 <option value="2.25">2.25</option>
                 <option value="2.50">2.50</option>
                 <option value="2.75">2.75</option>
                 <option value="3.00">3.00</option>
                 <option value="5.00">5.00</option>
                 <option value="INC">INC</option>
             </select><br><br>
             
             <label for="filipino">Forensic Psychology Grade:</label>
             <select id="Forensic" name="Forensic" required>
                 <option value="1.00">1.00</option>
                 <option value="1.25">1.25</option>
                 <option value="1.50">1.50</option>
                 <option value="1.75">1.75</option>
                 <option value="2.00">2.00</option>
                 <option value="2.25">2.25</option>
                 <option value="2.50">2.50</option>
                 <option value="2.75">2.75</option>
                 <option value="3.00">3.00</option>
                 <option value="5.00">5.00</option>
                 <option value="INC">INC</option>
             </select><br><br>
             
             <label for="english">Legal Studies Grade:</label>
             <select id="Legal" name="Legal" required>
                 <option value="1.00">1.00</option>
                 <option value="1.25">1.25</option>
                 <option value="1.50">1.50</option>
                 <option value="1.75">1.75</option>
                 <option value="2.00">2.00</option>
                 <option value="2.25">2.25</option>
                 <option value="2.50">2.50</option>
                 <option value="2.75">2.75</option>
                 <option value="3.00">3.00</option>
                 <option value="5.00">5.00</option>
                 <option value="INC">INC</option>
             </select><br><br>
             
             <label for="science">Criminal Law Grade:</label>
             <select id="Criminal" name="Criminal" required>
                 <option value="1.00">1.00</option>
                 <option value="1.25">1.25</option>
                 <option value="1.50">1.50</option>
                 <option value="1.75">1.75</option>
                 <option value="2.00">2.00</option>
                 <option value="2.25">2.25</option>
                 <option value="2.50">2.50</option>
                 <option value="2.75">2.75</option>
                 <option value="3.00">3.00</option>
                 <option value="5.00">5.00</option>
                 <option value="INC">INC</option>
             </select><br><br>
             
             <label for="pe">Victimology Grade:</label>
             <select id="Victimology" name="Victimology" required>
                 <option value="1.00">1.00</option>
                 <option value="1.25">1.25</option>
                 <option value="1.50">1.50</option>
                 <option value="1.75">1.75</option>
                 <option value="2.00">2.00</option>
                 <option value="2.25">2.25</option>
                 <option value="2.50">2.50</option>
                 <option value="2.75">2.75</option>
                 <option value="3.00">3.00</option>
                 <option value="5.00">5.00</option>
                 <option value="INC">INC</option>
             </select><br><br>
             
             <label for="ap">Criminal Justice Grade:</label>
             <select id="Justice" name="Justice" required>
                 <option value="1.00">1.00</option>
                 <option value="1.25">1.25</option>
                 <option value="1.50">1.50</option>
                 <option value="1.75">1.75</option>
                 <option value="2.00">2.00</option>
                 <option value="2.25">2.25</option>
                 <option value="2.50">2.50</option>
                 <option value="2.75">2.75</option>
                 <option value="3.00">3.00</option>
                 <option value="5.00">5.00</option>
                 <option value="INC">INC</option>
             </select><br><br>
             <input type="hidden" id="CrimMidtermstudentID" name="CrimMidtermstudentID">
             <input type="hidden" id="CrimMidtermstudentName" name="CrimMidtermstudentName">
             
             <input class="grade-submit" type="submit" value="Submit">
             <button class="grade-back" type="button" onclick="hidePopupCrimMidterm()">Close</button>
         </form>
        </div>
        <!-- Overlay crim midterms -->
        <div id="overlayCrimMidterm" class="overlay"></div>

        <!-- Popup finals crim -->
        <div id="popupCrimFinals" class="popup">
        <form id="grade-Finals-crim" onsubmit="event.preventDefault(); submitFormCrimFinals();"> 
             <h2 class="input-grades">Input Finals Grades</h2>
             <label for="math">Crime Scene Investigation Grade:</label>
             <select id="CrimeScene" name="CrimeScene" required>
                 <option value="1.00">1.00</option>
                 <option value="1.25">1.25</option>
                 <option value="1.50">1.50</option>
                 <option value="1.75">1.75</option>
                 <option value="2.00">2.00</option>
                 <option value="2.25">2.25</option>
                 <option value="2.50">2.50</option>
                 <option value="2.75">2.75</option>
                 <option value="3.00">3.00</option>
                 <option value="5.00">5.00</option>
                 <option value="INC">INC</option>
             </select><br><br>
             
             <label for="filipino">Forensic Psychology Grade:</label>
             <select id="Forensic" name="Forensic" required>
                 <option value="1.00">1.00</option>
                 <option value="1.25">1.25</option>
                 <option value="1.50">1.50</option>
                 <option value="1.75">1.75</option>
                 <option value="2.00">2.00</option>
                 <option value="2.25">2.25</option>
                 <option value="2.50">2.50</option>
                 <option value="2.75">2.75</option>
                 <option value="3.00">3.00</option>
                 <option value="5.00">5.00</option>
                 <option value="INC">INC</option>
             </select><br><br>
             
             <label for="english">Legal Studies Grade:</label>
             <select id="Legal" name="Legal" required>
                 <option value="1.00">1.00</option>
                 <option value="1.25">1.25</option>
                 <option value="1.50">1.50</option>
                 <option value="1.75">1.75</option>
                 <option value="2.00">2.00</option>
                 <option value="2.25">2.25</option>
                 <option value="2.50">2.50</option>
                 <option value="2.75">2.75</option>
                 <option value="3.00">3.00</option>
                 <option value="5.00">5.00</option>
                 <option value="INC">INC</option>
             </select><br><br>
             
             <label for="science">Criminal Law Grade:</label>
             <select id="Criminal" name="Criminal" required>
                 <option value="1.00">1.00</option>
                 <option value="1.25">1.25</option>
                 <option value="1.50">1.50</option>
                 <option value="1.75">1.75</option>
                 <option value="2.00">2.00</option>
                 <option value="2.25">2.25</option>
                 <option value="2.50">2.50</option>
                 <option value="2.75">2.75</option>
                 <option value="3.00">3.00</option>
                 <option value="5.00">5.00</option>
                 <option value="INC">INC</option>
             </select><br><br>
             
             <label for="pe">Victimology Grade:</label>
             <select id="Victimology" name="Victimology" required>
                 <option value="1.00">1.00</option>
                 <option value="1.25">1.25</option>
                 <option value="1.50">1.50</option>
                 <option value="1.75">1.75</option>
                 <option value="2.00">2.00</option>
                 <option value="2.25">2.25</option>
                 <option value="2.50">2.50</option>
                 <option value="2.75">2.75</option>
                 <option value="3.00">3.00</option>
                 <option value="5.00">5.00</option>
                 <option value="INC">INC</option>
             </select><br><br>
             
             <label for="ap">Criminal Justice Grade:</label>
             <select id="Justice" name="Justice" required>
                 <option value="1.00">1.00</option>
                 <option value="1.25">1.25</option>
                 <option value="1.50">1.50</option>
                 <option value="1.75">1.75</option>
                 <option value="2.00">2.00</option>
                 <option value="2.25">2.25</option>
                 <option value="2.50">2.50</option>
                 <option value="2.75">2.75</option>
                 <option value="3.00">3.00</option>
                 <option value="5.00">5.00</option>
                 <option value="INC">INC</option>
             </select><br><br>
             <input type="hidden" id="CrimFinalsstudentID" name="CrimFinalsstudentID">
             <input type="hidden" id="CrimFinalsstudentName" name="CrimFinalsstudentName">
             
             <input class="grade-submit" type="submit" value="Submit">
             <button class="grade-back" type="button" onclick="hidePopupCrimFinals()">Close</button>
         </form>
        </div>
        <!-- Overlay crim finals -->
        <div id="overlayCrimFinals" class="overlay"></div>

        <!-- Popup Div for IT PRELIMS  -->
        <div id="popupITprelims" class="popupIT">
        <form id="grade-prelims-IT" onsubmit="event.preventDefault(); submitFormITprelims();"> 
            <h2 class="input-grades">Input Prelims Grades</h2>
            <label for="math">Programming Fundamentals Grade:</label>
            <select id="Programming" name="Programming" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            
            <label for="filipino">Database Management Grade:</label>
            <select id="Database" name="Database" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            
            <label for="english">Web Development Grade:</label>
            <select id="Web" name="Web" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            
            <label for="science">Networking Grade:</label>
            <select id="Networking" name="Networking" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            
            <label for="pe">Operating Systems Grade:</label>
            <select id="Operating" name="Operating" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            
            <label for="ap">Information Security Grade:</label>
            <select id="Information" name="Information" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            <input type="hidden" id="PrelimsstudentIDIT" name="PrelimsstudentIDIT">
            <input type="hidden" id="PrelimsstudentNameIT" name="PrelimsstudentNameIT">
            
            <input class="grade-submit" type="submit" value="Submit">
            <button class="grade-back" type="button" onclick="hidePopupITprelims()">Close</button>
        </form>
        </div>
        <!-- Overlay IT prelims -->
        <div id="overlayITprelims" class="overlayIT"></div>

        <!-- Popup Div for IT midterm  -->
        <div id="popupITmidterm" class="popupIT">
        <form id="grade-midterm-IT" onsubmit="event.preventDefault(); submitFormITmidterm();"> 
            <h2 class="input-grades">Input Midterm Grades</h2>
            <label for="math">Programming Fundamentals Grade:</label>
            <select id="Programming" name="Programming" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            
            <label for="filipino">Database Management Grade:</label>
            <select id="Database" name="Database" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            
            <label for="english">Web Development Grade:</label>
            <select id="Web" name="Web" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            
            <label for="science">Networking Grade:</label>
            <select id="Networking" name="Networking" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            
            <label for="pe">Operating Systems Grade:</label>
            <select id="Operating" name="Operating" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            
            <label for="ap">Information Security Grade:</label>
            <select id="Information" name="Information" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            <input type="hidden" id="MidtermstudentIDIT" name="MidtermstudentIDIT">
            <input type="hidden" id="MidtermstudentNameIT" name="MidtermstudentNameIT">
            
            <input class="grade-submit" type="submit" value="Submit">
            <button class="grade-back" type="button" onclick="hidePopupITmidterm()">Close</button>
        </form>
        </div>
        <!-- Overlay IT midterms -->
        <div id="overlayITmidterm" class="overlayIT"></div>

        <!-- Popup Div for IT finals -->
        <div id="popupITFinals" class="popupIT">
        <form id="grade-Finals-IT" onsubmit="event.preventDefault(); submitFormITFinals();"> 
            <h2 class="input-grades">Input Finals Grades</h2>
            <label for="math">Programming Fundamentals Grade:</label>
            <select id="Programming" name="Programming" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            
            <label for="filipino">Database Management Grade:</label>
            <select id="Database" name="Database" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            
            <label for="english">Web Development Grade:</label>
            <select id="Web" name="Web" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            
            <label for="science">Networking Grade:</label>
            <select id="Networking" name="Networking" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            
            <label for="pe">Operating Systems Grade:</label>
            <select id="Operating" name="Operating" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            
            <label for="ap">Information Security Grade:</label>
            <select id="Information" name="Information" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            <input type="hidden" id="FinalsstudentIDIT" name="FinalsstudentIDIT">
            <input type="hidden" id="FinalsstudentNameIT" name="FinalsstudentNameIT">
            
            <input class="grade-submit" type="submit" value="Submit">
            <button class="grade-back" type="button" onclick="hidePopupITFinals()">Close</button>
        </form>
        </div>
        <!-- Overlay IT finals -->
        <div id="overlayITFinals" class="overlayIT"></div>

        <!-- Popup Div for Comsci Prelims  -->
        <div id="PrelimspopupComsci" class="popupComsci">
        <form id="grade-Prelims-Comsci" onsubmit="event.preventDefault(); submitFormComsciPrelims();"> 
            <h2 class="input-grades">Input Prelims Grades</h2>
            
            <label for="math">Algorithms Grade:</label>
            <select id="Algorithms" name="Algorithms" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            
            <label for="filipino">Data Structures Grade:</label>
            <select id="Structures" name="Structures" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            
            <label for="english">Software Engineering Grade:</label>
            <select id="Software" name="Software" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            
            <label for="science">Computer Architecture Grade:</label>
            <select id="Architecture" name="Architecture" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            
            <label for="pe">Artificial Intelligence Grade:</label>
            <select id="Intelligence" name="Intelligence" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            
            <label for="ap">Computer Graphics Grade:</label>
            <select id="Graphics" name="Graphics" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            
            <input type="hidden" id="PrelimsstudentIDcomSci" name="PrelimsstudentIDcomSci">
            <input type="hidden" id="PrelimsstudentNameComSci" name="PrelimsstudentNameComSci">
            
            <input class="grade-submit" type="submit" value="Submit">
            <button class="grade-back" type="button" onclick="hidePopupComsciPrelims()">Close</button>
        </form>
        </div>
        <!-- Overlay Comsci Prelims -->
        <div id="PrelimsoverlayComsci" class="overlayComsci"></div>

        <!-- Popup Div for Comsci midterms  -->
        <div id="MidtermpopupComsci" class="popupComsci">
        <form id="grade-midterm-Comsci" onsubmit="event.preventDefault(); submitFormComsciMidterm();"> 
            <h2 class="input-grades">Input Midterm Grades</h2>
            
            <label for="math">Algorithms Grade:</label>
            <select id="Algorithms" name="Algorithms" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            
            <label for="filipino">Data Structures Grade:</label>
            <select id="Structures" name="Structures" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            
            <label for="english">Software Engineering Grade:</label>
            <select id="Software" name="Software" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            
            <label for="science">Computer Architecture Grade:</label>
            <select id="Architecture" name="Architecture" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            
            <label for="pe">Artificial Intelligence Grade:</label>
            <select id="Intelligence" name="Intelligence" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            
            <label for="ap">Computer Graphics Grade:</label>
            <select id="Graphics" name="Graphics" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            <input type="hidden" id="MidtermstudentIDcomSci" name="MidtermstudentIDcomSci">
            <input type="hidden" id="MidtermstudentNameComSci" name="MidtermstudentNameComSci">
            
            <input class="grade-submit" type="submit" value="Submit">
            <button class="grade-back" type="button" onclick="hidePopupComsciMidterm()">Close</button>
        </form>
        </div>
        <!-- Overlay Comsci midterms -->
        <div id="MidtermoverlayComsci" class="overlayComsci"></div>

        <!-- Popup Div for Comsci finals  -->
        <div id="FinalspopupComsci" class="popupComsci">
        <form id="grade-Finals-Comsci" onsubmit="event.preventDefault(); submitFormComsciFinals();"> 
            <h2 class="input-grades">Input Finals Grades</h2>
            
            <label for="math">Algorithms Grade:</label>
            <select id="Algorithms" name="Algorithms" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            
            <label for="filipino">Data Structures Grade:</label>
            <select id="Structures" name="Structures" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            
            <label for="english">Software Engineering Grade:</label>
            <select id="Software" name="Software" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            
            <label for="science">Computer Architecture Grade:</label>
            <select id="Architecture" name="Architecture" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            
            <label for="pe">Artificial Intelligence Grade:</label>
            <select id="Intelligence" name="Intelligence" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            
            <label for="ap">Computer Graphics Grade:</label>
            <select id="Graphics" name="Graphics" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            <input type="hidden" id="FinalsstudentIDcomSci" name="FinalsstudentIDcomSci">
            <input type="hidden" id="FinalsstudentNameComSci" name="FinalsstudentNameComSci">
            
            <input class="grade-submit" type="submit" value="Submit">
            <button class="grade-back" type="button" onclick="hidePopupComsciFinals()">Close</button>
        </form>
        </div>
        <!-- Overlay Comsci finals -->
        <div id="FinalsoverlayComsci" class="overlayComsci"></div>

        <!-- Popup Div for Civil Prelims  -->
        <div id="PrelimspopupCivil" class="popupCivil">
        <form id="grade-Prelims-Civil" onsubmit="event.preventDefault(); submitFormCivilPrelims();"> 
            <h2 class="input-grades">Input Prelims Grades</h2>
            
            <label for="math">Statics Grade:</label>
            <select id="Statics" name="Statics" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            
            <label for="filipino">Structural Analysis Grade:</label>
            <select id="Structural" name="Structural" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            
            <label for="english">Fluid Mechanics Grade:</label>
            <select id="Fluid" name="Fluid" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            
            <label for="science">Geotechnical Engineering Grade:</label>
            <select id="Geotechnical" name="Geotechnical" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            
            <label for="pe">Transportation Engineering Grade:</label>
            <select id="Transportation" name="Transportation" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            
            <label for="ap">Construction Management Grade:</label>
            <select id="Construction" name="Construction" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            
            <input type="hidden" id="PrelimsstudentIDcivil" name="PrelimsstudentIDcivil">
            <input type="hidden" id="PrelimsstudentNameCivil" name="PrelimsstudentNameCivil">
            
            <input class="grade-submit" type="submit" value="Submit">
            <button class="grade-back" type="button" onclick="hidePopupCivilPrelims()">Close</button>
        </form>
        </div>
        <!-- Overlay Civil prelims -->
        <div id="PrelimsoverlayCivil" class="overlayCivil"></div>

        <!-- Popup Div for Civil midterm  -->
        <div id="MidtermpopupCivil" class="popupCivil">
        <form id="grade-Midterm-Civil" onsubmit="event.preventDefault(); submitFormCivilMidterm();"> 
            <h2 class="input-grades">Input Midterm Grades</h2>
            
            <label for="math">Statics Grade:</label>
            <select id="Statics" name="Statics" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            
            <label for="filipino">Structural Analysis Grade:</label>
            <select id="Structural" name="Structural" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            
            <label for="english">Fluid Mechanics Grade:</label>
            <select id="Fluid" name="Fluid" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            
            <label for="science">Geotechnical Engineering Grade:</label>
            <select id="Geotechnical" name="Geotechnical" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            
            <label for="pe">Transportation Engineering Grade:</label>
            <select id="Transportation" name="Transportation" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            
            <label for="ap">Construction Management Grade:</label>
            <select id="Construction" name="Construction" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            <input type="hidden" id="MidtermstudentIDcivil" name="MidtermstudentIDcivil">
            <input type="hidden" id="MidtermstudentNameCivil" name="MidtermstudentNameCivil">
            
            <input class="grade-submit" type="submit" value="Submit">
            <button class="grade-back" type="button" onclick="hidePopupCivilMidterm()">Close</button>
        </form>
        </div>
        <!-- Overlay Civil midterm -->
        <div id="MidtermoverlayCivil" class="overlayCivil"></div>

        <!-- Popup Div for Civil finals  -->
        <div id="FinalspopupCivil" class="popupCivil">
        <form id="grade-Finals-Civil" onsubmit="event.preventDefault(); submitFormCivilFinals();"> 
            <h2 class="input-grades">Input Finals Grades</h2>
            
            <label for="math">Statics Grade:</label>
            <select id="Statics" name="Statics" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            
            <label for="filipino">Structural Analysis Grade:</label>
            <select id="Structural" name="Structural" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            
            <label for="english">Fluid Mechanics Grade:</label>
            <select id="Fluid" name="Fluid" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            
            <label for="science">Geotechnical Engineering Grade:</label>
            <select id="Geotechnical" name="Geotechnical" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            
            <label for="pe">Transportation Engineering Grade:</label>
            <select id="Transportation" name="Transportation" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            
            <label for="ap">Construction Management Grade:</label>
            <select id="Construction" name="Construction" required>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
                <option value="5.00">5.00</option>
                <option value="INC">INC</option>
            </select><br><br>
            <input type="hidden" id="FinalsstudentIDcivil" name="FinalsstudentIDcivil">
            <input type="hidden" id="FinalsstudentNameCivil" name="FinalsstudentNameCivil">
            
            <input class="grade-submit" type="submit" value="Submit">
            <button class="grade-back" type="button" onclick="hidePopupCivilFinals()">Close</button>
        </form>
        </div>
        <!-- Overlay Civil finals -->
        <div id="FinalsoverlayCivil" class="overlayCivil"></div>


        <!-- Announcement Section -->
        <div class="announcement-container">
            <div class="list-announcement">
                <h2 class="List-of-Announcement">List of Announcement</h2>
                <ol class="announcement-ul">
                    <?php
                    // Include your database connection script
                    include 'db_connection.php';
        
                    // Query to fetch all announcements from announcement_table
                    $sql = "SELECT Announcement, AnnouncementID FROM announcement_table";
                    $result = $conn->query($sql);
        
                    // Check if there are any announcements
                    if ($result->num_rows > 0) {
                        // Loop through each row and display the announcements
                        while ($row = $result->fetch_assoc()) {
                            // Each list item has a unique ID corresponding to the AnnouncementID
                            echo "<li onclick='deleteAnnouncement(" . $row['AnnouncementID'] . ")'>" . $row['Announcement'] . "</li>";
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

         <!-- Popup Div for announcement -->
         <div id="popupp" class="popupp">
            <form id="announcement-form" onsubmit="event.preventDefault(); submitForm2();"> 
                <h2 class="input-grades">Make an Announcement</h2>
                <label for="announce">Announcement: </label>
                <input type="text" id="announce" name="announce" required><br><br>
                <input class="announcement-submit" type="submit" value="Submit">
                <button class="grade-back" type="button" onclick="hidePopup2()">Close</button>
            </form>
         </div>

        <!-- Overlay fpor announcement -->
        <div id="overlayy" class="overlayy"></div>
    </content>



    <!-- JavaScript -->
    <script>
        // Function to show the popup for crim
        function showPopupCrimPrelims(studentID, studentName) {
            document.getElementById('popupCrimPrelims').style.display = 'block';
            document.getElementById('overlayCrimPrelims').style.display = 'block';
            // Set the studentID in the hidden input field
            document.getElementById('CrimPrelimsstudentID').value = studentID;
            document.getElementById('CrimPrelimsstudentName').value = studentName;
        }
        // Function to show the popup for crim midterm
        function showPopupCrimMidterm(studentID, studentName) {
            document.getElementById('popupCrimMidterm').style.display = 'block';
            document.getElementById('overlayCrimMidterm').style.display = 'block';
            // Set the studentID in the hidden input field
            document.getElementById('CrimMidtermstudentID').value = studentID;
            document.getElementById('CrimMidtermstudentName').value = studentName;
        }
        // Function to show the popup for crim finals
        function showPopupCrimFinals(studentID, studentName) {
            document.getElementById('popupCrimFinals').style.display = 'block';
            document.getElementById('overlayCrimFinals').style.display = 'block';
            // Set the studentID in the hidden input field
            document.getElementById('CrimFinalsstudentID').value = studentID;
            document.getElementById('CrimFinalsstudentName').value = studentName;
        }
        // Function to show the popup for IT prelims
        function showPopupITprelims(studentID, studentName) {
            document.getElementById('popupITprelims').style.display = 'block';
            document.getElementById('overlayITprelims').style.display = 'block';
            // Set the studentID in the hidden input field
            document.getElementById('PrelimsstudentIDIT').value = studentID;
            document.getElementById('PrelimsstudentNameIT').value = studentName;
        }
        // Function to show the popup for IT midterm
        function showPopupITmidterm(studentID, studentName) {
            document.getElementById('popupITmidterm').style.display = 'block';
            document.getElementById('overlayITmidterm').style.display = 'block';
            // Set the studentID in the hidden input field
            document.getElementById('MidtermstudentIDIT').value = studentID;
            document.getElementById('MidtermstudentNameIT').value = studentName;
        }
        // Function to show the popup for IT finals
        function showPopupITFinals(studentID, studentName) {
            document.getElementById('popupITFinals').style.display = 'block';
            document.getElementById('overlayITFinals').style.display = 'block';
            // Set the studentID in the hidden input field
            document.getElementById('FinalsstudentIDIT').value = studentID;
            document.getElementById('FinalsstudentNameIT').value = studentName;
        }
        // Function to show the popup for Comsci prelims
        function showPopupComsciPrelims(studentID, studentName) {
            document.getElementById('PrelimspopupComsci').style.display = 'block';
            document.getElementById('PrelimsoverlayComsci').style.display = 'block';
            // Set the studentID in the hidden input field
            document.getElementById('PrelimsstudentIDcomSci').value = studentID;
            document.getElementById('PrelimsstudentNameComSci').value = studentName;
        }
        // Function to show the popup for Comsci midterms
        function showPopupComsciMidterm(studentID, studentName) {
            document.getElementById('MidtermpopupComsci').style.display = 'block';
            document.getElementById('MidtermoverlayComsci').style.display = 'block';
            // Set the studentID in the hidden input field
            document.getElementById('MidtermstudentIDcomSci').value = studentID;
            document.getElementById('MidtermstudentNameComSci').value = studentName;
        }
        // Function to show the popup for Comsci finals
        function showPopupComsciFinals(studentID, studentName) {
            document.getElementById('FinalspopupComsci').style.display = 'block';
            document.getElementById('FinalsoverlayComsci').style.display = 'block';
            // Set the studentID in the hidden input field
            document.getElementById('FinalsstudentIDcomSci').value = studentID;
            document.getElementById('FinalsstudentNameComSci').value = studentName;
        }
        // Function to show the popup for Civil
        function showPopupCivilPrelims(studentID, studentName) {
            document.getElementById('PrelimspopupCivil').style.display = 'block';
            document.getElementById('PrelimsoverlayCivil').style.display = 'block';
            // Set the studentID in the hidden input field
            document.getElementById('PrelimsstudentIDcivil').value = studentID;
            document.getElementById('PrelimsstudentNameCivil').value = studentName;
        }
        // Function to show the popup for Civil midterm
        function showPopupCivilMidterm(studentID, studentName) {
            document.getElementById('MidtermpopupCivil').style.display = 'block';
            document.getElementById('MidtermoverlayCivil').style.display = 'block';
            // Set the studentID in the hidden input field
            document.getElementById('MidtermstudentIDcivil').value = studentID;
            document.getElementById('MidtermstudentNameCivil').value = studentName;
        }
        // Function to show the popup for Civil midterm
        function showPopupCivilFinals(studentID, studentName) {
            document.getElementById('FinalspopupCivil').style.display = 'block';
            document.getElementById('FinalsoverlayCivil').style.display = 'block';
            // Set the studentID in the hidden input field
            document.getElementById('FinalsstudentIDcivil').value = studentID;
            document.getElementById('FinalsstudentNameCivil').value = studentName;
        }
        //Function to display the announce popup
        function showPopup2() {
            document.getElementById('popupp').style.display = 'block';
            document.getElementById('overlayy').style.display = 'block';
        }
        // Function to hide popup of announcement
        function hidePopup2() {
            document.getElementById('popupp').style.display = 'none';
            document.getElementById('overlayy').style.display = 'none';
        }
        // Function to hide the popup for crim
        function hidePopupCrimPrelims() {
            document.getElementById('popupCrimPrelims').style.display = 'none';
            document.getElementById('overlayCrimPrelims').style.display = 'none';
        }
        // Function to hide the popup for crim
        function hidePopupCrimFinals() {
            document.getElementById('popupCrimFinals').style.display = 'none';
            document.getElementById('overlayCrimFinals').style.display = 'none';
        }
        // Function to hide the popup for crim midterm
        function hidePopupCrimMidterm() {
            document.getElementById('popupCrimMidterm').style.display = 'none';
            document.getElementById('overlayCrimMidterm').style.display = 'none';
        }
        // Function to hide the popup for IT prelims
        function hidePopupITprelims() {
            document.getElementById('popupITprelims').style.display = 'none';
            document.getElementById('overlayITprelims').style.display = 'none';
        }
        // Function to hide the popup for IT midterm
        function hidePopupITmidterm() {
            document.getElementById('popupITmidterm').style.display = 'none';
            document.getElementById('overlayITmidterm').style.display = 'none';
        }
        // Function to hide the popup for IT finals
        function hidePopupITFinals() {
            document.getElementById('popupITFinals').style.display = 'none';
            document.getElementById('overlayITFinals').style.display = 'none';
        }
        // Function to hide the popup for Comsci
        function hidePopupComsciPrelims() {
            document.getElementById('PrelimspopupComsci').style.display = 'none';
            document.getElementById('PrelimsoverlayComsci').style.display = 'none';
        }
        // Function to hide the popup for Comsci
        function hidePopupComsciMidterm() {
            document.getElementById('MidtermpopupComsci').style.display = 'none';
            document.getElementById('MidtermoverlayComsci').style.display = 'none';
        }
        // Function to hide the popup for Comsci
        function hidePopupComsciFinals() {
            document.getElementById('FinalspopupComsci').style.display = 'none';
            document.getElementById('FinalsoverlayComsci').style.display = 'none';
        }
        // Function to hide the popup for Civil
        function hidePopupCivilPrelims() {
            document.getElementById('PrelimspopupCivil').style.display = 'none';
            document.getElementById('PrelimsoverlayCivil').style.display = 'none';
        }
        // Function to hide the popup for Civil
        function hidePopupCivilMidterm() {
            document.getElementById('MidtermpopupCivil').style.display = 'none';
            document.getElementById('MidtermoverlayCivil').style.display = 'none';
        }
        // Function to hide the popup for Civil
        function hidePopupCivilFinals() {
            document.getElementById('FinalspopupCivil').style.display = 'none';
            document.getElementById('FinalsoverlayCivil').style.display = 'none';
        }



        
        //Function to update grades of crim students
        // Function to handle form submission
        function submitFormCrimPrelims() {
        // Submit the form using AJAX
        var form = document.getElementById('grade-prelims-crim');
        var formData = new FormData(form);

        // Create a new XMLHttpRequest object
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "prelims_grades_crim.php", true);
        xhr.onload = function () {
            if (xhr.status == 200) {
                // Show success message using SweetAlert
                Swal.fire({
                    title: 'Success!',
                    text: xhr.responseText,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Reload the page after the SweetAlert is closed
                        location.reload();
                    }
                });
            } else {
                // Show error message using SweetAlert
                Swal.fire({
                    title: 'Error!',
                    text: 'Error: ' + xhr.statusText,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        };
        xhr.onerror = function () {
            // Show error message using SweetAlert
            Swal.fire({
                title: 'Error!',
                text: 'Request failed',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        };
        xhr.send(formData);
    }

    //Function to update grades of crim students
        // Function to handle form submission
        function submitFormCrimMidterm() {
        // Submit the form using AJAX
        var form = document.getElementById('grade-Midterm-crim');
        var formData = new FormData(form);

        // Create a new XMLHttpRequest object
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "midterm_grades_crim.php", true);
        xhr.onload = function () {
            if (xhr.status == 200) {
                // Show success message using SweetAlert
                Swal.fire({
                    title: 'Success!',
                    text: xhr.responseText,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Reload the page after the SweetAlert is closed
                        location.reload();
                    }
                });
            } else {
                // Show error message using SweetAlert
                Swal.fire({
                    title: 'Error!',
                    text: 'Error: ' + xhr.statusText,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        };
        xhr.onerror = function () {
            // Show error message using SweetAlert
            Swal.fire({
                title: 'Error!',
                text: 'Request failed',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        };
        xhr.send(formData);
    }

    //Function to update grades of crim students finals
        // Function to handle form submission
        function submitFormCrimFinals() {
        // Submit the form using AJAX
        var form = document.getElementById('grade-Finals-crim');
        var formData = new FormData(form);

        // Create a new XMLHttpRequest object
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "finals_grades_crim.php", true);
        xhr.onload = function () {
            if (xhr.status == 200) {
                // Show success message using SweetAlert
                Swal.fire({
                    title: 'Success!',
                    text: xhr.responseText,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Reload the page after the SweetAlert is closed
                        location.reload();
                    }
                });
            } else {
                // Show error message using SweetAlert
                Swal.fire({
                    title: 'Error!',
                    text: 'Error: ' + xhr.statusText,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        };
        xhr.onerror = function () {
            // Show error message using SweetAlert
            Swal.fire({
                title: 'Error!',
                text: 'Request failed',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        };
        xhr.send(formData);
    }


    //Function to update grades of IT students prelims
        // Function to handle form submission
        function submitFormITprelims() {
        // Submit the form using AJAX
        var formIT = document.getElementById('grade-prelims-IT');
        var formData = new FormData(formIT);

        // Create a new XMLHttpRequest object
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "prelims_grades_it.php", true);
        xhr.onload = function () {
            if (xhr.status == 200) {
                console.log(formData);
                // Show success message using SweetAlert
                Swal.fire({
                    title: 'Success!',
                    text: xhr.responseText,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Reload the page after the SweetAlert is closed
                        location.reload();
                    }
                });
            } else {
                // Show error message using SweetAlert
                Swal.fire({
                    title: 'Error!',
                    text: 'Error: ' + xhr.statusText,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        };
        xhr.onerror = function () {
            // Show error message using SweetAlert
            Swal.fire({
                title: 'Error!',
                text: 'Request failed',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        };
        xhr.send(formData);
    }

    //Function to update grades of IT students midterm
        // Function to handle form submission
        function submitFormITmidterm() {
        // Submit the form using AJAX
        var formIT = document.getElementById('grade-midterm-IT');
        var formData = new FormData(formIT);

        // Create a new XMLHttpRequest object
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "midterm_grades_it.php", true);
        xhr.onload = function () {
            if (xhr.status == 200) {
                console.log(formData);
                // Show success message using SweetAlert
                Swal.fire({
                    title: 'Success!',
                    text: xhr.responseText,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Reload the page after the SweetAlert is closed
                        location.reload();
                    }
                });
            } else {
                // Show error message using SweetAlert
                Swal.fire({
                    title: 'Error!',
                    text: 'Error: ' + xhr.statusText,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        };
        xhr.onerror = function () {
            // Show error message using SweetAlert
            Swal.fire({
                title: 'Error!',
                text: 'Request failed',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        };
        xhr.send(formData);
    }

    //Function to update grades of IT students midterm
        // Function to handle form submission
        function submitFormITFinals() {
        // Submit the form using AJAX
        var formIT = document.getElementById('grade-Finals-IT');
        var formData = new FormData(formIT);

        // Create a new XMLHttpRequest object
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "finals_grades_it.php", true);
        xhr.onload = function () {
            if (xhr.status == 200) {
                console.log(formData);
                // Show success message using SweetAlert
                Swal.fire({
                    title: 'Success!',
                    text: xhr.responseText,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Reload the page after the SweetAlert is closed
                        location.reload();
                    }
                });
            } else {
                // Show error message using SweetAlert
                Swal.fire({
                    title: 'Error!',
                    text: 'Error: ' + xhr.statusText,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        };
        xhr.onerror = function () {
            // Show error message using SweetAlert
            Swal.fire({
                title: 'Error!',
                text: 'Request failed',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        };
        xhr.send(formData);
    }

    //Function to update grades of Comsci students prelims
        // Function to handle form submission
        function submitFormComsciPrelims() {
        // Submit the form using AJAX
        var formComsci = document.getElementById('grade-Prelims-Comsci');
        var formData = new FormData(formComsci);

        // Create a new XMLHttpRequest object
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "prelims_grades_comsci.php", true);
        xhr.onload = function () {
            if (xhr.status == 200) {
                console.log(formData);
                // Show success message using SweetAlert
                Swal.fire({
                    title: 'Success!',
                    text: xhr.responseText,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Reload the page after the SweetAlert is closed
                        location.reload();
                    }
                });
            } else {
                // Show error message using SweetAlert
                Swal.fire({
                    title: 'Error!',
                    text: 'Error: ' + xhr.statusText,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        };
        xhr.onerror = function () {
            // Show error message using SweetAlert
            Swal.fire({
                title: 'Error!',
                text: 'Request failed',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        };
        xhr.send(formData);
    }

    //Function to update grades of Comsci students midterm
        // Function to handle form submission
        function submitFormComsciMidterm() {
        // Submit the form using AJAX
        var formComsci = document.getElementById('grade-midterm-Comsci');
        var formData = new FormData(formComsci);

        // Create a new XMLHttpRequest object
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "midterm_grades_comsci.php", true);
        xhr.onload = function () {
            if (xhr.status == 200) {
                console.log(formData);
                // Show success message using SweetAlert
                Swal.fire({
                    title: 'Success!',
                    text: xhr.responseText,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Reload the page after the SweetAlert is closed
                        location.reload();
                    }
                });
            } else {
                // Show error message using SweetAlert
                Swal.fire({
                    title: 'Error!',
                    text: 'Error: ' + xhr.statusText,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        };
        xhr.onerror = function () {
            // Show error message using SweetAlert
            Swal.fire({
                title: 'Error!',
                text: 'Request failed',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        };
        xhr.send(formData);
    }

    //Function to update grades of Comsci students finals
        // Function to handle form submission
        function submitFormComsciFinals() {
        // Submit the form using AJAX
        var formComsci = document.getElementById('grade-Finals-Comsci');
        var formData = new FormData(formComsci);

        // Create a new XMLHttpRequest object
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "finals_grades_comsci.php", true);
        xhr.onload = function () {
            if (xhr.status == 200) {
                console.log(formData);
                // Show success message using SweetAlert
                Swal.fire({
                    title: 'Success!',
                    text: xhr.responseText,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Reload the page after the SweetAlert is closed
                        location.reload();
                    }
                });
            } else {
                // Show error message using SweetAlert
                Swal.fire({
                    title: 'Error!',
                    text: 'Error: ' + xhr.statusText,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        };
        xhr.onerror = function () {
            // Show error message using SweetAlert
            Swal.fire({
                title: 'Error!',
                text: 'Request failed',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        };
        xhr.send(formData);
    }

    //Function to update grades of Comsci students
        // Function to handle form submission
        function submitFormCivilPrelims() {
        // Submit the form using AJAX
        var formCivil = document.getElementById('grade-Prelims-Civil');
        var formData = new FormData(formCivil);

        // Create a new XMLHttpRequest object
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "prelims_grades_civil.php", true);
        xhr.onload = function () {
            if (xhr.status == 200) {
                console.log(formData);
                // Show success message using SweetAlert
                Swal.fire({
                    title: 'Success!',
                    text: xhr.responseText,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Reload the page after the SweetAlert is closed
                        location.reload();
                    }
                });
            } else {
                // Show error message using SweetAlert
                Swal.fire({
                    title: 'Error!',
                    text: 'Error: ' + xhr.statusText,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        };
        xhr.onerror = function () {
            // Show error message using SweetAlert
            Swal.fire({
                title: 'Error!',
                text: 'Request failed',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        };
        xhr.send(formData);
    }

    //Function to update grades of Comsci students
        // Function to handle form submission
        function submitFormCivilMidterm() {
        // Submit the form using AJAX
        var formCivil = document.getElementById('grade-Midterm-Civil');
        var formData = new FormData(formCivil);

        // Create a new XMLHttpRequest object
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "midterm_grades_civil.php", true);
        xhr.onload = function () {
            if (xhr.status == 200) {
                console.log(formData);
                // Show success message using SweetAlert
                Swal.fire({
                    title: 'Success!',
                    text: xhr.responseText,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Reload the page after the SweetAlert is closed
                        location.reload();
                    }
                });
            } else {
                // Show error message using SweetAlert
                Swal.fire({
                    title: 'Error!',
                    text: 'Error: ' + xhr.statusText,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        };
        xhr.onerror = function () {
            // Show error message using SweetAlert
            Swal.fire({
                title: 'Error!',
                text: 'Request failed',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        };
        xhr.send(formData);
    }

    //Function to update grades of civil finals
        // Function to handle form submission
        function submitFormCivilFinals() {
        // Submit the form using AJAX
        var formCivil = document.getElementById('grade-Finals-Civil');
        var formData = new FormData(formCivil);

        // Create a new XMLHttpRequest object
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "finals_grades_civil.php", true);
        xhr.onload = function () {
            if (xhr.status == 200) {
                console.log(formData);
                // Show success message using SweetAlert
                Swal.fire({
                    title: 'Success!',
                    text: xhr.responseText,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Reload the page after the SweetAlert is closed
                        location.reload();
                    }
                });
            } else {
                // Show error message using SweetAlert
                Swal.fire({
                    title: 'Error!',
                    text: 'Error: ' + xhr.statusText,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        };
        xhr.onerror = function () {
            // Show error message using SweetAlert
            Swal.fire({
                title: 'Error!',
                text: 'Request failed',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        };
        xhr.send(formData);
    }




    //Function to send xml http request to announcementphp
    // Function to handle form submission
     function submitForm2() {
    // Get form data
    var form = document.getElementById('announcement-form');
    var formData = new FormData(form);

    // Create XMLHttpRequest object
    var xhr = new XMLHttpRequest();

    // Configure POST request
    xhr.open('POST', 'insert_announcement.php', true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

    // Define callback function for when the request completes
    xhr.onload = function() {
        if (xhr.status === 200) {
            // Display success message or handle response as needed
            Swal.fire({
                    title: 'Success!',
                    text: xhr.responseText,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Reload the page after the SweetAlert is closed
                        location.reload();
                    }
                });
        } else {
            // Display error message
            Swal.fire({
                    title: 'Error!',
                    text: 'Error: ' + xhr.statusText,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
        }
    };
    // Send form data
    xhr.send(formData);
}


    //Function to delete an announcement
     // JavaScript function to delete an announcement
     function deleteAnnouncement(announcementID) {
        Swal.fire({
        title: 'Are you sure you?',
        text: 'You won\'t be able to revert this!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
        // Create a new XMLHttpRequest object
        var xhr = new XMLHttpRequest();
        
        // Configure the request
        xhr.open("POST", "delete_announcement.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        // Define what happens on successful data submission
        xhr.onload = function () {
            if (xhr.status == 200) {
                Swal.fire({
                    title: 'Success!',
                    text: xhr.responseText,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Reload the page after the SweetAlert is closed
                        location.reload();
                    }
                });
            } else {
                // Handle errors if any
                console.log('Error:', xhr.statusText);
            }
        };
        
        // Define what happens in case of an error
        xhr.onerror = function () {
            console.log('Request failed');
        };
        
        // Send the request with the announcementID as data
        xhr.send("announcementID=" + announcementID);
    }
    });
}



    //Function of xml to delete or unenrolled students
    function deleteStudent(studentID) {
    // Use SweetAlert2 for confirmation
    Swal.fire({
        title: 'Are you sure?',
        text: 'You won\'t be able to revert this!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Create a new XMLHttpRequest object
            var xhr = new XMLHttpRequest();

            // Configure the request
            xhr.open("POST", "delete_student.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            // Define what happens on successful data submission
            xhr.onload = function() {
                if (xhr.status == 200) {
                    // Display the response message with SweetAlert2
                    Swal.fire({
                        title: 'Success!',
                        text: xhr.responseText,
                        icon: 'success'
                    }).then(() => {
                        // Reload the page after deletion
                        location.reload();
                    });
                } else {
                    // Display error message with SweetAlert2
                    Swal.fire({
                        title: 'Error!',
                        text: 'Error: ' + xhr.statusText,
                        icon: 'error'
                    });
                }
            };
            // Define what happens in case of an error
            xhr.onerror = function() {
                // Display error message with SweetAlert2
                Swal.fire({
                    title: 'Error!',
                    text: 'Request failed',
                    icon: 'error'
                });
            };

            // Send the request
            xhr.send("studentID=" + studentID);
        }
    });
}


    </script>
</body>
</html>