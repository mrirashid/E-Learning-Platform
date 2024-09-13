<?php
// Include the database connection
include 'db.php';

// Get the assignment ID from the POST data
$assignment_id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($assignment_id > 0) {
    // Prepare the SQL statement to delete the assignment
    $stmt = $conn->prepare("DELETE FROM assignments WHERE AssignmentID = ?");
    $stmt->bind_param("i", $assignment_id);

    if ($stmt->execute()) {
        echo "Success";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid assignment ID";
}

$conn->close();
?>
