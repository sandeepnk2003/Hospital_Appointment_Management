<!-- app/Views/patient/appointments_history.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Appointments History</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="<?= base_url('patient/dashboard') ?>">Appointment Portal</a>
      <div class="d-flex">
        <a href="<?= base_url('patient/profile') ?>" class="btn btn-outline-light btn-sm me-2">
          <i class="bi bi-person-circle"></i> My Profile
        </a>
        <a href="<?= base_url('logout') ?>" class="btn btn-danger btn-sm">
          <i class="bi bi-box-arrow-right"></i> Logout
        </a>
      </div>
    </div>
  </nav>

  <!-- Page Content -->
   <?php if (session()->getFlashdata('error')): ?>
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-triangle"></i>
    <?= session()->getFlashdata('error') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?> 
  <div class="card shadow-lg border-0 rounded-3">
  <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
    <h4 class="mb-0">My Appointments History</h4>
    <a href="<?= base_url('patient/book') ?>" class="btn btn-light btn-sm">
      <i class="bi bi-calendar-plus"></i> Book an Appointment
    </a>
  </div>
  <div class="card-body p-4">
    <?php if (!empty($appt)): ?>
      <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
          <thead class="table-dark text-center">
            <tr>
              <th>#</th>
              <th>Doctor</th>
              <th>Specialization</th>
              <th>Date & Time</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($appt as $index => $apptRow): ?>
              <tr>
                <td class="text-center"><?= $index + 1 ?></td>
                <td><?= esc($apptRow['doctor_name']) ?></td>
                <td><?= esc($apptRow['specialization'] ?? 'N/A') ?></td>
                <td>
                  <?= date('d M Y, h:i A', strtotime($apptRow['start_datetime'])) ?>
                </td>
                <td class="text-center">
                  <?php if ($apptRow['status'] === 'Completed'): ?>
                    <span class="badge bg-success">Completed</span>
                  <?php elseif ($apptRow['status'] === 'Cancelled'): ?>
                    <span class="badge bg-danger">Canceled</span>
                  <?php else: ?>
                    <span class="badge bg-warning text-dark">Scheduled</span>
                  <?php endif; ?>
                </td>
                <td class="text-center">
                  <?php if ($apptRow['status'] === 'Scheduled'): ?>
                    <a href="<?= base_url('appointments/cancel/' . $apptRow['id']); ?>" 
                       class="btn btn-danger btn-sm">
                      Cancel
                    </a>
                     <a href="<?= base_url('patient/appointments/reschedule/'. $apptRow['id']); ?>" 
                       class="btn btn-success btn-sm">
                      Reshedule
                    </a>
                  <?php else: ?>
                    <a href="<?= base_url('patient/appointments/rebook/' . $apptRow['id']) ?>" 
                       class="btn btn-sm btn-success">
                      <i class="bi bi-arrow-repeat"></i> Rebook
                    </a>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div class="alert alert-info text-center">
        <i class="bi bi-info-circle"></i> You have no appointment history yet.
      </div>
    <?php endif; ?>
  </div>
</div>


  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
