<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Book Appointment</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-primary text-white text-center">
          <h4>Book an Appointment</h4>
        </div>
        
        <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error'); ?></div>
    <?php endif; ?>
        <div class="card-body p-4">
          <form method="post" action="<?= base_url('patient/book/save') ?>">
            
            <!-- Doctor -->
            <div class="mb-3">
              <label for="doctor_id" class="form-label">Select Doctor</label>
              <select name="doctor_id" id="doctor_id" class="form-select" required>
                <option value="">-- Choose Doctor --</option>
                <?php foreach ($doctors as $doc): ?>
                  <option value="<?= $doc['id'] ?>">
                    <?= esc($doc['name']) ?> (<?= esc($doc['specialization']) ?>)
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <!-- Date -->
            <div class="mb-3">
              <label for="date" class="form-label">Appointment Date</label>
              <input type="date" name="date" id="date" class="form-control" required>
            </div>

            <!-- Time -->
            <div class="mb-3">
              <label for="time" class="form-label">Appointment Time</label>
              <input type="time" name="time" id="time" class="form-control" required>
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-success">Book Appointment</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
