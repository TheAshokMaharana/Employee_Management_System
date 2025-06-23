<?php
session_start();
include("../db.php");

if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch all employees
$sql = $conn->prepare("
    SELECT e.id, e.name, e.email, e.salary, d.name AS department_name, e.profile_pic 
    FROM employees e
    JOIN departments d ON e.department_id = d.id
");
$sql->execute();
$result = $sql->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Employees</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile-img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <a href="index.php" class="navbar-brand">← Dashboard</a>
        <a href="../auth/logout.php" class="btn btn-outline-light">Logout</a>
    </div>
</nav>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>📋 All Employees</h2>
        <div>
            <a href="excel.php" class="btn btn-success me-2">⬇️ Export Excel</a>
            <a href="pdf.php" class="btn btn-danger me-2">⬇️ Export PDF</a>
            <a href="add_emp.php" class="btn btn-primary">➕ Add New</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Department</th>
                    <th>Salary (₹)</th>
                    <th style="width: 140px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><img src="../profile_img/<?= htmlspecialchars($row['profile_pic']) ?>" class="profile-img"></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['department_name']) ?></td>
                        <td><?= number_format($row['salary'], 2) ?></td>
                        <td>
                            <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="delete.php?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this employee?')">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">No employees found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<footer class="text-center mt-5 py-3 bg-light border-top">
    <small>&copy; <?= date("Y") ?> Employee Management System</small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
