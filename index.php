<?php

include 'connection.php';
session_start();
if(!isset($_SESSION['email'])){
    header('location:login.php');
    exit();
}
// Query to get the total number of members
$sql = "SELECT COUNT(*) AS total_members FROM users";
$result = $conn->query($sql);

// Check if we got a result
if ($result->num_rows > 0) {
    // Fetch the result
    $row = $result->fetch_assoc();
    $total_members = $row['total_members'];
} else {
    // If no members exist, set to 0
    $total_members = 0;
}
$sql = "SELECT SUM(amount) AS total_expenses FROM expenses";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_expenses = $row['total_expenses'];
    } else {
        $total_expenses = 0;
    }

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8fafc; color: #333; margin: 0; padding: 0; line-height: 1.6;">

  <!-- Navbar -->
  <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px 20px; background-color: #043d86; color: white; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        <!-- Logo -->
        <a href="index.php" style="font-size: 1.8rem; font-weight: bold; color: white; text-decoration: none;">Expenses Manager</a>

        <!-- Navigation Buttons -->
        <div style="display: flex; gap: 15px;">
            <a href="index.php" style="text-decoration: none; font-weight: 600; color: #043d86; background-color: white; padding: 10px 18px; border-radius: 5px; transition: background-color 0.3s ease, color 0.3s ease;">Dashboard</a>
            <a href="add_member.php" style="text-decoration: none; font-weight: 600; color: #043d86; background-color: white; padding: 10px 18px; border-radius: 5px; transition: background-color 0.3s ease, color 0.3s ease;">Add Member</a>
            <a href="expenses.php" style="text-decoration: none; font-weight: 600; color: #043d86; background-color: white; padding: 10px 18px; border-radius: 5px; transition: background-color 0.3s ease, color 0.3s ease;">View Expenses</a>
            <a href="collections.php" style="text-decoration: none; font-weight: 600; color: #043d86; background-color: white; padding: 10px 18px; border-radius: 5px; transition: background-color 0.3s ease, color 0.3s ease;">View Collections</a>
            <a href="report.php" style="text-decoration: none; font-weight: 600; color: #043d86; background-color: white; padding: 10px 18px; border-radius: 5px; transition: background-color 0.3s ease, color 0.3s ease;">View Report</a>
            <a href="logout.php" style="text-decoration: none; font-weight: 600; color: #043d86; background-color: white; padding: 10px 18px; border-radius: 5px; transition: background-color 0.3s ease, color 0.3s ease;">Logout</a>
        </div>
    </div>

    <div style="margin-top: 15px; max-width: 900px; margin: 30px auto; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
        <header style="text-align: center; margin-bottom: 30px;">
            <h1 style="font-size: 2rem; color: #043d86; margin-bottom: 10px;">Welcome, <?php echo $_SESSION['name']; ?>!</h1>
        </header>

        <main>
            <h2 style="font-size: 1.5rem; margin-bottom: 20px;">Dashboard Overview</h2>
            <table style="width: 100%; border-collapse: collapse; margin: 20px 0; font-size: 1rem;">
                <tr>
                    <th style="padding: 12px 15px; text-align: left; border: 1px solid #ddd; background-color: #f4f4f4; font-weight: bold;">Total Members</th>
                    <td style="padding: 12px 15px; text-align: left; border: 1px solid #ddd; background-color: #fafafa;"><?php echo $total_members; ?></td>
                </tr>

                <tr>
                    <th style="padding: 12px 15px; text-align: left; border: 1px solid #ddd; background-color: #f4f4f4; font-weight: bold;">Total Expenses</th>
                    <td style="padding: 12px 15px; text-align: left; border: 1px solid #ddd; background-color: #fafafa;"><?php echo $total_expenses; ?></td>
                </tr>
            </table>
        </main>

        <footer style="text-align: center; margin-top: 20px; color: #888; font-size: 0.9rem;">
            <p>&copy; Expense Management System</p>
        </footer>
    </div>

    <script src="script.js"></script>
</body>
</html>
