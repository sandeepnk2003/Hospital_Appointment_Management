<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Prescription Details</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">
    <h2 class="mb-4">Prescription Details</h2>

    <div class="card mb-3">
        <div class="card-body">
            <h5>Patient: <?= esc($prescription['patient_name']) ?></h5>
            <p>Doctor: <?= esc($prescription['doctor_name']) ?></p>
            <p>Prescription ID: <?= esc($prescription['id']) ?></p>
            <p>Date: <?= date('d M Y', strtotime($prescription['created_at'])) ?></p>
        </div>
    </div>

    <h4>Medicines</h4>

    <?php if (count($medicines) > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Medicine Name</th>
                    <th>Frequencu</th>
                    <th>Duration</th>
                    <th>Instructions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($medicines as $m): ?>
                    <tr>
                        <td><?= esc($m['medicine_name']) ?></td>
                        <td><?= esc($m['frequency']) ?></td>
                        <td><?= esc($m['duration']) ?></td>
                        <td><?= esc($m['instruction']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-muted">No medicines added yet.</p>
    <?php endif; ?>

    <a href="<?= base_url('doctors/add_medicine/'.$prescription['id']) ?>" class="btn btn-primary mt-3">
        ➕ Add More Medicine
    </a>
</div>

</body>
</html>
