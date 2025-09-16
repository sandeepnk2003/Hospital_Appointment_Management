<!DOCTYPE html>
<html>
<head>
    <title>Appointments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Appointment Management</h2>
   <div>
            <a href="<?= base_url('/dashboard'); ?>" class="btn btn-secondary me-2">Back</a>
            <a href="<?= base_url('appointments/create'); ?>" class="btn btn-success">+ Add appointment</a>
        </div>
</div>
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success'); ?></div>
    <?php endif; ?>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Doctor</th>
                <th>Patient</th>
                <th>Start</th>
                <th>End</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($appointments)): ?>
                <?php foreach($appointments as $a): ?>
                    <tr>
                        <td><?= $a['id']; ?></td>
                        <td><?= esc($a['doctor_name']); ?></td>
                        <td><?= esc($a['patient_name']); ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($a['start_datetime'])); ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($a['end_datetime'])); ?></td>
                        <td><?= esc($a['status']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" class="text-center">No appointments found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
