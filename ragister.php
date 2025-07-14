<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Staff Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header bg-dark text-white text-center">
                    <h4>Create Account</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php elseif (isset($success)): ?>
                        <div class="alert alert-success"><?= $success ?></div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select" required>
                                <option value="">-- Select Role --</option>
                                <option value="admin">Admin</option>
                                <option value="staff">Staff</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Register</button>
                    </form>
                </div>
                <div class="card-footer text-center text-muted">
                    Already have an account? <a href="login.php">Login here</a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
<?php
session_start();
include 'includes/db.php'; // Assumes $conn is your MySQLi connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure hash
    $role     = $_POST['role']; // admin/staff

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM `users` WHERE email = ?");
    $stmt->execute([$email]);
    $stmt->execute();
    //$stmt->store_result();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        $error = "Email already registered!";
        // Email already exists
    } else {
        $created_at = date('Y-m-d H:i:s'); // current timestamp
        // Proceed with registration
    }
        
        $stmt = $conn->prepare("INSERT INTO `users`(`name`, `email`, `password`, `role`, `created_at`) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $password, $role, $created_at]);
        if ($stmt->execute()) {
            $success = "Registered successfully! You can now <a href='login.php'>Login</a>.";
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
?>
