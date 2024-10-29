<?php
require_once('tcpdf/tcpdf.php'); // Make sure to adjust the path to your TCPDF installation

include 'db_connect.php'; // Include database connection

// Check if the report type is set (monthly or annually)
$reportType = isset($_GET['type']) ? $_GET['type'] : '';

if ($reportType == 'monthly') {
    // Monthly report query
    $reportQuery = "
    SELECT MONTH(payment_date) AS month, SUM(amount) AS total_amount
    FROM Payments
    WHERE YEAR(payment_date) = YEAR(CURDATE())
    GROUP BY month";
} elseif ($reportType == 'annually') {
    // Annual report query
    $reportQuery = "
    SELECT YEAR(payment_date) AS year, SUM(amount) AS total_amount
    FROM Payments
    GROUP BY year";
} else {
    echo "Invalid report type.";
    exit;
}

$reportResult = $conn->query($reportQuery);

// Create a new PDF document
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('Bill Report');
$pdf->SetSubject('Bill Report');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// Set default header and footer
$pdf->setHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Bill Report', "Generated on: " . date('Y-m-d'));
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', 10));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', 8));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(15, 30, 15);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->AddPage();

// Add report title
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, ucfirst($reportType) . ' Report', 0, 1, 'C');

// Add table for report data
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(40, 10, ucfirst($reportType) == 'Monthly' ? 'Month' : 'Year', 1);
$pdf->Cell(40, 10, 'Total Amount', 1);

while ($row = $reportResult->fetch_assoc()) {
    $pdf->Cell(40, 10, ucfirst($reportType) == 'Monthly' ? $row['month'] : $row['year'], 1);
    $pdf->Cell(40, 10, $row['total_amount'], 1);
    $pdf->Ln();
}

// Close and output PDF document
$pdf->Output('bill_report.pdf', 'I');

$conn->close(); // Close the database connection
?>
