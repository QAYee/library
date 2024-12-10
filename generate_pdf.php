<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/app/config/Directories.php");
require_once(ROOT_DIR . '/app/config/DatabaseConnect.php');
require_once('C:/Users/biboy/Documents/Web Dev/library/fpdf186/fpdf.php');

 // Adjust the path to the FPDF library

$db = new DatabaseConnect();
$conn = $db->connectDB();

// Fetch data from the database
$query = "SELECT t.transaction_id, t.book_id, t.user_id, u.username, t.status, t.borrow_date, t.return_date, t.penalty, b.title 
          FROM transactions t 
          JOIN books b ON t.book_id = b.id 
          JOIN users u ON t.user_id = u.id 
          WHERE t.status IN ('approved', 'borrowed') 
          ORDER BY t.borrow_date DESC";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Initialize FPDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);

// Add table header
$pdf->Cell(20, 10, 'ID', 1);
$pdf->Cell(50, 10, 'Book Title', 1);
$pdf->Cell(20, 10, 'User ID', 1);
$pdf->Cell(30, 10, 'Username', 1);
$pdf->Cell(20, 10, 'Status', 1);
$pdf->Cell(30, 10, 'Borrow Date', 1);
$pdf->Cell(30, 10, 'Return Date', 1);
$pdf->Cell(20, 10, 'Penalty', 1);
$pdf->Ln();

// Add data rows
while ($row = mysqli_fetch_assoc($result)) {
    $pdf->Cell(20, 10, $row['transaction_id'], 1);
    $pdf->Cell(50, 10, $row['title'], 1);
    $pdf->Cell(20, 10, $row['user_id'], 1);
    $pdf->Cell(30, 10, $row['username'], 1);
    $pdf->Cell(20, 10, ucfirst($row['status']), 1);
    $pdf->Cell(30, 10, $row['borrow_date'], 1);
    $pdf->Cell(30, 10, $row['return_date'] ?: 'Not Returned', 1);
    $pdf->Cell(20, 10, $row['penalty'] ?: 'None', 1);
    $pdf->Ln();
}

// Output PDF
$pdf->Output('D', 'Transactions.pdf'); // 'D' triggers download, 'F' saves to a file
?>
