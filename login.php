<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg">
                <div class="card-header bg-dark text-white text-center">
                    <h4>Staff Login</h4>
                </div>
                <div class="card-body">
                    <!-- Display error if set -->
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" name="email" class="form-control" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Login</button>
                        <div class="card-footer text-center text-muted">
                    create an account? <a href="ragister.php">Ragister</a>
                </div>
                    </form>
                </div>
                <div class="card-footer text-center text-muted">
                    © 2025 School System
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>

<?php
session_start();
include 'includes/db.php'; // Assumes you're using MySQLi

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare the statement
    $stmt = $conn->prepare("SELECT * FROM `users`WHERE email = ?");
    $stmt->execute([$email]);
    $stmt->execute();

    // Get the result
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
   // $user = $result->fetch_assoc();

    // Verify password
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION["user_id"] = $user['id'];
        $_SESSION['username'] = $user['name']; // ✅ Set username after successful login
        $_SESSION["role"] = $user['role'];
        $_SESSION["name"] = $user['name'];
        //header("Location: admin_dashboard.php");
        header("Location: staff_dashboard.php");
        exit();
    } else {
        $error = "Invalid email or password!";
    }
}
?>

