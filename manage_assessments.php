<?php
include 'db.php';

// Handle form submission to add a new assessment
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'addAssessment') {
    $studentID = $_POST['studentID'];
    $courseID = $_POST['courseID'];
    $type = $_POST['type'];
    $maxScore = $_POST['maxScore'];
    $assessmentDate = $_POST['assessmentDate'];
    $title = $_POST['title'];

    $sql = $conn->prepare("INSERT INTO assessments (StudentID, CourseID, Type, MaxScore, AssessmentDate, Title) 
                            VALUES (?, ?, ?, ?, ?, ?)");
    $sql->bind_param("ssssss", $studentID, $courseID, $type, $maxScore, $assessmentDate, $title);

    if ($sql->execute()) {
        echo json_encode(["status" => "success", "message" => "New assessment created successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $sql->error]);
    }
    $sql->close();
    exit();
}

// Fetch existing assessments
$sql = "SELECT * FROM assessments";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Assessments</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="icon" href="fav_icon.png" type="image/png"> 
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    // AJAX for adding assessments without page reload
    function addAssessment() {
        const studentID = $('#studentID').val();
        const courseID = $('#courseID').val();
        const type = $('#type').val();
        const maxScore = $('#maxScore').val();
        const assessmentDate = $('#assessmentDate').val();
        const title = $('#title').val();

        // Simple validation
        if (!studentID || !courseID || !type || !maxScore || !assessmentDate || !title) {
            alert("All fields are required!");
            return;
        }

        $.ajax({
            url: 'manage_assessments.php',
            type: 'POST',
            data: {
                action: 'addAssessment',
                studentID: studentID,
                courseID: courseID,
                type: type,
                maxScore: maxScore,
                assessmentDate: assessmentDate,
                title: title
            },
            success: function(response) {
                const data = JSON.parse(response);
                if (data.status === 'success') {
                    alert(data.message);
                    // Append the new record to the table without reloading
                    $('#assessmentsTable').append(
                        `<tr>
                            <td class='border px-4 py-2'>${studentID}</td>
                            <td class='border px-4 py-2'>${courseID}</td>
                            <td class='border px-4 py-2'>${type}</td>
                            <td class='border px-4 py-2'>${maxScore}</td>
                            <td class='border px-4 py-2'>${assessmentDate}</td>
                            <td class='border px-4 py-2'>${title}</td>
                            <td class='border px-4 py-2'>
                                <a href='edit.php?id=${studentID}' class='bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-3 rounded mr-2'>Edit</a>
                                <a href='delete.php?id=${studentID}' class='bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded' onclick='return confirm("Are you sure?")'>Delete</a>
                            </td>
                        </tr>`
                    );
                    // Clear the form
                    $('#assessmentForm')[0].reset();
                } else {
                    alert(data.message);
                }
            }
        });
    }

    $(document).ready(function() {
        // Handle form submission on button click
        $('#assignButton').click(function() {
            addAssessment();
        });
    });
</script>

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

    <div class="flex justify-center mt-16">
        <div class="form-container">
            <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <h2 class="text-2xl font-bold text-center text-gray-700 mb-4">Manage Assessments</h2>
                <form id="assessmentForm">
    <div class="mb-4">
        <label for="studentID" class="block text-gray-700 text-sm font-bold mb-2">Student ID</label>
        <input type="text" id="studentID" name="studentID" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Enter student ID" required>
    </div>
    <div class="mb-4">
        <label for="courseID" class="block text-gray-700 text-sm font-bold mb-2">Course ID</label>
        <input type="text" id="courseID" name="courseID" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Enter course ID" required>
    </div>
    <div class="mb-4">
        <label for="type" class="block text-gray-700 text-sm font-bold mb-2">Type</label>
        <input type="text" id="type" name="type" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Enter type" required>
    </div>
    <div class="mb-4">
        <label for="maxScore" class="block text-gray-700 text-sm font-bold mb-2">Max Score</label>
        <input type="number" id="maxScore" name="maxScore" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Enter max score" required>
    </div>
    <div class="mb-4">
        <label for="assessmentDate" class="block text-gray-700 text-sm font-bold mb-2">Assessment Date</label>
        <input type="date" id="assessmentDate" name="assessmentDate" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
    </div>
    <div class="mb-4">
        <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title</label>
        <input type="text" id="title" name="title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Enter title" required>
    </div>
    <button type="button" id="assignButton" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Assign</button>
</form>

                <p id="successMessage" class="mt-4"></p>

                <!-- Existing Assessments section starts here -->
                <div class="container mx-auto my-8">
                    <h3 class="text-xl font-bold text-center text-gray-700 mb-6">Existing Assessments</h3>
                    <table class="table-auto w-full text-center bg-white shadow-md rounded-lg">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="px-4 py-2">Student ID</th>
                                <th class="px-4 py-2">Student Name</th>
                                <th class="px-4 py-2">Course ID</th>
                                <th class="px-4 py-2">Type</th>
                                <th class="px-4 py-2">Score</th>
                                <th class="px-4 py-2">Assessment Date</th>
                                <th class="px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="assessmentsTable">
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td class='border px-4 py-2'>" . htmlspecialchars($row["StudentID"]) . "</td>";
            echo "<td class='border px-4 py-2'>" . htmlspecialchars($row["CourseID"]) . "</td>";
            echo "<td class='border px-4 py-2'>" . htmlspecialchars($row["Type"]) . "</td>";
            echo "<td class='border px-4 py-2'>" . htmlspecialchars($row["MaxScore"]) . "</td>";
            echo "<td class='border px-4 py-2'>" . htmlspecialchars($row["AssessmentDate"]) . "</td>";
            echo "<td class='border px-4 py-2'>" . htmlspecialchars($row["Title"]) . "</td>";
            echo "<td class='border px-4 py-2'>
                    <a href='edit.php?id=" . htmlspecialchars($row["AssessmentID"]) . "' class='bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-3 rounded mr-2'>Edit</a>
                    <a href='delete.php?id=" . htmlspecialchars($row["AssessmentID"]) . "' class='bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                  </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='7' class='border px-4 py-2'>No assessments found</td></tr>";
    }
    ?>
</tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
