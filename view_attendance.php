<?php
include 'auth.php';
include 'db.php';

$user_id = $_SESSION['user_id'];

// Get selected month/year or default to current
$selectedMonth = $_GET['month'] ?? date('m');
$selectedYear = $_GET['year'] ?? date('Y');

// Get all days of selected month
$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $selectedMonth, $selectedYear);
$attendanceData = [];

// Get all attendance entries for this month
$stmt = $conn->prepare("SELECT date, status FROM `attendance` WHERE user_id = ? AND MONTH(date) = ? AND YEAR(date) = ?");
$stmt->execute([$user_id, $selectedMonth, $selectedYear]);

foreach ($stmt->fetchAll() as $row) {
    $attendanceData[$row['date']] = $row['status'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Attendance Report</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h2>📊 Monthly Attendance Report</h2>

  <!-- Form to select month/year -->
  <form class="row g-3 my-4" method="get">
    <div class="col-md-3">
      <select name="month" class="form-select" required>
        <?php for ($m = 1; $m <= 12; $m++): ?>
          <option value="<?= $m ?>" <?= ($selectedMonth == $m) ? 'selected' : '' ?>>
            <?= date('F', mktime(0, 0, 0, $m, 1)) ?>
          </option>
        <?php endfor; ?>
      </select>
    </div>
    <div class="col-md-3">
      <select name="year" class="form-select" required>
        <?php for ($y = date('Y'); $y >= 2022; $y--): ?>
          <option value="<?= $y ?>" <?= ($selectedYear == $y) ? 'selected' : '' ?>><?= $y ?></option>
        <?php endfor; ?>
      </select>
    </div>
    <div class="col-md-3">
      <button class="btn btn-primary">View Report</button>
    </div>
  </form>

  <!-- Attendance Table -->
  <table class="table table-bordered">
    <thead class="table-light">
      <tr>
        <th>Date</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <?php for ($day = 1; $day <= $daysInMonth; $day++): 
          $date = sprintf('%04d-%02d-%02d', $selectedYear, $selectedMonth, $day);
          $status = $attendanceData[$date] ?? 'Not Marked';
      ?>
        <tr>
          <td><?= date('d M Y', strtotime($date)) ?></td>
          <td><?= $status ?></td>
        </tr>
      <?php endfor; ?>
    </tbody>
  </table>

  <div class="mt-4">
    <a href="staff_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
  </div>
</div>
</body>
</html>
