<?php
include 'connection.php';
session_start();
if (!isset($_SESSION['email'])) {
    header('location:login.php');
    exit();
}

// Handle form submission for adding an expense
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_expense'])) {
    // Sanitize inputs
    $expense_type = mysqli_real_escape_string($conn, $_POST['expense_type']);
    $amount = isset($_POST['amount']) ? (float) $_POST['amount'] : 0;
    $exclude_member = isset($_POST['exclude_member']) && is_array($_POST['exclude_member']) 
        ? $_POST['exclude_member'] : [];

    // Use custom expense type if 'others' is selected
    if ($expense_type == 'others' && !empty($_POST['otherExpenseType'])) {
        $expense_type = mysqli_real_escape_string($conn, $_POST['otherExpenseType']);
    }

    // Insert the expense data
    $exclude_member_str = implode(',', $exclude_member); // Save as a comma-separated string
    $query = "INSERT INTO expenses (expense_type, amount, created_at, exclude_member) 
              VALUES ('$expense_type', '$amount', NOW(), '$exclude_member_str')";

    if (mysqli_query($conn, $query)) {
        echo "<div class='alert alert-success'>Expense added successfully!</div>";

         // Fetch all users
         $query_users = "SELECT id, balance FROM users";
         $result_users = mysqli_query($conn, $query_users);
 
         if ($result_users && mysqli_num_rows($result_users) > 0) {
             $valid_users = [];
             while ($user = mysqli_fetch_assoc($result_users)) {
                 // Include only users who are not in the excluded list
                 if (!in_array($user['id'], $exclude_member)) {
                     $valid_users[] = $user;
                 }
             }
 
             // Calculate expense per user
             $total_users = count($valid_users);
 
             if ($total_users > 0) {
                 $expense_per_user = $amount / $total_users;
 
                 // Update balance for valid users
                 foreach ($valid_users as $user) {
                     $user_id = $user['id'];
                     $new_balance = $user['balance'] - $expense_per_user;
 
                     $update_query = "UPDATE users SET balance = $new_balance WHERE id = $user_id";
                     mysqli_query($conn, $update_query);
                 }
            } else {
                echo "<div class='alert alert-warning'>All members are excluded. No balance updates made.</div>";
            }
        } else {
            echo "<div class='alert alert-warning'>No users found to deduct the expense.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
    }
}

        // Fetch members
        $sql = "SELECT id, name FROM users";
        $result = mysqli_query($conn, $sql);
        $members = [];
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $members[] = $row;
            }
        }

        // Fetch expenses with optional filter
        $expense_type_filter = isset($_GET['expense_type']) ? $_GET['expense_type'] : '';
        $query = "SELECT * FROM expenses";
        if (!empty($expense_type_filter)) {
            $expense_type_filter = mysqli_real_escape_string($conn, $expense_type_filter);
            $query .= " WHERE expense_type = '$expense_type_filter'";
        }
        $result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: Arial, sans-serif; }
        .form-label { font-weight: bold; }
        .alert { margin-top: 10px; }

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
    <h2 class="text-center">Expense Management System</h2>

    <!-- Add Expense Form -->
    <div class="card mt-4">
        <div class="card-header bg-dark text-white">Add New Expense</div>
        <div class="card-body">
            <form method="POST" class="row g-3">
                <div class="col-md-4">
                    <label for="expense_type" class="form-label">Expense Type:</label>
                    <select name="expense_type" id="expense_type" class="form-select" onchange="toggleOtherExpenseInput()" required>
                        <option value="breakfast">Breakfast</option>
                        <option value="lunch">Lunch</option>
                        <option value="dinner">Dinner</option>
                        <option value="others">Others</option>
                    </select>
                </div>

<div>
    <label for="exclude_member_checkbox">Exclude Member</label>
    <input type="checkbox" id="exclude_member_checkbox" name="exclude_member_checkbox[]" value="1">
</div>

<div id="exclude_member_div" style="display: none;">
    <label for="exclude_members">Select Members to Exclude:</label>
    <select name="exclude_member[]" id="exclude_members" multiple>
        <?php foreach ($members as $member): ?>
            <option value="<?= htmlspecialchars($member['id']) ?>">
                <?= htmlspecialchars($member['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>


                <div class="col-md-4" id="otherExpenseTypeContainer" style="display: none;">
                    <label for="otherExpenseType" class="form-label">Other Expense Type:</label>
                    <input type="text" id="otherExpenseType" name="otherExpenseType" class="form-control" placeholder="Enter type">
                </div>
                <div class="col-md-4">
                    <label for="amount" class="form-label">Amount (PKR):</label>
                    <input type="number" step="0.01" name="amount" id="amount" class="form-control" required>
                </div>
                <div class="col-12 text-end">
                    <button type="submit" name="add_expense" class="btn btn-success">Add Expense</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Filter Expenses -->
    <div class="card mt-4">
        <div class="card-header bg-dark text-white">Filter Expenses</div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-8">
                    <label for="filter_expense_type" class="form-label">Filter by Expense Type:</label>
                    <select name="expense_type" id="filter_expense_type" class="form-select">
                        <option value="">All</option>
                        <option value="breakfast" <?php echo ($expense_type_filter == 'breakfast') ? 'selected' : ''; ?>>Breakfast</option>
                        <option value="lunch" <?php echo ($expense_type_filter == 'lunch') ? 'selected' : ''; ?>>Lunch</option>
                        <option value="dinner" <?php echo ($expense_type_filter == 'dinner') ? 'selected' : ''; ?>>Dinner</option>
                        <option value="others" <?php echo ($expense_type_filter == 'others') ? 'selected' : ''; ?>>Others</option>
                    </select>
                </div>
                <div class="col-md-4 text-end">
                    <button type="submit" class="btn btn-primary">View</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Display Expenses -->
    <h3 class="mt-5">Members' Expenses</h3>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Expense Type</th>
            <th>Amount (PKR)</th>
            <th>Date</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr><td>{$row['id']}</td><td>" . htmlspecialchars($row['expense_type']) . "</td><td>" . htmlspecialchars($row['amount']) . "</td><td>" . htmlspecialchars($row['created_at']) . "</td></tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No expenses found</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>

<script>
    function toggleOtherExpenseInput() {
        const expenseType = document.getElementById('expense_type').value;
        document.getElementById('otherExpenseTypeContainer').style.display = expenseType === 'others' ? 'block' : 'none';
    }

// Wait for the DOM to fully load
document.addEventListener('DOMContentLoaded', function () {
    const checkbox = document.querySelector('#exclude_member_checkbox');
    const excludeMemberDiv = document.querySelector('#exclude_member_div');

    // Toggle visibility based on checkbox state
    const toggleVisibility = () => {
        excludeMemberDiv.style.display = checkbox.checked ? 'block' : 'none';
    };

    // Set initial state
    toggleVisibility();

    // Add an event listener to the checkbox
    checkbox.addEventListener('change', toggleVisibility);
});


</script>
</body>
</html>
