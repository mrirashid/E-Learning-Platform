<?php
include 'db.php';

if (isset($_GET['id'])) {
    $assessmentID = $_GET['id'];

    $sql = $conn->prepare("DELETE FROM assessments WHERE AssessmentID=?");
    $sql->bind_param("i", $assessmentID);

    if ($sql->execute()) {
        header("Location: manage_assessments.php");
    } else {
        echo "Error: " . $sql->error;
    }
    $sql->close();
} else {
    echo "No ID provided.";
}
?>
