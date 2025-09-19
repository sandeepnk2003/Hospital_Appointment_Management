<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reschedule Appointment</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <!-- Alert messages -->
      <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
      <?php endif; ?>
      <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
      <?php endif; ?>

      <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-warning text-dark text-center">
          <h4>Reschedule Appointment</h4>
        </div>
        <div class="card-body p-4">
          <form method="post" action="<?= base_url('patient/appointments/reschedule/'.$appointment['id']) ?>">
            <?= csrf_field() ?>

            <!-- Doctor info (read-only) -->
            <div class="mb-3">
              <label class="form-label">Doctor</label>
              <input type="text" class="form-control"
                     value="Dr. <?= esc($doctor['doctor_name'] ?? 'Unknown') ?> (<?= esc($doctor['specialization'] ?? '') ?>)"
                     readonly>
            </div>

            <!-- New Date -->
            <div class="mb-3">
              <label for="date" class="form-label">New Date</label>
              <input type="date" name="date" id="date" class="form-control"
                     value="<?= date('Y-m-d', strtotime($appointment['start_datetime'])) ?>" required>
            </div>

            <!-- New Time -->
            <div class="mb-3">
              <label for="time" class="form-label">New Time</label>
              <input type="time" name="time" id="time" class="form-control"
                     value="<?= date('H:i', strtotime($appointment['start_datetime'])) ?>" required>
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-warning">Reschedule</button>
              <a href="<?= base_url('patient/appointments/history') ?>" class="btn btn-secondary mt-2">Cancel</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
