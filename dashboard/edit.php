<?php
include('../db.php');

if (!isset($_GET['id'])) {
    header("Location: employee_mng.php");
    exit;
}

$employee_id = $_GET['id'];

// Get employee info
$stmt = $conn->prepare("SELECT * FROM employees WHERE id = ?");
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();

// Get department list
$departments = $conn->query("SELECT * FROM departments");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $department = $_POST['department'];
    $email = $_POST['email'];
    $salary = $_POST['salary'];
    $file = $_FILES['file']['name'];

    $fullname = $fname . ' ' . $lname;

    // Handle profile picture
    if (!empty($file)) {
        move_uploaded_file($_FILES["file"]["tmp_name"], "../profile_img/$file");
        $profile_pic = $file;
    } else {
        $profile_pic = $employee['profile_pic']; // keep old
    }

    $update = $conn->prepare("UPDATE employees SET name=?, email=?, salary=?, department_id=?, profile_pic=? WHERE id=?");
    $update->bind_param("ssdisi", $fullname, $email, $salary, $department, $profile_pic, $employee_id);
    
    if ($update->execute()) {
        header("Location: employee_mng.php");
        exit;
    } else {
        echo "<script>alert('Update failed.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Employee</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .form-container { max-width: 600px; margin: 40px auto; }
  </style>
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="employee_mng.php">← Back</a>
    <a class="btn btn-outline-light" href="#">Logout</a>
  </div>
</nav>

<div class="container form-container">
  <h2 class="mb-4 text-center">✏️ Edit Employee</h2>
  <form action="" method="POST" enctype="multipart/form-data">
    <?php
    $name_parts = explode(' ', $employee['name'], 2);
    $fname = $name_parts[0];
    $lname = $name_parts[1] ?? '';
    ?>
    <div class="row mb-3">
      <div class="col">
        <label class="form-label">First Name *</label>
        <input type="text" class="form-control" name="fname" value="<?= htmlspecialchars($fname) ?>">
      </div>
      <div class="col">
        <label class="form-label">Last Name *</label>
        <input type="text" class="form-control" name="lname" value="<?= htmlspecialchars($lname) ?>">
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label">Email *</label>
      <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($employee['email']) ?>">
    </div>

    <div class="mb-3">
      <label class="form-label">Salary (₹) *</label>
      <input type="number" class="form-control" name="salary" value="<?= htmlspecialchars($employee['salary']) ?>">
    </div>

    <div class="mb-3">
      <label class="form-label">Department *</label>
      <select class="form-select" name="department">
        <option disabled>Select Department</option>
        <?php while($dept = $departments->fetch_assoc()): ?>
          <option value="<?= $dept['id'] ?>" <?= $employee['department_id'] == $dept['id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($dept['name']) ?>
          </option>
        <?php endwhile; ?>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Profile Picture</label>
      <input type="file" class="form-control" name="file">
      <small class="text-muted">Current: <?= $employee['profile_pic'] ?></small>
    </div>

    <button type="submit" class="btn btn-primary w-100">Update Employee</button>
  </form>
</div>

</body>
</html>
