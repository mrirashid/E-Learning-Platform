<?php
include 'db.php';

$operation = isset($_GET['operation']) ? $_GET['operation'] : '';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Handle form submission for adding instructor
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $operation == '') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $department = $_POST['department'];
    
    $sql = "INSERT INTO instructors (Name, Email, Department) VALUES ('$name', '$email', '$department')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<p class='text-green-500 text-center mt-4'>Instructor added successfully!</p>";
    } else {
        echo "<p class='text-red-500 text-center mt-4'>Error: " . $conn->error . "</p>";
    }
}

// Handle update form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $operation == 'update') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $department = $_POST['department'];
    
    $sql = "UPDATE instructors SET Name='$name', Email='$email', Department='$department' WHERE InstructorID=$id";
    
    if ($conn->query($sql) === TRUE) {
        echo "<p class='text-green-500 text-center mt-4'>Instructor updated successfully!</p>";
    } else {
        echo "<p class='text-red-500 text-center mt-4'>Error: " . $conn->error . "</p>";
    }
}

// Handle delete operation
if ($operation == 'delete') {
    $sql = "DELETE FROM instructors WHERE InstructorID=$id";
    
    if ($conn->query($sql) === TRUE) {
        echo "<p class='text-green-500 text-center mt-4'>Instructor deleted successfully!</p>";
    } else {
        echo "<p class='text-red-500 text-center mt-4'>Error: " . $conn->error . "</p>";
    }
}

// Fetch existing instructors
$sql = "SELECT InstructorID, Name, Email, Department FROM instructors";
$result = $conn->query($sql);

// Fetch instructor for editing
$editInstructor = [];
if ($operation == 'edit' && $id > 0) {
    $sql = "SELECT InstructorID, Name, Email, Department FROM instructors WHERE InstructorID=$id";
    $editResult = $conn->query($sql);
    if ($editResult->num_rows > 0) {
        $editInstructor = $editResult->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- (head content remains the same) -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructors</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    
    <!-- Favicon -->
    <link rel="icon" href="fav_icon.png" type="image/png"> 
    <style>
        /* (styles remain the same) */
        @import url('https://fonts.googleapis.com/css2?family=Saira:wght@400;500;600&family=Ubuntu&display=swap');

        body {
            font-family: 'Saira', sans-serif;
        }

        h1, h2, h3 {
            font-family: 'Ubuntu', sans-serif;
        }

        textarea {
            min-height: 150px;
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- navbar.php -->
    <nav class="bg-white shadow-md fixed top-0 left-0 w-full z-50">
        <!-- (navbar content remains the same) -->
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

    <div class="flex justify-center items-center min-h-screen pt-24">
        <div class="w-full max-w-5xl">
            <div class="bg-white shadow-md rounded-lg px-8 py-6 mb-4">
                <h2 class="text-2xl font-bold text-center text-gray-700 mb-6">
                    <?php echo $operation == 'edit' ? 'Edit Instructor' : 'Add Instructor'; ?>
                </h2>
                <form action="instructor.php<?php echo $operation == 'edit' ? '?operation=update' : ''; ?>" method="post" class="space-y-6">
                    <?php if ($operation == 'edit') : ?>
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($editInstructor['InstructorID']); ?>">
                    <?php endif; ?>
                    <div>
                        <label class="block text-gray-700 text-lg font-medium mb-2">Name</label>
                        <input type="text" name="name" value="<?php echo htmlspecialchars($operation == 'edit' ? $editInstructor['Name'] : ''); ?>" class="w-full border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 p-2" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-lg font-medium mb-2">Email</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($operation == 'edit' ? $editInstructor['Email'] : ''); ?>" class="w-full border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 p-2" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-lg font-medium mb-2">Department</label>
                        <input type="text" name="department" value="<?php echo htmlspecialchars($operation == 'edit' ? $editInstructor['Department'] : ''); ?>" class="w-full border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 p-2" required>
                    </div>
                    <div>
                        <button type="submit" class="bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-600">
                            <?php echo $operation == 'edit' ? 'Update Instructor' : 'Add Instructor'; ?>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Display existing instructors in a table -->
            <div class="bg-white shadow-md rounded-lg px-8 py-6">
                <h3 class="text-xl font-bold mb-4">Existing Instructors</h3>
                <?php if ($result->num_rows > 0): ?>
                    <div class="overflow-x-auto">
                        <table class="table-auto w-full">
                            <thead>
                                <tr class="bg-gray-200">
                                    <th class="px-4 py-2 text-left">Name</th>
                                    <th class="px-4 py-2 text-left">Email</th>
                                    <th class="px-4 py-2 text-left">Department</th>
                                    <th class="px-4 py-2 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr class="border-b">
                                        <td class="px-4 py-2"><?php echo htmlspecialchars($row["Name"]); ?></td>
                                        <td class="px-4 py-2"><?php echo htmlspecialchars($row["Email"]); ?></td>
                                        <td class="px-4 py-2"><?php echo htmlspecialchars($row["Department"]); ?></td>
                                        <td class="px-4 py-2">
                                            <a href="instructor.php?operation=edit&id=<?php echo $row["InstructorID"]; ?>" class="text-blue-500 hover:underline">Edit</a> |
                                            <a href="instructor.php?operation=delete&id=<?php echo $row["InstructorID"]; ?>" class="text-red-500 hover:underline" onclick="return confirm('Are you sure you want to delete this instructor?');">Delete</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-gray-600">No instructors found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
