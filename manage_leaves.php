<?php
include 'auth.php';
include 'db.php';

if ($_SESSION['role'] !== 'admin') {
    echo "Access denied!";
    exit();
}

// Handle approve/reject actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $action = $_GET['action'];

    if (in_array($action, ['approved', 'rejected'])) {
        $stmt = $conn->prepare("UPDATE `leave_requests` SET status = ? WHERE id = ?");
        $stmt->execute([$action, $id]);
        header("Location: manage_leaves.php");
        exit();
    }
}

// Fetch all leave requests
$leaves = $conn->query("
    SELECT leave_requests.*, users.name 
    FROM leave_requests 
    JOIN users ON leave_requests.user_id = users.id 
    ORDER BY leave_requests.requested_at DESC
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Manage Leave Requests</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h2>🗂️ Leave Requests</h2>

  <?php if (count($leaves) > 0): ?>
    <table class="table table-bordered mt-4">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Staff Name</th>
          <th>Date</th>
          <th>Reason</th>
          <th>Status</th>
          <th>Requested At</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($leaves as $index => $leave): ?>
          <tr>
            <td><?= $index + 1 ?></td>
            <td><?= htmlspecialchars($leave['name']) ?></td>
            <td><?= $leave['leave_date'] ?></td>
            <td><?= htmlspecialchars($leave['reason']) ?></td>
            <td><strong><?= ucfirst($leave['status']) ?></strong></td>
            <td><?= $leave['requested_at'] ?></td>
            <td>
              <?php if ($leave['status'] == 'pending'): ?>
                <a href="?action=approved&id=<?= $leave['id'] ?>" class="btn btn-success btn-sm">Approve</a>
                <a href="?action=rejected&id=<?= $leave['id'] ?>" class="btn btn-danger btn-sm">Reject</a>
              <?php else: ?>
                <span class="text-muted">—</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <div class="alert alert-info mt-4">No leave requests found.</div>
  <?php endif; ?>

  <div class="mt-4">
    <a href="admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
  </div>
</div>
</body>
</html>
