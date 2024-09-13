<?php
include 'db.php';

if (isset($_GET['id'])) {
    $quizID = $_GET['id'];

    // Fetch existing quiz details
    $sql = "SELECT * FROM Quizzes WHERE QuizID = $quizID";
    $result = $conn->query($sql);
    $quiz = $result->fetch_assoc();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $quizName = $_POST['quizName'];
        $courseID = $_POST['courseID'];
        $dueDate = $_POST['dueDate'];

        $sql = "UPDATE Quizzes SET QuizName = '$quizName', CourseID = '$courseID', DueDate = '$dueDate' WHERE QuizID = $quizID";

        if ($conn->query($sql) === TRUE) {
            echo "Quiz updated successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
} else {
    echo "No quiz ID provided.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Quiz</title>
    
    <!-- Favicon -->
    <link rel="icon" href="fav_icon.png" type="image/png"> 
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

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
                <h2 class="text-2xl font-bold text-center text-gray-700 mb-4">Edit Quiz</h2>
                <form action="edit_quiz.php?id=<?php echo $quizID; ?>" method="post">
                    <div class="mb-4">
                        <label for="quizName" class="block text-gray-700 text-sm font-bold mb-2">Quiz Name</label>
                        <input type="text" id="quizName" name="quizName" value="<?php echo $quiz['QuizName']; ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div class="mb-4">
                        <label for="courseID" class="block text-gray-700 text-sm font-bold mb-2">Course ID</label>
                        <input type="number" id="courseID" name="courseID" value="<?php echo $quiz['CourseID']; ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div class="mb-4">
                        <label for="dueDate" class="block text-gray-700 text-sm font-bold mb-2">Due Date</label>
                        <input type="date" id="dueDate" name="dueDate" value="<?php echo $quiz['DueDate']; ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Update Quiz</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
