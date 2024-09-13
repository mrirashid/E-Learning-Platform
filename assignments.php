<?php
// Include the database connection
include 'db.php';

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch courses from the database
$courses_query = "SELECT CourseID, Title FROM courses";
$courses_result = $conn->query($courses_query);

if (!$courses_result) {
    die("Query failed: " . $conn->error);
}

// Handle form submission for assigning an assessment to a course
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form data
    $course_id = $conn->real_escape_string($_POST['course']);
    $title = $conn->real_escape_string($_POST['title']);
    $type = $conn->real_escape_string($_POST['type']);
    $max_score = $conn->real_escape_string($_POST['max_score']);
    $date_assigned = $conn->real_escape_string($_POST['date_assigned']);

    // Check for duplicate entry before inserting
    $check_query = $conn->prepare("SELECT * FROM assignments WHERE CourseID = ? AND Type = ?");
    $check_query->bind_param("is", $course_id, $type);
    $check_query->execute();
    $result = $check_query->get_result();

    if ($result->num_rows > 0) {
        // Duplicate entry found
        echo "<p class='text-red-500 text-center mt-4'>Error: This type of assessment already exists for the selected course.</p>";
    } else {
        // Insert into database if no duplicates found
        $stmt = $conn->prepare("INSERT INTO assignments (CourseID, Title, Type, MaxScore, DateAssigned) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issis", $course_id, $title, $type, $max_score, $date_assigned);
        
        if ($stmt->execute()) {
            echo "<p class='text-green-500 text-center mt-4'>Assessment assigned successfully!</p>";
        } else {
            echo "<p class='text-red-500 text-center mt-4'>Error: " . $stmt->error . "</p>";
        }
        $stmt->close();
    }
    $check_query->close();
}

// Close the database connection after the script finishes
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Assessments</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" href="fav_icon.png" type="image/png"> 
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
                <a href="Instructor.php" class="text-gray-800 hover:text-gray-600">Instructor</a>
                <a href="instructor_assignments.php" class="text-gray-800 hover:text-gray-600">Assignments</a>
                <a href="course_enroll.php" class="text-gray-800 hover:text-gray-600">Course Enrollment</a>
                <a href="manage_assessments.php" class="text-gray-800 hover:text-gray-600">Assessments</a>
                <a href="manage_quizzes.php" class="text-gray-800 hover:text-gray-600">Quizzes</a>
                <a href="grades.php" class="text-gray-800 hover:text-gray-600">Grades</a> 
                <a href="report.php" class="text-gray-800 hover:text-gray-600">Report</a> 
            </div>
        </div>
    </nav>

    <div class="flex justify-center items-center h-screen mt-16">
        <div class="w-full max-w-lg">
            <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <h2 class="text-2xl font-bold text-center text-gray-700 mb-4">Assign Assignment</h2>
                <form action="" method="POST">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="course">Select Course</label>
                        <select name="course" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none" required>
                            <option value="">Select a course</option>
                            <?php while($row = $courses_result->fetch_assoc()) { ?>
                                <option value="<?= htmlspecialchars($row['CourseID']) ?>"><?= htmlspecialchars($row['Title']) ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="title">Assignment Title</label>
                        <input type="text" name="title" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 rounded leading-tight focus:outline-none" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="type">Assignment Type</label>
                        <select name="type" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none" required>
                            <option value="Quiz">Quiz</option>
                            <option value="Assignment">Assignment</option>
                            <option value="Exam">Exam</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="max_score">Max Score</label>
                        <input type="number" name="max_score" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 rounded leading-tight focus:outline-none" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="date_assigned">Date Assigned</label>
                        <input type="date" name="date_assigned" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 rounded leading-tight focus:outline-none" required>
                    </div>
                    <div class="flex items-center justify-between">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">Assign</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
