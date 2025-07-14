<?php
include 'includes/auth.php';
include 'includes/db.php';

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $leave_date = $_POST['leave_date'];
    $reason = $_POST['reason'];

    // Prevent duplicate leave request on the same date
    $stmt = $conn->prepare("SELECT * FROM `leave_requests` WHERE user_id = ? AND leave_date = ?");
    $stmt->execute([$user_id, $leave_date]);
    if ($stmt->rowCount() > 0) {
        $error = "You have already requested leave for this date.";
    } else {
        $stmt = $conn->prepare("INSERT INTO `leave_requests` (user_id, leave_date, reason) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $leave_date, $reason]);
        $success = "Leave request submitted successfully!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Apply for Leave</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h2>📅 Apply for Leave</h2>

  <?php if (isset($error)): ?>
    <div class="alert alert-danger"><?= $error ?></div>
  <?php elseif (isset($success)): ?>
    <div class="alert alert-success"><?= $success ?></div>
  <?php endif; ?>

  <form method="post" class="mt-3">
    <div class="mb-3">
      <label for="leave_date" class="form-label">Leave Date:</label>
      <input type="date" name="leave_date" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="reason" class="form-label">Reason:</label>
      <textarea name="reason" class="form-control" rows="3" placeholder="Enter reason for leave" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Submit Request</button>
  </form>

  <div class="mt-4">
    <a href="staff_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
  </div>
</div>
</body>
</html>
