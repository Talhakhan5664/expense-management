<?php
include 'connection.php'; 
session_start();
if(!isset($_SESSION['email'])){
    header('location:login.php');
  exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_member'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);

    $user = "SELECT * FROM users where email = '$email'";
    $result = mysqli_query($conn, $user) or die('error in query of getting user');
    if(mysqli_num_rows($result) > 0) {
        echo "<div class='alert alert-danger'>Member Already Added</div>";
    }
    else{
        $query = "INSERT INTO users (name, email, phone_number) VALUES ('$name', '$email', '$phone_number')";
    
        if (mysqli_query($conn, $query)) {
            echo "<div class='alert alert-success'>Member added successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Members</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
         /* Navbar Styling */
         .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color:rgb(4, 44, 86);
            color: #fff;
            box-shadow: 0 4px 6px rgba(5, 35, 99, 0.37);
        }

        /* Logo Section */
        .navbar .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #fff;
            text-decoration: none;
        }

        /* Buttons Section */
        .navbar .nav-buttons {
            display: flex;
            gap: 15px;
        }

        .navbar .nav-buttons a {
            text-decoration: none;
            font-weight: bold;
            color: #007bff;
            background-color: #fff;
            padding: 8px 15px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .navbar .nav-buttons a:hover {
            background-color: #0056b3;
            color: #fff;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .nav-buttons {
                flex-wrap: wrap;
                gap: 10px;
                margin-top: 10px;
            }
        }
    </style>

</head>

<body>
     <!-- Navbar -->
  <div class="navbar"  >
        <!-- Logo -->
        <a href="index.php"  class="logo">Expenses Manager</a>

        <!-- Navigation Buttons -->
        <div class="nav-buttons" >
            <a href="index.php">Dashboard</a>
            <a href="add_member.php">Add Member</a>
            <a href="expenses.php">View Expenses</a>
            <a href="collections.php">View Collections</a>
            <a href="report.php">View Report</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>


<div class="container mt-5">
    <h2>Add a New Member</h2>
    <button class="btn btn-primary" id="addMember">Add Member</button>
    <form method="POST" class="mb-4" id="addMemberForm">
        <div class="mb-3">
            <label for="name" class="form-label">Name:</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="phone_number" class="form-label">Phone_number:</label>
            <input type="text" name="phone_number" id="phone_number" class="form-control">
        </div>
        <button type="submit" name="add_member" class="btn btn-primary">Add Member</button>
    </form>

    <h3>Members List</h3>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone_number</th>
            
        </tr>
        </thead>
        <tbody>
        <?php
        $query = "SELECT * FROM users ORDER BY id DESC";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td>" . htmlspecialchars($row['phone_number']) . "</td>";
              
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No members found.</td></tr>";
        }
        ?>
        </tbody>
    </table>

 
      
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            text-align: center;
        }

        select, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: #fff;
        }
    </style>


</body>
<script>
    let btn = document.getElementById('addMember');
    let form =document.getElementById('addMemberForm');
    form.style.display="none";
    btn.addEventListener('click', function(){
            btn.style.display="none";
            form.style.display="block";
    })
</script>
</html>
