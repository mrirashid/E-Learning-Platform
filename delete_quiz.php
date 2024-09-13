<?php
include 'db.php';

if (isset($_GET['id'])) {
    $quizID = $_GET['id'];

    // First, delete the related records from quizresults
    $sqlDeleteResults = "DELETE FROM quizresults WHERE QuizID = $quizID";
    $conn->query($sqlDeleteResults);

    // Then delete the quiz itself
    $sqlDeleteQuiz = "DELETE FROM Quizzes WHERE QuizID = $quizID";

    if ($conn->query($sqlDeleteQuiz) === TRUE) {
        echo "Quiz deleted successfully";
    } else {
        echo "Error: " . $sqlDeleteQuiz . "<br>" . $conn->error;
    }
} else {
    echo "No quiz ID provided.";
}

header("Location: manage_quizzes.php"); // Redirect back to the manage quizzes page
?>
