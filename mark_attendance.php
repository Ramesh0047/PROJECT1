<?php
include 'includes/auth.php';
include 'includes/db.php';

$user_id = $_SESSION['user_id'];
$today = date('Y-m-d');

// Check if already marked
$stmt = $conn->prepare("SELECT * FROM attendance WHERE user_id = ? AND date = ?");
$stmt->execute([$user_id, $today]);
$alreadyMarked = $stmt->fetch();

if ($_SERVER["REQUEST_METHOD"] == "POST" && !$alreadyMarked) {
    $status = $_POST['status'];

    $insert = $conn->prepare("INSERT INTO `attendance` (user_id, date, status) VALUES (?, ?, ?)");
    $insert->execute([$user_id, $today, $status]);

    $success = "Attendance marked as '$status'.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Mark Attendance</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h2>📝 Mark Attendance</h2>

  <?php if ($alreadyMarked): ?>
    <div class="alert alert-info mt-3">
      You already marked your attendance today as <strong><?= $alreadyMarked['status'] ?></strong>.
    </div>
  <?php elseif (isset($success)): ?>
    <div class="alert alert-success mt-3"><?= $success ?></div>
  <?php else: ?>
    <form method="post" class="mt-4">
      <div class="mb-3">
        <label for="status" class="form-label">Select Status:</label>
        <select name="status" class="form-select" required>
          <option value="">-- Choose --</option>
          <option value="Present">Present</option>
          <option value="Late">Late</option>
          <option value="Half-Day">Half-Day</option>
          <option value="Absent">Absent</option>
        </select>
      </div>
      <button type="submit" class="btn btn-success">Submit Attendance</button>
    </form>
  <?php endif; ?>

  <div class="mt-4">
    <a href="staff_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
  </div>
</div>
</body>
</html>
