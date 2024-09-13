<?php
include 'db.php';

// Fetch data for the dynamic table
$sql = "SELECT Students.StudentID, Students.Name AS StudentName, Courses.CourseID, Courses.CourseName, Quizzes.QuizName 
        FROM Students 
        JOIN Enrollments ON Students.StudentID = Enrollments.StudentID
        JOIN Courses ON Enrollments.CourseID = Courses.CourseID
        LEFT JOIN Quizzes ON Courses.CourseID = Quizzes.CourseID";
$result = $conn->query($sql);

$rows = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
}

// Fetch data for the chart
$sqlChart = "SELECT CourseName, COUNT(EnrollmentID) AS studentCount 
             FROM Enrollments 
             JOIN Courses ON Enrollments.CourseID = Courses.CourseID 
             GROUP BY CourseName";
$resultChart = $conn->query($sqlChart);

$courseNames = [];
$studentCounts = [];

if ($resultChart->num_rows > 0) {
    while ($row = $resultChart->fetch_assoc()) {
        $courseNames[] = $row['CourseName'];
        $studentCounts[] = $row['studentCount'];
    }
}

// Fetch data for the assessment report
$sqlAssessment = "SELECT Students.StudentID, Students.Name AS StudentName, 
                         Courses.CourseName, 
                         AVG(QuizResults.Score) AS AverageScore,
                         COUNT(Quizzes.QuizID) AS TotalQuizzes, 
                         COUNT(QuizResults.Score) AS QuizzesCompleted 
                  FROM Students 
                  JOIN Enrollments ON Students.StudentID = Enrollments.StudentID 
                  JOIN Courses ON Enrollments.CourseID = Courses.CourseID 
                  LEFT JOIN Quizzes ON Courses.CourseID = Quizzes.CourseID 
                  LEFT JOIN QuizResults ON Quizzes.QuizID = QuizResults.QuizID AND Students.StudentID = QuizResults.StudentID
                  GROUP BY Students.StudentID, Courses.CourseID";
$resultAssessment = $conn->query($sqlAssessment);

$assessmentRows = [];

if ($resultAssessment->num_rows > 0) {
    while ($row = $resultAssessment->fetch_assoc()) {
        $assessmentRows[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Enrollment Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" href="fav_icon.png" type="image/png"> 
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
<body>
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

<!-- hero Section -->
<section class="py-12 bg-gray-100 mt-16">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12">
            <h1 class="text-3xl font-bold text-gray-800"> Comprehensive Course Enrollment Report</h1>
            <p class="mt-3 text-gray-600">Explore the detailed insights into student enrollments, course popularity, and assessment performance. Our interactive report provides a clear overview of key metrics and trends to help you make informed decisions</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Card 1 -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <div class="flex justify-center mb-4">
                    <span class="bg-blue-500 text-white p-4 rounded-full">
                        <!-- SVG icon -->
                    </span>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 text-center">Enrollment Trends</h3>
                <p class="mt-3 text-gray-600 text-center">View trends in student enrollments across courses. Identify popular courses and adjust offerings based on enrollment data.</p>
            </div>
            <!-- Card 2 -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <div class="flex justify-center mb-4">
                    <span class="bg-green-500 text-white p-4 rounded-full">
                        <!-- SVG icon -->
                    </span>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 text-center">Student Performance</h3>
                <p class="mt-4 text-gray-600 text-center">Access key metrics on student performance, including average scores and quiz completion rates, to gauge course effectiveness.</p>
            </div>
            <!-- Card 3 -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <div class="flex justify-center mb-4">
                    <span class="bg-red-500 text-white p-4 rounded-full">
                        <!-- SVG icon -->
                    </span>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 text-center">Assessment Summary</h3>
                <p class="mt-4 text-gray-600 text-center">Get an overview of assessment results and grading, including average grades and completion rates, to ensure fair and effective evaluations.</p>
            </div>
        </div>
    </div>
</section>

<!-- Dynamic Table Section -->
<div class="container mt-5">
    <h2>Course and Student Details</h2>
    <table class="table-auto w-full border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-100">
                <th class="border border-gray-300 px-4 py-2">Student ID</th>
                <th class="border border-gray-300 px-4 py-2">Student Name</th>
                <th class="border border-gray-300 px-4 py-2">Course ID</th>
                <th class="border border-gray-300 px-4 py-2">Course Name</th>
                <th class="border border-gray-300 px-4 py-2">Quiz Name</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td class="border border-gray-300 px-4 py-2"><?php echo $row['StudentID']; ?></td>
                    <td class="border border-gray-300 px-4 py-2"><?php echo $row['StudentName']; ?></td>
                    <td class="border border-gray-300 px-4 py-2"><?php echo $row['CourseID']; ?></td>
                    <td class="border border-gray-300 px-4 py-2"><?php echo $row['CourseName']; ?></td>
                    <td class="border border-gray-300 px-4 py-2"><?php echo $row['QuizName']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class=my-2>
        <a href="download_report.php?type=assessment" class="btn btn-primary">Download Assessment Report</a>
    </div>
</div>

<!-- Chart Section -->
<div class="container mt-5">
    <h2>Course Enrollment Chart</h2>
    <canvas id="enrollmentChart" width="400" height="200"></canvas>
    <script>
        var ctx = document.getElementById('enrollmentChart').getContext('2d');
        var enrollmentChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($courseNames); ?>,
                datasets: [{
                    label: 'Number of Students',
                    data: <?php echo json_encode($studentCounts); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</div>

<!-- Download Buttons Section -->
<div class="container mt-5">
    <div class="mb-4">
        <a href="download_report.php?type=course_student_details" class="btn btn-primary">Download Course and Student Details Report</a>
    </div>
    
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
