<?php
include 'db.php';

// Function to sanitize input
function sanitize_input($data) {
    return htmlspecialchars(trim($data));
}

// Handle POST requests coming via AJAX
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = sanitize_input($_POST['name'] ?? '');
    $email = sanitize_input($_POST['email'] ?? '');
    $studentID = isset($_POST['studentID']) ? intval($_POST['studentID']) : 0;
    $operation = $_POST['operation'] ?? 'add';

    $response = [];

    // Validate input
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['status'] = 'error';
        $response['message'] = "Invalid email format.";
    } else {
        if ($operation == 'add') {
            $sql = "INSERT INTO Students (Name, Email) VALUES ('$name', '$email')";
            if ($conn->query($sql) === TRUE) {
                $studentID = $conn->insert_id;  // Get the newly created StudentID
                $response['status'] = 'success';
                $response['message'] = "New student registered successfully.";
                $response['studentID'] = $studentID;
                $response['name'] = $name;
                $response['email'] = $email;
            } else {
                $response['status'] = 'error';
                $response['message'] = "Error: " . $conn->error;
            }
        } elseif ($operation == 'update' && $studentID > 0) {
            $sql = "UPDATE Students SET Name='$name', Email='$email' WHERE StudentID=$studentID";
            if ($conn->query($sql) === TRUE) {
                $response['status'] = 'success';
                $response['message'] = "Student record updated successfully.";
                $response['studentID'] = $studentID;
                $response['name'] = $name;
                $response['email'] = $email;
            } else {
                $response['status'] = 'error';
                $response['message'] = "Error: " . $conn->error;
            }
        } elseif ($operation == 'delete' && $studentID > 0) {
            $deleteStudent = "DELETE FROM Students WHERE StudentID=$studentID";
            if ($conn->query($deleteStudent) === TRUE) {
                $response['status'] = 'success';
                $response['message'] = "Student record deleted successfully.";
            } else {
                $response['status'] = 'error';
                $response['message'] = "Error: " . $conn->error;
            }
        }
    }
    echo json_encode($response);
    $conn->close();
    exit;
}

// Fetch all students for display
$sql = "SELECT StudentID, Name, Email FROM Students";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
      <!-- Favicon -->
      <link rel="icon" href="fav_icon.png" type="image/png"> 
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
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
<div class="flex justify-center items-center h-screen pt-20" style="margin-top: 70px;">
    <div class="w-full max-w-md form-container">
        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <h2 class="text-2xl font-bold text-center text-gray-700 mb-4" id="form-title">Student Registration</h2>
            <form id="studentForm">
                <input type="hidden" id="operation" name="operation" value="add">
                <input type="hidden" id="studentID" name="studentID" value="0">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                    <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-6">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                    <input type="email" id="email" name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="flex items-center justify-between">
                    <button type="submit" id="submitButton" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Register
                    </button>
                </div>
            </form>
            <div id="responseMessage" class="text-center mt-4"></div>
        </div>

        <!-- Display existing students -->
        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8">
            <h3 class="text-xl text-center font-bold mb-4">Existing Students</h3>
            <table class="table-auto w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Email</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody id="studentList">
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr id='student-row-" . $row["StudentID"] . "'>";
                            echo "<td class='border px-4 py-2'>" . htmlspecialchars($row["Name"]) . "</td>";
                            echo "<td class='border px-4 py-2'>" . htmlspecialchars($row["Email"]) . "</td>";
                            echo "<td class='border px-4 py-2'>
                                    <div class='flex items-center space-x-2'>
                                        <a href='#' class='text-blue-500 hover:underline editButton' data-id='" . $row["StudentID"] . "' data-name='" . htmlspecialchars($row["Name"]) . "' data-email='" . htmlspecialchars($row["Email"]) . "'>Edit</a>
                                        <a href='#' class='text-red-500 hover:underline deleteButton' data-id='" . $row["StudentID"] . "'>Delete</a>
                                    </div>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3' class='border px-4 py-2 text-center'>No students found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// Handle form submission
document.getElementById('studentForm').addEventListener('submit', function(event) {
    event.preventDefault();  // Prevent form from submitting normally

    let formData = new FormData(this);

    fetch('student_register.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        let responseMessage = document.getElementById('responseMessage');
        if (data.status === 'success') {
            responseMessage.innerHTML = `<p class="text-green-500">${data.message}</p>`;

            // Determine whether to add or update based on the operation
            if (formData.get('operation') === 'add') {
                addStudentToTable(data); // Dynamically add the new student to the table
            } else if (formData.get('operation') === 'update') {
                updateStudentInTable(data); // Update the student record in the table
                // Reset form to registration mode after updating
                document.getElementById('operation').value = 'add';
                document.getElementById('submitButton').innerText = 'Register';
            }

            // Reset the form
            document.getElementById('studentForm').reset();
        } else {
            responseMessage.innerHTML = `<p class="text-red-500">${data.message}</p>`;
        }
    });
});

// Function to add a new student to the table dynamically
function addStudentToTable(data) {
    let newRow = `
        <tr id="student-row-${data.studentID}">
            <td class='border px-4 py-2'>${data.name}</td>
            <td class='border px-4 py-2'>${data.email}</td>
            <td class='border px-4 py-2'>
                <div class='flex items-center space-x-2'>
                    <a href='#' class='text-blue-500 hover:underline editButton' data-id='${data.studentID}' data-name='${data.name}' data-email='${data.email}'>Edit</a>
                    <a href='#' class='text-red-500 hover:underline deleteButton' data-id='${data.studentID}'>Delete</a>
                </div>
            </td>
        </tr>
    `;
    document.getElementById('studentList').insertAdjacentHTML('beforeend', newRow);
    attachEventListeners(); // Attach event listeners to new buttons
}

// Function to update student information in the table dynamically
function updateStudentInTable(data) {
    let row = document.getElementById(`student-row-${data.studentID}`);
    row.querySelector('td:nth-child(1)').innerText = data.name;
    row.querySelector('td:nth-child(2)').innerText = data.email;
}

// Attach event listeners to buttons (Edit, Delete)
function attachEventListeners() {
    document.querySelectorAll('.editButton').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            let studentID = this.getAttribute('data-id');
            let name = this.getAttribute('data-name');
            let email = this.getAttribute('data-email');
            document.getElementById('studentID').value = studentID;
            document.getElementById('name').value = name;
            document.getElementById('email').value = email;
            document.getElementById('operation').value = 'update';
            document.getElementById('submitButton').innerText = 'Update';
        });
    });

    document.querySelectorAll('.deleteButton').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            if (confirm('Are you sure you want to delete this student?')) {
                let studentID = this.getAttribute('data-id');
                let formData = new FormData();
                formData.append('operation', 'delete');
                formData.append('studentID', studentID);

                fetch('student_register.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        document.getElementById(`student-row-${studentID}`).remove(); // Remove the student from the table
                    } else {
                        alert(data.message);
                    }
                });
            }
        });
    });
}

// Attach event listeners on page load
attachEventListeners();

</script>

</body>
</html>
