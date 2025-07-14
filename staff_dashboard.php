<?php
include 'includes/auth.php';
include 'includes/db.php';

// Get user ID
$user_id = $_SESSION['user_id'];

// Check today’s attendance
$today = date('Y-m-d');
$stmt = $conn->prepare("SELECT status FROM `attendance` WHERE user_id = ? AND date = ?");
$stmt->execute([$user_id, $today]);
$status = $stmt->fetchColumn() ?: 'Not Marked';

// Count approved leaves
$approvedLeaves = $conn->prepare("SELECT COUNT(*) FROM `leave_requests` WHERE user_id = ? AND status = 'approved'");
$approvedLeaves->execute([$user_id]);
$leaveCount = $approvedLeaves->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Staff Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h2>👩‍🏫 Welcome, <?= htmlspecialchars($_SESSION['username']) ?></h2>
  <ul class="list-group mt-4">
    <li class="list-group-item">Today's Attendance: <strong><?= $status ?></strong></li>
    <li class="list-group-item">Total Approved Leaves: <strong><?= $leaveCount ?></strong></li>
  </ul>

  <div class="mt-4">
    <a href="mark_attendance.php" class="btn btn-success">Mark Attendance</a>
    <a href="apply_leave.php" class="btn btn-primary">Apply for Leave</a>
    <a href="logout.php" class="btn btn-danger">Logout</a>
  </div>
</div>
</body>
</html>
