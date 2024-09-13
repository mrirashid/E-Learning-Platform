<?php
// Include the database connection
include 'db.php';

// Get the assignment ID from the query parameters
$assignment_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($assignment_id > 0) {
    // Fetch the assignment details from the database
    $stmt = $conn->prepare("SELECT AssignmentID, Title, Type, MaxScore, DateAssigned, CourseID FROM assignments WHERE AssignmentID = ?");
    $stmt->bind_param("i", $assignment_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Output the assignment details as JSON
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(['error' => 'Assignment not found']);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'Invalid assignment ID']);
}

$conn->close();
?>
