<?php
include("../db.php");
require "../vendor/autoload.php";

// Fetch all employees with department and manager info
$sql = $conn->prepare("
    SELECT 
        e.name AS employee_name, 
        e.email AS employee_email, 
        e.salary, 
        d.name AS department_name, 
        m.name AS manager_name
    FROM employees e
    LEFT JOIN departments d ON e.department_id = d.id
    LEFT JOIN managers m ON e.manager_id = m.id
");
$sql->execute();
$result = $sql->get_result();

// Create PDF
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor("Employee Management System");
$pdf->SetTitle("Employee Report");

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

$pdf->SetMargins(15, 15, 15, true);
$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

$pdf->AddPage();
$pdf->SetFont("times", "B", 20);
$pdf->Cell(0, 10, "Employee Report", 0, 1, "C");

$pdf->Ln(5); // Space

$pdf->SetFont("times", "B", 12);

// Table Header
$html = '<table border="1" cellpadding="5">
    <thead>
        <tr style="background-color:#f2f2f2;">
            <th><b>Employee Name</b></th>
            <th><b>Email</b></th>
            <th><b>Department</b></th>
            <th><b>Manager</b></th>
            <th><b>Salary (₹)</b></th>
        </tr>
    </thead>
    <tbody>';

// Table Rows
$pdf->SetFont("times", "", 12);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $html .= '<tr>
            <td>' . htmlspecialchars($row['employee_name']) . '</td>
            <td>' . htmlspecialchars($row['employee_email']) . '</td>
            <td>' . htmlspecialchars($row['department_name']) . '</td>
            <td>' . htmlspecialchars($row['manager_name'] ?? 'N/A') . '</td>
            <td>' . number_format($row['salary'], 2) . '</td>
        </tr>';
    }
} else {
    $html .= '<tr><td colspan="5" align="center">No employee records found.</td></tr>';
}

$html .= '</tbody></table>';

// Render the table
$pdf->writeHTML($html, true, false, false, false, '');

// Output PDF to browser
$pdf->Output("employee_report.pdf", "I");
?>