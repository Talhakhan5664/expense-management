<?php
include 'connection.php';


if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password =$_POST['confirm_password'];
    $phone_number=$_POST['phone_number'];

    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!');</script>";
    } else {
        //$hashed_password = password_hash($password, PASSWORD_BCRYPT); // Secure password hashing
       $sql = "INSERT INTO users (name, email, password, phone_number) VALUES ('$name', '$email', '$password', '$phone_number')";
        
        if (mysqli_query($conn, $sql)) {
    echo "<script>alert('Registration successful!');</script>";
    header('Location:index.php');
    exit();
} else {
    echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
    mysqli_close($conn);
}
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<section class="vh-100" style="background-color: #eee;">
  <div class="container h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-lg-12 col-xl-11">
        <div class="card text-black" style="border-radius: 25px;">
          <div class="card-body p-md-5">
            <div class="row justify-content-center">
              <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">
                <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Sign up</p>
                
                <!-- Form with POST method -->
                <form class="mx-1 mx-md-4" method="POST" action=" ">

                  <div class="d-flex flex-row align-items-center mb-4">
                    <div class="form-outline flex-fill mb-0">
                      <input type="text" name="name" class="form-control" placeholder="Enter your Name" required /> Your Name 
                    </div>
                  </div>

                  <div class="d-flex flex-row align-items-center mb-4">
                    <div class="form-outline flex-fill mb-0">
                      <input type="email" name="email" class="form-control" placeholder="Enter your Email" required /> Your Email
                    </div>
                  </div>

                  <div class="d-flex flex-row align-items-center mb-4">
                    <div class="form-outline flex-fill mb-0"> 
                      <input type="number" name="phone_number" class="form-control" placeholder="Enter your Phone" required >Your Phone Number 
                    </div>
                  </div>

                  <div class="d-flex flex-row align-items-center mb-4">
                    <div class="form-outline flex-fill mb-0">
                      <input type="password" name="password" class="form-control" placeholder="Enter your Password" required /> Your Password
                    </div>
                  </div>

                  <div class="d-flex flex-row align-items-center mb-4">
                    <div class="form-outline flex-fill mb-0">
                      <input type="password" name="confirm_password" class="form-control" placeholder="Re-Enter your Password"  required />Repeat your Password
                    </div>
                  </div>

                  <div class="form-check d-flex justify-content-center mb-5">
                    <input class="form-check-input me-2" type="checkbox" required />
                    <label class="form-check-label">
                      I agree to all statements in <a href="#!">Terms of Service</a>
                    </label>
                  </div>

                  <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                    <button type="submit" name="register" class="btn btn-primary btn-lg">Register</button>
                  </div>
                </form>
              </div>

              <div class="col-md-10 col-lg-6 col-xl-7 d-flex align-items-center order-1 order-lg-2">
                <img src="https://img.freepik.com/free-photo/entrepreneur-working-with-bills_1098-20001.jpg" class="img-fluid" alt="Sample image">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
