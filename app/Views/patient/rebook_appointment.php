<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rebook Appointment</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
   <?php if (session()->getFlashdata('error')): ?>
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-triangle"></i>
    <?= session()->getFlashdata('error') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?> 
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-success text-white text-center">
          <h4>Rebook Appointment</h4>
        </div>
        <div class="card-body p-4">
          <form method="post" action="<?= base_url('patient/appointments/saveRebook') ?>">
            
            <!-- Doctor Name (readonly) -->
            <div class="mb-3">
              <label class="form-label">Doctor</label>
              <input type="text" class="form-control" value="<?= esc($doctor['doctor_name']) ?> (<?= esc($doctor['specialization']) ?>)" readonly>
              <!-- hidden doctor_id -->
              <input type="hidden" name="doctor_id" value="<?= $doctor['id'] ?>">
            </div>

            <!-- Date -->
            <div class="mb-3">
              <label for="date" class="form-label">New Appointment Date</label>
              <input type="date" name="date" id="date" class="form-control"
                     value="<?= date('Y-m-d', strtotime($appointment['start_datetime'])) ?>" required>
            </div>

            <!-- Time -->
            <div class="mb-3">
              <label for="time" class="form-label">New Appointment Time</label>
              <input type="time" name="time" id="time" class="form-control"
                     value="<?= date('H:i', strtotime($appointment['start_datetime'])) ?>" required>
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-success">Rebook Appointment</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
