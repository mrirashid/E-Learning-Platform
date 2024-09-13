<?php
include 'db.php';

// Handle form submission to add a new quiz
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $quizName = $_POST['quizName'];
    $courseID = $_POST['courseID'];
    $dueDate = $_POST['dueDate'];

    $sql = "INSERT INTO Quizzes (QuizName, CourseID, DueDate) 
            VALUES ('$quizName', '$courseID', '$dueDate')";

    if ($conn->query($sql) === TRUE) {
        echo "New quiz created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch existing quizzes
$sql = "SELECT * FROM Quizzes";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Quizzes</title>
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

        /* Center content vertically and horizontally */
        .centered {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Increase form box size */
        .form-box {
            width: 100%;
            max-width: 700px;
        }
    </style>
</head>
<body class="bg-gray-100">

    <!-- Navbar -->
    <nav class="bg-white shadow-md fixed top-0 left-0 w-full z-50">
        <div class="container mx-auto px-6 py-3 flex justify-between items-center">
            <a href="index.php" class="text-xl font-bold text-gray-800 hover:text-gray-600">E-Learning Platform</a>
            <div class="flex space-x-4">
                <a href="course_create.php" class="text-gray-800 hover:text-gray-600">Create Course</a>
                <a href="content_management.php" class="text-gray-800 hover:text-gray-600">Content Management</a>
                <a href="assignments.php" class="text-gray-800 hover:text-gray-600">Assignments</a>
                <a href="course_enroll.php" class="text-gray-800 hover:text-gray-600">Course Enrollment</a>
                <a href="manage_assessments.php" class="text-gray-800 hover:text-gray-600">Assessments</a>
                <a href="manage_quizzes.php" class="text-gray-800 hover:text-gray-600">Quizzes</a>
                <a href="grades.php" class="text-gray-800 hover:text-gray-600">Grades</a> 
                <a href="report.php" class="text-gray-800 hover:text-gray-600">Report</a> 
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="centered mt-16">
        <div class="form-box bg-white shadow-md rounded px-8 pt-6 pb-8">
            <h2 class="text-3xl font-bold text-center text-gray-700 mb-8">Manage Quizzes</h2>

            <!-- Form to Add Quiz -->
            <form action="manage_quizzes.php" method="post">
                <div class="mb-4">
                    <label for="quizName" class="block text-gray-700 text-sm font-bold mb-2">Quiz Name</label>
                    <input type="text" id="quizName" name="quizName" class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Enter quiz name" required>
                </div>
                <div class="mb-4">
                    <label for="courseID" class="block text-gray-700 text-sm font-bold mb-2">Course ID</label>
                    <input type="number" id="courseID" name="courseID" class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Enter course ID" required>
                </div>
                <div class="mb-4">
                    <label for="dueDate" class="block text-gray-700 text-sm font-bold mb-2">Due Date</label>
                    <input type="date" id="dueDate" name="dueDate" class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded focus:outline-none focus:shadow-outline w-full">Add Quiz</button>
            </form>

            <!-- Table of Existing Quizzes -->
            <h3 class="text-2xl font-bold text-gray-700 mt-8 mb-4 text-center">Existing Quizzes</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['QuizID']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['QuizName']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['CourseID']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['DueDate']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="edit_quiz.php?id=<?php echo $row['QuizID']; ?>" class="text-blue-500 hover:text-blue-700">Edit</a>
                                <a href="delete_quiz.php?id=<?php echo $row['QuizID']; ?>" class="text-red-500 hover:text-red-700 ml-4">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
