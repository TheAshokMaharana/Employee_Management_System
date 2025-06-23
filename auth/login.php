
<?php
    include ('../db.php');    

    $sql = $conn->query('SELECT id,name from departments');

     if ($_SERVER['REQUEST_METHOD'] === "POST") {
        
        $department = $_POST['department'];
        $email = $_POST['email'];
        $password = $_POST['pass'];
        $file = $_FILES['file'];
        
        $flag = false;

        if (empty($department)) {
            echo "<script>alert('Department is not selected')</script>";
        } else if (empty($password)) {
            echo "<script>alert('Password or Confirm Password is Empty')</script>";
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('Email formate is Not Valid')</script>";
        } else {

            $sql = $conn ->prepare("SELECT id,password from managers where email = ? and department_id = ?");
            $sql->bind_param("si",$email,$department);
            $sql->execute();
            $sql->store_result();
            $sql->bind_result($id,$pass);

            if ($sql->fetch() && password_verify($password, $pass) ) {
                $_SESSION['id'] = $id;
                header("Location:../dashboard/index.php");
            } else {
                header("Location:login.php");
            }

        }
        
     }


?>
<!doctype html>
<html lang="en">
    <head>
        <title>Login</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />

        <!-- Bootstrap CSS v5.2.1 -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous"
        />
    </head>

    <body>
        <header>
            <div
                class="container"
            >
                <center>
                    <strong>
                        <h1 style="padding: 30px;">Registration Form</h1>
                    </strong>
                </center>
            </div>
        </header>
        <main>
            <div
                class="container"
            >
                <div
                    class="row justify-content-center align-items-center g-2"
                >
                    <div class="col">
                        <center>
                            <form action="" method="POST" enctype="multipart/form-data">
                                <div class="form-floating mb-3">
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="email"
                                        id="formId1"
                                        placeholder=""
                                    />
                                    <label for="formId1">Email</label>
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">Department</label>
                                    <select
                                        class="form-select form-select-lg"
                                        name="department"
                                        id=""
                                    >
                                        <option value="" selected>Select one</option>
                                       <?php while($row = $sql->fetch_assoc()){ ?>
                                            <option value="<?php echo $row['id'];?>"><?php echo $row['name'];?></option>
                                       <?php } ?>
                                    </select>
                                </div>
                                
                                <div class="form-floating mb-3">
                                    <input
                                        type="password"
                                        class="form-control"
                                        name="pass"
                                        id="formId1"
                                        placeholder=""
                                    />
                                    <label for="formId1">Password</label>
                                </div>
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                >
                                    Submit
                                </button>
                                <a href="registration.php">Registration</a>
                                
                            </form>
                        </center>
                    </div>
                </div>
                
            </div>
            
        </main>
        <footer>
            <!-- place footer here -->
        </footer>
        <!-- Bootstrap JavaScript Libraries -->
        <script
            src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"
        ></script>

        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
            integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
            crossorigin="anonymous"
        ></script>
    </body>
</html>
