<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Doctor Availability</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      background: linear-gradient(to right, #f0f8ff, #e6f0ff);
      font-family: "Poppins", sans-serif;
    }
    .modal-content {
      border-radius: 1rem;
    }
    .modal-header {
      border-bottom: none;
    }
    .modal-footer {
      border-top: none;
    }
    .form-control, .form-select {
      border-radius: 0.6rem;
    }
  </style>
</head>

<body class="d-flex justify-content-center align-items-center vh-100">

  <!-- Modal Trigger (for demo only, you can remove this button if opening automatically) -->
  <!-- <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal">
    <i class="bi bi-pencil-square me-2"></i> Edit Availability
  </button> -->

  <!-- Edit Doctor Availability Modal -->
  <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content shadow-lg border-0 rounded-4">
        <form action="<?= base_url('availability/edit/' . $avail['id']); ?>" method="post">
          <?= csrf_field() ?>

          <div class="modal-header bg-primary text-white rounded-top-4">
            <h5 class="modal-title">
              <i class="bi bi-calendar-check me-2"></i>Edit Doctor Availability
            </h5>
           <!-- <a href="<?= site_url('/availability'); ?>" class="btn-close btn-close-white" data-bs-dismiss="modal"></a> -->

          </div>

          <div class="modal-body p-4">
            <div class="row g-3">
              <!-- Doctor -->
              <div class="col-md-6">
                <label class="form-label fw-semibold">Doctor</label>
                <select name="doctor_id" class="form-select shadow-sm" required>
                  <option value="">-- Select Doctor --</option>
                  <?php foreach ($doctor as $d): ?>
                    <option value="<?= $d['id'] ?>" 
                      <?= $d['id'] == $avail['doctor_id'] ? 'selected' : '' ?>>
                      <?= esc($d['doctors_name']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <!-- Day of Week -->
              <div class="col-md-6">
                <label class="form-label fw-semibold">Day of Week</label>
                <select name="day_of_week" class="form-select shadow-sm" required>
                  <?php 
                    $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
                    foreach ($days as $day): 
                  ?>
                    <option value="<?= $day ?>" 
                      <?= $avail['day_of_week'] == $day ? 'selected' : '' ?>>
                      <?= $day ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <!-- Shift -->
              <div class="col-md-6">
                <label class="form-label fw-semibold">Shift</label>
                <select name="shift_name" class="form-select shadow-sm">
                  <?php 
                    $shifts = ['Morning', 'Evening', 'Night']; 
                    foreach ($shifts as $shift): 
                  ?>
                    <option value="<?= $shift ?>" 
                      <?= $avail['shift_name'] == $shift ? 'selected' : '' ?>>
                      <?= $shift ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <!-- Start Time -->
              <div class="col-md-3">
                <label class="form-label fw-semibold">Start Time</label>
                <input type="time" name="start_time" class="form-control shadow-sm" 
                  value="<?= esc($avail['start_time']) ?>" required>
              </div>

              <!-- End Time -->
              <div class="col-md-3">
                <label class="form-label fw-semibold">End Time</label>
                <input type="time" name="end_time" class="form-control shadow-sm"
                  value="<?= esc($avail['end_time']) ?>" required>
              </div>

              <!-- Availability Toggle -->
              <div class="col-12 mt-2">
                <div class="form-check form-switch">
                  <input type="checkbox" name="is_available" class="form-check-input" 
                         id="availabilitySwitch" <?= $avail['is_available'] ? 'checked' : '' ?>>
                  <label for="availabilitySwitch" class="form-check-label fw-semibold">
                    Available for Appointments
                  </label>
                </div>
              </div>
            </div>
          </div>

          <div class="modal-footer bg-light rounded-bottom-4">
            <button type="submit" class="btn btn-success px-4">
              <i class="bi bi-check-circle me-1"></i> Update
            </button>
          <a href="<?= base_url('availability'); ?>" class="btn btn-secondary px-4">
  <i class="bi bi-x-circle me-1"></i> Close
</a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Auto open modal when page loads -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const modal = new bootstrap.Modal(document.getElementById('editModal'));
      modal.show();
    });
  </script>

</body>
</html>
