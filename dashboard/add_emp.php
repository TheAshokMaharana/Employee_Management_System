<?php

    include ('../db.php');    

    $sql = $conn->query('SELECT * from departments');

    $manager_id = $_SESSION['id'];

    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $department = $_POST['department'];
        $email = $_POST['email'];
        $salary = $_POST['salary'];
        $file = $_FILES['file']['name'];
        

        $fullname = $fname.' '.$lname; 

        $flag = false;

        if (empty($fname) || empty($lname)) {
            echo "<script>alert('First Name or last Name is Empty')</script>";
        } elseif (empty($department)) {
            echo "<script>alert('Department is not selected')</script>";
        } elseif (empty($file)) {
            echo "<script>alert('Select Profile Pic')</script>";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('Email formate is Not Valid')</script>";
        } else {  

            if (move_uploaded_file($_FILES["file"]["tmp_name"], "../profile_img/$file")) {
                $file_name = $file;
            } else {
                $file_name = "default.png";
            }

            //echo "$fullname | $email | $salary | $department | $manager_id | $file_name";
            
            $sql = $conn->prepare("INSERT into employees (name,email,salary,department_id,manager_id,profile_pic) values (?,?,?,?,?,?)");

            $sql->bind_param("ssdiis", $fullname,$email,$salary,$department,$manager_id,$file_name);

            if ($sql->execute()) {
                header("Location:employee_mng.php");
            } else {
                header("Location:../auth/login.php");
            }
            
            

        }
    } 

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Employee</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .form-container {
      max-width: 600px;
      margin: 40px auto;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">← Back</a>
    <a class="btn btn-outline-light" href="#">Logout</a>
  </div>
</nav>

<div class="container form-container">
  <h2 class="mb-4 text-center">➕ Add New Employee</h2>

  <form action="" method="POST" enctype="multipart/form-data">
    <div class="row mb-3">
      <div class="col">
        <label for="firstName" class="form-label">First Name *</label>
        <input type="text" class="form-control" id="" name="fname" placeholder="Enter first name">
      </div>
      <div class="col">
        <label for="lastName" class="form-label">Last Name *</label>
        <input type="text" class="form-control" id="" placeholder="Enter last name" name="lname">
      </div>
    </div>

    <div class="mb-3">
      <label for="email" class="form-label">Email Address *</label>
      <input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
    </div>

    <div class="mb-3">
      <label for="salary" class="form-label">Salary (₹) *</label>
      <input type="number" class="form-control" id="salary" placeholder="Enter salary" name="salary">
    </div>

    <div class="mb-3">
      <label for="department" class="form-label">Department *</label>
      <select class="form-select" id="department" name="department">
        <option selected disabled>Select Department</option>
         <?php while($row = $sql->fetch_assoc()){ ?>
                <option value="<?php echo $row['id'];?>"><?php echo $row['name'];?></option>
         <?php } ?>
      </select>
    </div>

    <div class="mb-3">
      <label for="profilePic" class="form-label">Profile Picture</label>
      <input class="form-control" type="file" id="profilePic" accept="image/*" name="file">
    </div>

    <button type="submit" class="btn btn-success w-100">Add Employee</button>
  </form>
</div>

<footer class="text-center mt-5 py-3 bg-light border-top">
  <small>&copy; 2025 Employee Management System</small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
