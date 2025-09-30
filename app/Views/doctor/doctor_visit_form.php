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


                <div class="mb-3">
                    <label class="form-label">Reason for Visit</label>
                    <textarea name="reason" class="form-control" rows="2" required><?= old('reason') ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Weight (kg)</label>
                    <input type="text" name="weight" class="form-control" value="<?= old('weight') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Blood Pressure</label>
                    <input type="text" name="blood_pressure" class="form-control" placeholder="e.g. 120/80" value="<?= old('blood_pressure') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Doctor’s Comments</label>
                    <textarea name="doctor_comments" class="form-control" rows="3" required><?= old('doctor_comments') ?></textarea>
                </div>

                <button type="submit" class="btn btn-success">Save & Complete</button>
                <a href="<?= base_url('doctor_dashboard') ?>" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
