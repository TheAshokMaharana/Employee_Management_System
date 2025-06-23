<?php 
include ("../db.php");

if (!isset($_SESSION['id'])) {
    header('Location:../auth/login.php');
    exit;
}

$id = $_SESSION['id'];

$sql = $conn->prepare('
    SELECT m.id, m.name, m.email, m.department_id, m.profile_pic, d.name AS department_name 
    FROM managers m 
    JOIN departments d ON m.department_id = d.id 
    WHERE m.id = ?
');

$sql->bind_param('i', $id);
$sql->execute();
$sql->store_result();
$sql->bind_result($id, $name, $email, $department_id, $profile_pic, $department_name);
$sql->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manager Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .profile-img {
            width: 120px;
            height: 120px;
            border-radius: 60px;
            object-fit: cover;
            border: 2px solid #dee2e6;
        }
        .card-actions a {
            margin-right: 10px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
    <a class="navbar-brand" href="#">Employee Management</a>
    <div class="ms-auto">
        <a href="../auth/logout.php" class="btn btn-outline-light">Logout</a>
    </div>
</nav>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-body text-center">
            <img src="../profile_img/<?= htmlspecialchars($profile_pic) ?>" alt="Profile Picture" class="profile-img mb-3">
            <h3 class="card-title"><?= htmlspecialchars($name) ?></h3>
            <p class="card-text mb-1"><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
            <p class="card-text"><strong>Department:</strong> <?= htmlspecialchars($department_name) ?></p>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-4 offset-md-4 mb-3">
            <div class="card card-actions text-center h-100 shadow">
                <div class="card-body">
                    <h5 class="card-title">Manage Employees</h5>
                    <p class="card-text">Add, edit, or delete employees you manage.</p>
                    <a href="employee_mng.php" class="btn btn-primary">Open</a>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="text-center mt-5 py-3 bg-light border-top">
    <small>&copy; <?= date("Y") ?> Employee Management System</small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
