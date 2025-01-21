<?php
include 'connection.php';

// Fetch all users for the dropdown
$userQuery = "SELECT id, name FROM users";
$userResult = mysqli_query($conn, $userQuery);

// Get the total number of users
$userCountQuery = "SELECT COUNT(*) AS total_users FROM users";
$userCountResult = mysqli_query($conn, $userCountQuery);
$totalUsersRow = mysqli_fetch_assoc($userCountResult);
$totalUsers = $totalUsersRow['total_users'];
// Initialize variables
$weeklyReport = [];
$selectedUserId = isset($_POST['user_id']) ? $_POST['user_id'] : null;
$from_date = isset($_POST['from_date']) ? $_POST['from_date'] : date('Y-m-d', strtotime('monday this week')); // Start of the week (Monday)
$to_date = isset($_POST['to_date']) ? $_POST['to_date'] : date('Y-m-d', strtotime('sunday this week'));

if ($selectedUserId) {
    // Fetch weekly collections and expenses for the selected user
    $reportQuery = "
        SELECT SUM(amount) as total_collection  FROM
            collections 
            WHERE  member_id= '$selectedUserId'
            AND created_at BETWEEN '$from_date' and '$to_date'
    "; 
    $total_collections = 0;
    $reportResult = mysqli_query($conn, $reportQuery);
    if (mysqli_num_rows($reportResult) > 0) {
        $row = mysqli_fetch_array($reportResult);
        if($row)
            $total_collections = ($row['total_collection'] != NULL) ? $row['total_collection'] : 0;
          
    }
    
    $total_expense = 0;

// Query to fetch all expenses within the date range
$expenseQuery = "
    SELECT amount, exclude_member 
    FROM expenses 
    WHERE created_at BETWEEN '$from_date' AND '$to_date'
";
$expenseResult = mysqli_query($conn, $expenseQuery);

if ($expenseResult) {
    while ($expenseRow = mysqli_fetch_assoc($expenseResult)) {
        $amount = $expenseRow['amount'];
        $exclude_members = $expenseRow['exclude_member']; // Comma-separated excluded members

        // Check if the selected user is excluded
        if (!empty($exclude_members) && in_array($selectedUserId, explode(',', $exclude_members))) {
            // User is excluded, skip this expense
            continue;
        }

        // Calculate the number of included users for this expense
        $excludedArray = !empty($exclude_members) ? explode(',', $exclude_members) : [];
        $includedUsersCount = $totalUsers - count($excludedArray);

        // Avoid division by zero
        if ($includedUsersCount > 0) {
            // Add the user's share of the expense
            $total_expense += $amount / $includedUsersCount;
        }
    }
}

// Calculate balance
$balance = round($total_collections - $total_expense, 2);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weekly Report</title>
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
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        .container {
            width: 80%;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form {
            margin-bottom: 30px;
            text-align: center;
        }
        label {
            font-size: 16px;
            color: #555;
        }
        select {
            padding: 8px;
            font-size: 14px;
            margin-right: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color:rgb(21, 20, 20);
            color: #ccc;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color:rgb(235, 222, 222);
        }
        .no-data {
            text-align: center;
            color: red;

        }
        input[type="date"] {
            padding: 8px;
            font-size: 14px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 200px;
        }
        label {
            font-size: 16px;
            color: #555;
            margin-bottom: 10px;
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
    
<div class="container">
    <h1>Weekly Report</h1>

    <!-- User Dropdown -->
    <form method="POST" action="" style="text-align: left; margin-left: 20px;">
    
        <label for="user_id">Select User:</label>
        
        <select name="user_id" id="user_id" required>
            
            <option value="">-- Select User --</option>
            <?php while ($user = mysqli_fetch_assoc($userResult)): ?>
                <option value="<?= $user['id']; ?>" <?= $selectedUserId == $user['id'] ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($user['name']); ?>
                </option>
            <?php endwhile; ?>
        </select>
       
        <label for="from_date">From Date:</label>
        <input type="date" id="from_date" name="from_date" value="<?= $from_date; ?>" required>
        <label for="to_date">To Date:</label>
        <input type="date" id="to_date" name="to_date"  value="<?= $to_date; ?>" required>

        <button type="submit">Generate Report</button>
    </form>

    <!-- Weekly Report Table -->
    <?php if ($selectedUserId): ?>
        <table style="border-right-color: #333;">
            <thead>
            <tr>
                <th>Week</th>
                <th>Total Collections (PKR)</th>
                <th>Total Expenses (PKR)</th>
                <th>Balance (PKR)</th>
            </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= $from_date . ' - ' . $to_date; ?></td>
                    <td><?= $total_collections; ?></td>
                    <td><?= round($total_expense , 2); ?></td>
                    <td><?= round($balance , 2); ?></td>
                </tr>
            </tbody>
        </table>
    <?php elseif ($selectedUserId): ?>
        <p class="no-data">No data found for the selected user.</p>
    <?php endif; ?>
</div>
</body>
</html>