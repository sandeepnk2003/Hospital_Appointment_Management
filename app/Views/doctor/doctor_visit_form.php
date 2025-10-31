<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Complete Visit - <?= $appointment['patient_name'] ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">
  <div class="card shadow-sm">
    <div class="card-body">
      <h4 class="card-title mb-3">Complete Appointment - <?= $appointment['patient_name'] ?></h4>

      <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
      <?php endif; ?>

      <form method="post" action="<?= base_url('doctors/visit/save') ?>">
        <input type="hidden" name="appointment_id" value="<?= $appointment['id'] ?>">
        <input type="hidden" name="patient_id" value="<?= $appointment['patient_id'] ?>">
        <input type="hidden" name="doctor_id" value="<?= $appointment['doctor_id'] ?>">
        <input type="hidden" name="hospital_id" value="<?= $appointment['hospital_id'] ?>">

        <div id="reason-group">
          <div class="reason-item border rounded p-3 mb-3 bg-light-subtle">
            <div class="mb-2">
              <label class="form-label">Reason for Visit</label>
              <textarea name="reason[]" class="form-control" rows="2" required></textarea>
            </div>
            <div class="mb-2">
              <label class="form-label">Diagnosis</label>
              <textarea name="diagnosis[]" class="form-control" rows="2" required></textarea>
            </div>
            <button type="button" class="btn btn-danger btn-sm remove-reason">Remove</button>
          </div>
        </div>

        <button type="button" id="add-reason" class="btn btn-outline-primary mb-3">
          + Add Another Reason
        </button>

        <div class="mb-3">
          <label class="form-label">Weight (kg)</label>
          <input type="text" name="weight" class="form-control" value="<?= old('weight') ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Blood Pressure</label>
          <input type="text" name="blood_pressure" class="form-control" placeholder="e.g. 120/80" value="<?= old('blood_pressure') ?>" required>
        </div>

        <button type="submit" class="btn btn-success">Save & Complete</button>
        <a href="<?= base_url('doctor_dashboard') ?>" class="btn btn-secondary">Cancel</a>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('add-reason').addEventListener('click', function () {
  const container = document.getElementById('reason-group');
  const item = document.querySelector('.reason-item').cloneNode(true);
  item.querySelectorAll('textarea').forEach(t => t.value = '');
  container.appendChild(item);
});

document.addEventListener('click', function (e) {
  if (e.target.classList.contains('remove-reason')) {
    const all = document.querySelectorAll('.reason-item');
    if (all.length > 1) e.target.closest('.reason-item').remove();
  }
});
</script>

</body>
</html>
