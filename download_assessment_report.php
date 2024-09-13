<?php
// Include the FPDF library
require('fpdf186/fpdf.php'); // Make sure this path matches where you placed the fpdf.php file

include 'db.php';

// Fetch data for the assessment report
$sql = "SELECT Students.StudentID, Students.Name AS StudentName, 
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

$result = $conn->query($sql);

// Initialize PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Assessment Report', 0, 1, 'C');
$pdf->Ln(10);

// Table Header
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(30, 10, 'Student ID', 1);
$pdf->Cell(50, 10, 'Student Name', 1);
$pdf->Cell(50, 10, 'Course Name', 1);
$pdf->Cell(30, 10, 'Average Score', 1);
$pdf->Cell(30, 10, 'Quizzes Completed', 1);
$pdf->Ln();

// Table Data
$pdf->SetFont('Arial', '', 10);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pdf->Cell(30, 10, $row['StudentID'], 1);
        $pdf->Cell(50, 10, $row['StudentName'], 1);
        $pdf->Cell(50, 10, $row['CourseName'], 1);
        $pdf->Cell(30, 10, number_format($row['AverageScore'], 2), 1);
        $pdf->Cell(30, 10, $row['QuizzesCompleted'], 1);
        $pdf->Ln();
    }
}

$pdf->Output('D', 'Assessment_Report.pdf'); // Outputs the PDF file for download
?>
