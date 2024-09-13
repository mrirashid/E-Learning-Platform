<?php
require('fpdf186/fpdf.php');
include 'db.php';

// Fetch data for the course enrollment report
$sql = "SELECT CourseName, COUNT(EnrollmentID) AS studentCount 
        FROM Enrollments 
        JOIN Courses ON Enrollments.CourseID = Courses.CourseID 
        GROUP BY CourseName";
$result = $conn->query($sql);

// Initialize PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Course Enrollment Report', 0, 1, 'C');
$pdf->Ln(10);

// Table Header
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(70, 10, 'Course Name', 1);
$pdf->Cell(50, 10, 'Number of Students', 1);
$pdf->Ln();

// Table Data
$pdf->SetFont('Arial', '', 10);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pdf->Cell(70, 10, $row['CourseName'], 1);
        $pdf->Cell(50, 10, $row['studentCount'], 1);
        $pdf->Ln();
    }
}

$pdf->Output('D', 'Course_Enrollment_Report.pdf');
?>
