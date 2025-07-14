<?php
include 'auth.php';
include 'db.php';

// Total staff count
$totalStaff = $conn->query("SELECT COUNT(*) FROM `users` WHERE role = 'staff'")->fetchColumn();

// Today's attendance
$today = date('Y-m-d');
$todayAttendance = $conn->query("SELECT COUNT(*) FROM `attendance` WHERE date = '$today'")->fetchColumn();

// Pending leave requests
$pendingLeaves = $conn->query("SELECT COUNT(*) FROM `leave_requests` WHERE status = 'pending'")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h2>👨‍💼 Admin Dashboard</h2>
  <ul class="list-group mt-4">
    <li class="list-group-item">Total Staff: <strong><?= $totalStaff ?></strong></li>
    <li class="list-group-item">Today's Attendance Marked: <strong><?= $todayAttendance ?></strong></li>
    <li class="list-group-item">Pending Leave Requests: <strong><?= $pendingLeaves ?></strong></li>
  </ul>

  <div class="mt-4">
    <a href="manage_leaves.php" class="btn btn-warning">Manage Leaves</a>
    <a href="logout.php" class="btn btn-danger">Logout</a>
  </div>
</div>
</body>
</html>
