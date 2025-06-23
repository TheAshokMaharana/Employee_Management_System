<?php

include "db.php";

header("Content-Type: text/csv");
header("Content-Disposition: attachment;filename=employees.csv");

$output = fopen("php://output", "w");

fputcsv($output, ["Employee Name", "Email", "Salary", "Department", "Manager"]);

$sql = "
    SELECT 
        e.name AS employee_name, 
        e.email AS employee_email, 
        e.salary,
        d.name AS department_name, 
        m.name AS manager_name 
    FROM employees e 
    LEFT JOIN departments d ON e.department_id = d.id 
    LEFT JOIN managers m ON e.manager_id = m.id
";

$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    fputcsv($output, [
        $row["employee_name"],
        $row["employee_email"],
        $row["salary"],
        $row["department_name"],
        $row["manager_name"] ?? 'N/A'
    ]);
}

fclose($output);
exit;

?>
