<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $courseID = $_POST['courseID'];
    $studentID = $_POST['studentID'];
    $enrollmentDate = $_POST['enrollmentDate'];

    // Sanitize inputs to prevent SQL injection
    $courseID = $conn->real_escape_string($courseID);
    $studentID = $conn->real_escape_string($studentID);
    $enrollmentDate = $conn->real_escape_string($enrollmentDate);

    // Insert data into the Enrollments table
    $sql = "INSERT INTO Enrollments (CourseID, StudentID, EnrollmentDate)
            VALUES ('$courseID', '$studentID', '$enrollmentDate')";

    if ($conn->query($sql) === TRUE) {
        echo "<p style='color: green; text-align: center;'>Enrollment successful</p>";
    } else {
        echo "<p style='color: red; text-align: center;'>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }

    $conn->close();
}

// Fetch courses and students for dropdowns
$courseQuery = "SELECT CourseID, CourseName FROM Courses";
$courseResult = $conn->query($courseQuery);

$studentQuery = "SELECT StudentID, Name FROM Students";
$studentResult = $conn->query($studentQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Enrollment</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" href="fav_icon.png" type="image/png"> 
    <!-- Google Fonts -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Saira:wght@400;500;600&family=Ubuntu&display=swap');

        body {
            font-family: 'Saira', sans-serif;
        }

        h1, h2, h3 {
            font-family: 'Ubuntu', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- navbar.php -->
<nav class="bg-white shadow-md fixed top-0 left-0 w-full z-50">
    <div class="container mx-auto px-6 py-3 flex justify-between items-center">
        <a href="index.php" class="text-xl font-bold text-gray-800 hover:text-gray-600">E-Learning Platform</a>
        <div class="flex space-x-4">
            <a href="course_create.php" class="text-gray-800 hover:text-gray-600">Create Course</a>
            <a href="instructor.php" class="text-gray-800 hover:text-gray-600">Instructor</a>
            <a href="assignments.php" class="text-gray-800 hover:text-gray-600">Assignments</a>
            <a href="course_enroll.php" class="text-gray-800 hover:text-gray-600">Course Enrollment</a>
            <a href="manage_assessments.php" class="text-gray-800 hover:text-gray-600">Assessments</a>
            <a href="manage_quizzes.php" class="text-gray-800 hover:text-gray-600">Quizzes</a>
            <a href="grades.php" class="text-gray-800 hover:text-gray-600">Grades</a> 
            <a href="report.php" class="text-gray-800 hover:text-gray-600">Report</a> 
        </div>
    </div>
</nav>

    <div class="flex justify-center items-center h-screen">
        <div class="w-full max-w-lg">
            <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <h2 class="text-2xl font-bold text-center text-gray-700 mb-4">Course Enrollment</h2>
                <form action="course_enroll.php" method="post">
                    <!-- Course Dropdown -->
                    <div class="mb-4">
                        <label for="courseID" class="block text-gray-700 text-sm font-bold mb-2">Select Course</label>
                        <select id="courseID" name="courseID" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            <option value="">-- Select a Course --</option>
                            <?php while ($row = $courseResult->fetch_assoc()): ?>
                                <option value="<?php echo $row['CourseID']; ?>"><?php echo $row['CourseName']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <!-- Student Dropdown -->
                    <div class="mb-4">
                        <label for="studentID" class="block text-gray-700 text-sm font-bold mb-2">Select Student</label>
                        <select id="studentID" name="studentID" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            <option value="">-- Select a Student --</option>
                            <?php while ($row = $studentResult->fetch_assoc()): ?>
                                <option value="<?php echo $row['StudentID']; ?>"><?php echo $row['Name']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <!-- Enrollment Date -->
                    <div class="mb-4">
                        <label for="enrollmentDate" class="block text-gray-700 text-sm font-bold mb-2">Enrollment Date</label>
                        <input type="date" id="enrollmentDate" name="enrollmentDate" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-between">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Enroll</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
