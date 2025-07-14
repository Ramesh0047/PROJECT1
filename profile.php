<?php
include 'auth.php';
include 'db.php';

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

// Fetch current user data
$stmt = $conn->prepare("SELECT name, email FROM `users` WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Update profile info
if (isset($_POST['update_profile'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];

    $update = $conn->prepare("UPDATE `users` SET name = ?, email = ? WHERE id = ?");
    $update->execute([$name, $email, $user_id]);
    $_SESSION['username'] = $name;
    $success = "Profile updated successfully!";
}

// Change password
if (isset($_POST['change_password'])) {
    $old = $_POST['old_password'];
    $new = $_POST['new_password'];

    // Fetch current password hash
    $stmt = $conn->prepare("SELECT password FROM `users` WHERE id = ?");
    $stmt->execute([$user_id]);
    $hashed = $stmt->fetchColumn();

    if (password_verify($old, $hashed)) {
        $newHash = password_hash($new, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
        $stmt->execute([$newHash, $user_id]);
        $success = "Password changed successfully!";
    } else {
        $error = "Incorrect current password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>My Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h2>👤 My Profile</h2>

  <?php if ($success): ?>
    <div class="alert alert-success"><?= $success ?></div>
  <?php elseif ($error): ?>
    <div class="alert alert-danger"><?= $error ?></div>
  <?php endif; ?>

  <form method="post" class="mb-4">
    <h5>Update Profile Info</h5>
    <div class="mb-2">
      <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
    </div>
    <div class="mb-2">
      <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
    </div>
    <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
  </form>

  <form method="post">
    <h5>Change Password</h5>
    <div class="mb-2">
      <input type="password" name="old_password" class="form-control" placeholder="Current Password" required>
    </div>
    <div class="mb-2">
      <input type="password" name="new_password" class="form-control" placeholder="New Password" required>
    </div>
    <button type="submit" name="change_password" class="btn btn-warning">Change Password</button>
  </form>

  <div class="mt-4">
    <a href="<?= $_SESSION['role'] === 'admin' ? 'admin_dashboard.php' : 'staff_dashboard.php' ?>" class="btn btn-secondary">Back to Dashboard</a>
  </div>
</div>
</body>
</html>
