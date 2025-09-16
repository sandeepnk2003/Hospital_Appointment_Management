<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Patient Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    /* Make the page use full height */
    html, body {
      height: 100%;
      margin: 0;
      display: flex;
      flex-direction: column;
    }

    /* Main container grows to push footer down */
    .content {
      flex: 1;
    }

    footer {
      background-color: #212529; /* Bootstrap dark */
      color: white;
      text-align: center;
      padding: 10px 0;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?= base_url('patient/dashboard') ?>">Appointment Portal</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link" href="<?= base_url('patient/profile') ?>">My Profile</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= base_url('patient/appointments/history') ?>">My Appointments</a>
        </li>
      </ul>
      <span class="navbar-text text-white me-3">
        Logged in as <strong><?= session()->get('patient_name') ?></strong>
      </span>
      <a href="<?= base_url('logout') ?>" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<!-- Main content wrapper -->
<div class="container mt-4 content">
  <h2>Welcome, <?= session()->get('patient_name') ?>!</h2>

  <div class="row my-4">
    <div class="col-md-4">
      <div class="card text-center border-primary">
        <div class="card-body">
          <h5 class="text-primary">Upcoming</h5>
          <p class="h4"><?= $upcomingCount; ?> Appointments</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-center border-success">
        <div class="card-body">
          <h5 class="text-success">Completed</h5>
          <p class="h4"><?= $completedCount; ?> Appointments</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-center border-danger">
        <div class="card-body">
          <h5 class="text-danger">Canceled</h5>
          <p class="h4"><?= $canceledCount; ?> Appointments</p>
        </div>
      </div>
    </div>
  </div>

  <h4>Upcoming Appointments</h4>
  <?php if (count($upcomingAppointments) > 0): ?>
    <table class="table table-bordered mt-3">
      <thead class="table-dark">
        <tr>
          <th>Doctor</th>
          <th>Start Time</th>
          <th>End Time</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($upcomingAppointments as $appt): ?>
          <tr>
            <td><?= esc($appt['doctor_name']); ?></td>
            <td><?= date('d/m/Y H:i', strtotime($appt['start_datetime'])); ?></td>
            <td><?= date('d/m/Y H:i', strtotime($appt['end_datetime'])); ?></td>
            <td>
              <span class="badge <?= $appt['status'] === 'Scheduled' ? 'bg-warning' : ($appt['status'] === 'completed' ? 'bg-success' : 'bg-danger') ?>">
                <?= ucfirst($appt['status']); ?>
              </span>
            </td>
            <td>
              <?php if ($appt['status'] === 'Scheduled'): ?>
                <a href="<?= base_url('appointments/cancel/'.$appt['id']); ?>" 
                   class="btn btn-danger btn-sm">
                  Cancel
                </a>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p class="text-muted mt-3">No upcoming appointments.</p>
  <?php endif; ?>
</div>

<!-- Sticky footer -->
<footer>
  &copy; <?= date('Y') ?> Appointment Portal
</footer>

</body>
</html>
