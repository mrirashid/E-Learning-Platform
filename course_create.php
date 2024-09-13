<?php
include 'db.php';

// Function to sanitize input
function sanitize_input($data) {
    return htmlspecialchars(trim($data));
}

// Check if there is a 'delete' operation
if (isset($_GET['operation']) && $_GET['operation'] == 'delete') {
    $courseID = isset($_GET['courseID']) ? intval($_GET['courseID']) : 0;
    if ($courseID > 0) {
        // Perform delete operation
        $sql = "DELETE FROM Courses WHERE CourseID = $courseID";
        if ($conn->query($sql) === TRUE) {
            echo "<p class='text-green-500 text-center mt-4'>Course deleted successfully.</p>";
        } else {
            echo "<p class='text-red-500 text-center mt-4'>Error deleting course: " . $conn->error . "</p>";
        }
    } else {
        echo "<p class='text-red-500 text-center mt-4'>Invalid course ID.</p>";
    }
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $courseName = sanitize_input($_POST['courseName']);
    $description = sanitize_input($_POST['description']);
    $instructorID = intval($_POST['instructorID']);
    $numModules = intval($_POST['numModules']);
    $operation = sanitize_input($_POST['operation']);
    $courseID = isset($_POST['courseID']) ? intval($_POST['courseID']) : 0;

    // Validate input
    if (empty($courseName) || empty($instructorID) || empty($numModules)) {
        echo "<p class='text-red-500 text-center mt-4'>All fields are required except description.</p>";
    } else {
        if ($operation == 'add') {
            $sql = "INSERT INTO Courses (CourseName, Description, InstructorID, NumberOfModules) 
                    VALUES ('$courseName', '$description', $instructorID, $numModules)";
            if ($conn->query($sql) === TRUE) {
                echo "<p class='text-green-500 text-center mt-4'>New course created successfully.</p>";
            } else {
                echo "<p class='text-red-500 text-center mt-4'>Error: " . $conn->error . "</p>";
            }
        } elseif ($operation == 'update' && $courseID > 0) {
            $sql = "UPDATE Courses SET CourseName='$courseName', Description='$description', InstructorID=$instructorID, NumberOfModules=$numModules WHERE CourseID=$courseID";
            if ($conn->query($sql) === TRUE) {
                echo "<p class='text-green-500 text-center mt-4'>Course updated successfully.</p>";
            } else {
                echo "<p class='text-red-500 text-center mt-4'>Error: " . $conn->error . "</p>";
            }
        }
    }
}

// Fetch all courses for display
$sql = "SELECT CourseID, CourseName, Description, InstructorID, NumberOfModules FROM Courses";
$result = $conn->query($sql);

// Check if editing
$operation = $_GET['operation'] ?? '';
$courseID = isset($_GET['courseID']) ? intval($_GET['courseID']) : 0;
$currentCourse = null;

if ($operation == 'edit' && $courseID > 0) {
    $sql = "SELECT CourseName, Description, InstructorID, NumberOfModules FROM Courses WHERE CourseID=$courseID";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $currentCourse = $result->fetch_assoc();
    } else {
        echo "<p class='text-red-500 text-center mt-4'>Course not found.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create or Edit Course</title>
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
        .mt-50 {
            margin-top: 50px;
        }
    </style>
</head>
<body class="bg-gray-100">
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

<!-- Main content starts here -->
<div class="flex justify-center items-center" style="margin-top: 70px;">
    <div class="w-full max-w-lg">
        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <h2 class="text-2xl font-bold text-center text-gray-700 mb-4"><?php echo $operation == 'edit' ? 'Edit Course' : 'Create New Course'; ?></h2>
            <form action="course_create.php" method="post">
                <input type="hidden" name="operation" value="<?php echo $operation == 'edit' ? 'update' : 'add'; ?>">
                <input type="hidden" name="courseID" value="<?php echo $courseID; ?>">
                <div class="mb-4">
                    <label for="courseName" class="block text-gray-700 text-sm font-bold mb-2">Course Name</label>
                    <input type="text" id="courseName" name="courseName" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Enter course name" value="<?php echo htmlspecialchars($currentCourse['CourseName'] ?? ''); ?>" required>
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Course Description</label>
                    <textarea id="description" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Describe the course" rows="4"><?php echo htmlspecialchars($currentCourse['Description'] ?? ''); ?></textarea>
                </div>
                <div class="mb-4">
                    <label for="instructorID" class="block text-gray-700 text-sm font-bold mb-2">Course ID</label>
                    <input type="number" id="instructorID" name="instructorID" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Enter instructor ID" value="<?php echo htmlspecialchars($currentCourse['InstructorID'] ?? ''); ?>" required>
                </div>
                <div class="mb-4">
                    <label for="numModules" class="block text-gray-700 text-sm font-bold mb-2">Number of Modules</label>
                    <input type="number" id="numModules" name="numModules" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Enter number of modules" value="<?php echo htmlspecialchars($currentCourse['NumberOfModules'] ?? ''); ?>" required>
                </div>
                <div class="mb-4">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        <?php echo $operation == 'edit' ? 'Update Course' : 'Create Course'; ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Existing courses section starts here -->
<div class="container mx-auto my-8 ">
    <h3 class="text-xl font-bold text-center text-gray-700 mb-6">Existing Courses</h3>
    <table class="table-auto w-full text-center bg-white shadow-md rounded-lg">
        <thead class="bg-gray-200">
            <tr>
                <th class="px-4 py-2">Course ID</th>
                <th class="px-4 py-2">Course Name</th>
                <th class="px-4 py-2">Instructor ID</th>
                <th class="px-4 py-2">Number of Modules</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td class='border px-4 py-2'>" . $row['CourseID'] . "</td>";
                    echo "<td class='border px-4 py-2'>" . htmlspecialchars($row['CourseName']) . "</td>";
                    echo "<td class='border px-4 py-2'>" . $row['InstructorID'] . "</td>";
                    echo "<td class='border px-4 py-2'>" . $row['NumberOfModules'] . "</td>";
                    echo "<td class='border px-4 py-2'>
                        <a href='course_create.php?operation=edit&courseID=" . $row['CourseID'] . "' class='bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-3 rounded mr-2'>Edit</a>
                        <a href='course_create.php?operation=delete&courseID=" . $row['CourseID'] . "' class='bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                    </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5' class='border px-4 py-2'>No courses available.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
</body>
</html>

<?php
$conn->close();
?>
