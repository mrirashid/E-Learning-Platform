<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'updateAssessment') {
    $assessmentID = $_POST['assessmentID'];
    $studentID = $_POST['studentID'];
    $courseID = $_POST['courseID'];
    $type = $_POST['type'];
    $maxScore = $_POST['maxScore'];
    $assessmentDate = $_POST['assessmentDate'];
    $title = $_POST['title'];

    $sql = $conn->prepare("UPDATE assessments SET StudentID=?, CourseID=?, Type=?, MaxScore=?, AssessmentDate=?, Title=? WHERE AssessmentID=?");
    $sql->bind_param("ssssssi", $studentID, $courseID, $type, $maxScore, $assessmentDate, $title, $assessmentID);

    if ($sql->execute()) {
        echo json_encode(["status" => "success", "message" => "Assessment updated successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $sql->error]);
    }
    $sql->close();
    exit();
}

// Fetch assessment details to display in the form
$assessmentID = $_GET['id'];
$sql = $conn->prepare("SELECT * FROM assessments WHERE AssessmentID=?");
$sql->bind_param("i", $assessmentID);
$sql->execute();
$result = $sql->get_result();
$assessment = $result->fetch_assoc();
$sql->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Assessment</title>
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
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

    <div class="max-w-lg w-full bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold mb-4">Edit Assessment</h2>
        <form id="editForm">
            <input type="hidden" id="assessmentID" name="assessmentID" value="<?php echo htmlspecialchars($assessment['AssessmentID']); ?>">
            <div class="mb-4">
                <label for="studentID" class="block text-gray-700">Student ID:</label>
                <input type="text" id="studentID" name="studentID" value="<?php echo htmlspecialchars($assessment['StudentID']); ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div class="mb-4">
                <label for="courseID" class="block text-gray-700">Course ID:</label>
                <input type="text" id="courseID" name="courseID" value="<?php echo htmlspecialchars($assessment['CourseID']); ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div class="mb-4">
                <label for="type" class="block text-gray-700">Type:</label>
                <input type="text" id="type" name="type" value="<?php echo htmlspecialchars($assessment['Type']); ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div class="mb-4">
                <label for="maxScore" class="block text-gray-700">Max Score:</label>
                <input type="number" id="maxScore" name="maxScore" value="<?php echo htmlspecialchars($assessment['MaxScore']); ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div class="mb-4">
                <label for="assessmentDate" class="block text-gray-700">Assessment Date:</label>
                <input type="date" id="assessmentDate" name="assessmentDate" value="<?php echo htmlspecialchars($assessment['AssessmentDate']); ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div class="mb-4">
                <label for="title" class="block text-gray-700">Title:</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($assessment['Title']); ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <button type="button" id="updateButton" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update</button>
        </form>
    </div>
    <script>
    document.getElementById('updateButton').onclick = function() {
        var formData = new FormData(document.getElementById('editForm'));

        // Append action to formData
        formData.append('action', 'updateAssessment');

        fetch('edit.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert(data.message);
                window.location.href = 'manage_assessments.php'; // Redirect on success
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    };
    </script>
</body>
</html>
