<!DOCTYPE html>
<html>
<head>
    <title>Create Appointment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Create Appointment</h2>
    <a href="<?= base_url('appointments'); ?>" class="btn btn-secondary mb-3">Back</a>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error'); ?></div>
    <?php endif; ?>

  <form action="<?= base_url('appointments/create'); ?>" method="post">
    <?= csrf_field() ?>

    <!-- Doctor Dropdown -->
    <div class="mb-3">
        <label for="doctor_id" class="form-label">Doctor</label>
        <select name="doctor_id" id="doctor_id" class="form-control" required>
            <option value="">Select Doctor</option>
            <?php foreach($doctors as $doctor): ?>
                <option value="<?= $doctor['id']; ?>">
                    <?= esc($doctor['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Patient Dropdown -->
    <div class="mb-3">
        <label for="patient_id" class="form-label">Patient</label>
        <select name="patient_id" id="patient_id" class="form-control" required>
            <option value="">Select Patient</option>
            <?php foreach($patients as $patient): ?>
                <option value="<?= $patient['id']; ?>">
                    <?= esc($patient['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Start Date & Time -->
    <div class="mb-3">
        <label for="start_time" class="form-label">Start Date & Time</label>
        <input type="datetime-local" name="start_time" id="start_time" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary">Submit Appointment</button>
</form>

</div>
</body>
</html>
