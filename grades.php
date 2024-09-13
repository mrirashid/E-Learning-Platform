<?php
include 'db.php';

// Handle form submission for adding grades
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $studentID = $_POST['studentID'];
    $courseID = $_POST['courseID'];
    $gpa = $_POST['gpa'];
    $feedback = $_POST['feedback'];
    $dateGraded = date('Y-m-d');

    $sql = "INSERT INTO grades (StudentID, CourseID, GPA, Feedback, DateGraded) 
            VALUES ('$studentID', '$courseID', '$gpa', '$feedback', '$dateGraded')";

    if ($conn->query($sql) === TRUE) {
        // Redirect to avoid duplicate submission
        header("Location: grades.php");
        exit();
    } else {
        echo "<div class='bg-red-100 border-l-4 border-red-500 text-red-700 p-4' role='alert'>
                <p class='font-bold'>Error</p>
                <p>Error: " . $sql . "<br>" . $conn->error . "</p>
              </div>";
    }
}

// Fetch grades for students
$sql = "SELECT g.GradeID, s.Name as StudentName, g.GPA, g.Feedback, g.DateGraded 
        FROM grades g 
        JOIN students s ON g.StudentID = s.StudentID";
$result = $conn->query($sql);

// Fetch students for the form dropdowns
$students = $conn->query("SELECT StudentID, Name FROM students");

// Fetch courses for the form dropdowns
$courses = $conn->query("SELECT CourseID, Title FROM courses");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Student Grades</title>
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
            <a href="instructor_assignments.php" class="text-gray-800 hover:text-gray-600">Assignments</a>
            <a href="course_enroll.php" class="text-gray-800 hover:text-gray-600">Course Enrollment</a>
            <a href="assignments.php" class="text-gray-800 hover:text-gray-600">Assignments</a>
            <a href="manage_quizzes.php" class="text-gray-800 hover:text-gray-600">Quizzes</a>
            <a href="grades.php" class="text-gray-800 hover:text-gray-600">Grades</a> 
            <a href="report.php" class="text-gray-800 hover:text-gray-600">Report</a> 
        </div>
    </div>
</nav>

    <!-- Add Student Grades Form -->
    <div class="flex justify-center items-center mt-10 mt-16">
        <div class="w-full max-w-lg">
            <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <h2 class="text-2xl font-bold text-center text-gray-700 mb-4">Add Student Grade</h2>
                <form action="" method="post">
                    <div class="mb-4">
                        <label for="studentID" class="block text-gray-700 text-sm font-bold mb-2">Student</label>
                        <select id="studentID" name="studentID" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            <?php while ($row = $students->fetch_assoc()): ?>
                            <option value="<?php echo $row['StudentID']; ?>"><?php echo $row['Name']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="courseID" class="block text-gray-700 text-sm font-bold mb-2">Course</label>
                        <select id="courseID" name="courseID" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            <?php while ($row = $courses->fetch_assoc()): ?>
                            <option value="<?php echo $row['CourseID']; ?>"><?php echo $row['Title']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="gpa" class="block text-gray-700 text-sm font-bold mb-2">GPA</label>
                        <input type="number" step="0.01" id="gpa" name="gpa" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Enter GPA" required>
                    </div>
                    <div class="mb-4">
                        <label for="feedback" class="block text-gray-700 text-sm font-bold mb-2">Feedback</label>
                        <textarea id="feedback" name="feedback" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Enter feedback" required></textarea>
                    </div>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Add Grade</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Display Student Grades -->
    <div class="flex justify-center items-center mt-10">
        <div class="w-full max-w-5xl">
            <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <h2 class="text-2xl font-bold text-center text-gray-700 mb-4">Manage Student Grades</h2>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">GPA</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Feedback</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Graded</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['GradeID']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['StudentName']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['GPA']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['Feedback']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['DateGraded']; ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>
