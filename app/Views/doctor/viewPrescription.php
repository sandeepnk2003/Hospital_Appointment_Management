<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Prescription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">
    <div class="card shadow p-4">
        <h3 class="mb-3">Prescription #<?= esc($prescription['id']) ?></h3>

        <!-- Basic Info -->
        <div class="mb-3">
            <p><strong>Patient:</strong> <?= esc($prescription['patient_name']) ?></p>
            <p><strong>Doctor:</strong> <?= esc($prescription['doctor_name']) ?></p>
        </div>

        <!-- Flash Message -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <!-- Medicines List -->
        <h5 class="mb-3">Medicines</h5>

        <?php if (!empty($medicines)): ?>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th>#</th>
                            <th>Medicine Name</th>
                            <th>Frequency</th>
                            <th>Duration</th>
                            <th>Instruction</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($medicines as $index => $m): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= esc($m['medicine_name']) ?></td>
                                <td><?= esc($m['frequency']) ?></td>
                                <td><?= esc($m['duration']) ?></td>
                                <td><?= esc($m['instruction']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-muted">No medicines added to this prescription.</p>
        <?php endif; ?>

        <!-- Buttons -->
        <div class="mt-3">
            <a href="<?= base_url('doctor_dashboard') ?>" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>
</div>

</body>
</html>
