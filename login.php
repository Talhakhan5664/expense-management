<?php
include 'connection.php';


session_start();

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Check if email exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password (if passwords are hashed in the database)
        if ($password==$user['password']) {
            $_SESSION['email'] = $user['email'];
            $_SESSION['logged-in'] = true;
            $_SESSION['name'] = $user['name'];

            // Redirect to the dashboard
            header("Location: index.php");
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No account found with this email.";
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Log In</title>
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
              <div class="col-md-10 col-lg-6 col-xl-5">
                <p class="text-center h1 fw-bold mb-5">Sign In</p>
                <form method="POST" action="#" class="mx-1 mx-md-4">
                  <div class="mb-4">
                    <input type="text" name="email" class="form-control" placeholder="Enter your email" required /> Enter Your Email
                  </div> 
                  <div class="mb-4">
                    <input type="password" name="password" class="form-control" placeholder="Enter your Password" required /> Enter Password
                  </div>
                  <div class="d-flex justify-content-center">
                    <button type="login" name="login" class="btn btn-primary btn-lg">Login</button>
                  </div>
                </form>
              </div>
              <div class="col-md-10 col-lg-6 col-xl-7">
                <img src="https://img.freepik.com/free-photo/entrepreneur-working-with-bills_1098-20001.jpg"
                  class="img-fluid" alt="Sample image">
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
