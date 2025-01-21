<?php
include 'connection.php';

session_start();
if(!isset($_SESSION['email'])){
    header('location:login.php');
    exit(); }

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_collection'])) {
    $member_id = mysqli_real_escape_string($conn, $_POST['member_id']);
    $amount = mysqli_real_escape_string($conn, $_POST['amount']);

    // Start transaction
    mysqli_begin_transaction($conn);
 

        // Insert the collection into the database
        $query = "INSERT INTO collections (member_id, amount , created_at) 
                  VALUES ('$member_id', '$amount', NOW())";
        if (!mysqli_query($conn, $query)) {
            throw new Exception("Error inserting collection: " . mysqli_error($conn));
        }

        // Update the contributor's balance
        $updateContributor = "UPDATE users SET balance = balance + $amount WHERE id = $member_id";
        if (!mysqli_query($conn, $updateContributor)) {
            throw new Exception("Error updating contributor balance: " . mysqli_error($conn));
        }

        // Commit the transaction
        mysqli_commit($conn);
        echo "<div class='alert alert-success'>Collection added successfully!</div>";
    } 

// Fetch members from the database for dropdown selection
$query = "SELECT id, name FROM users";
$result = mysqli_query($conn, $query);

$members = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $members[] = $row;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collections Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        h2, h3 {
            margin-top: 20px;
        }
        .form-label {
            font-weight: bold;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .alert {
            margin-top: 10px;
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
    <h2 class="text-center">Collections Management System</h2>

    <!-- Form to Add Collections -->
    <div class="card mt-4">
        <div class="card-header bg-dark text-white">
            <h5>Add New Collection</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="" class="row g-3">
                <div class="col-md-6">
                    <label for="member_id" class="form-label">Member Name:</label>
                    <select name="member_id" id="member_id" class="form-control" required>
                        <option value="">-- Select Member --</option>
                        <?php
                        // Populate dropdown with member names
                        foreach ($members as $member) {
                            echo "<option value='" . $member['id'] . "'>" . htmlspecialchars($member['name']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="amount" class="form-label">Amount (PKR):</label>
                    <input type="number" step="0.01" name="amount" id="amount" class="form-control" required>
                </div>
                <div class="col-12 text-end">
                    <button type="submit" name="add_collection" class="btn btn-success">Add Collection</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Display Collections -->
    <h3 class="mt-5">Collections Records</h3>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Member Name</th>
            <th>Amount (PKR)</th>
            <th>Date</th>
        </tr>
        </thead>
        <tbody>
        <?php
        // Fetch collections data
        $query = "SELECT c.id, u.name AS member_name, c.amount, c.created_at
                  FROM collections c
                  JOIN users u ON c.member_id = u.id
                  ORDER BY c.created_at DESC";

        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['member_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['amount']) . "</td>";
                echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No collections found</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
