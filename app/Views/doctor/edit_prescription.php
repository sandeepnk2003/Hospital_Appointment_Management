<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Prescription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">
    <div class="card shadow p-4">
        <h3 class="mb-3">Edit Prescription #<?= esc($prescription['id']) ?></h3>

        <div class="mb-3">
            <p><strong>Patient:</strong> <?= esc($prescription['patient_name']) ?></p>
            <p><strong>Doctor:</strong> <?= esc($prescription['doctor_name']) ?></p>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <form method="post" action="<?= base_url('doctors/prescriptions/'.$prescription['id']) ?>">
            <h5 class="mb-3">Medicines</h5>

            <div id="medicines">
                <?php if (!empty($medicines)): ?>
                    <?php foreach ($medicines as $index => $m): ?>
                        <div class="row g-2 medicine-row mb-2">
                            <input type="hidden" name="medicines[<?= $index ?>][id]" value="<?= $m['id'] ?>">
                            <div class="col-md-3">
                                <input type="text" name="medicines[<?= $index ?>][medicine_name]" class="form-control"
                                       value="<?= esc($m['medicine_name']) ?>" placeholder="Medicine Name" required>
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="medicines[<?= $index ?>][frequency]" class="form-control"
                                       value="<?= esc($m['frequency']) ?>" placeholder="Frequency" required>
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="medicines[<?= $index ?>][duration]" class="form-control"
                                       value="<?= esc($m['duration']) ?>" placeholder="Duration">
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="medicines[<?= $index ?>][instruction]" class="form-control"
                                       value="<?= esc($m['instruction']) ?>" placeholder="Instruction">
                            </div>
                            <div class="col-md-1 d-flex align-items-center">
                                <button type="button" class="btn btn-danger btn-sm remove-medicine">×</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="row g-2 medicine-row mb-2">
                        <div class="col-md-3">
                            <input type="text" name="medicines[0][medicine_name]" class="form-control" placeholder="Medicine Name" required>
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="medicines[0][frequency]" class="form-control" placeholder="Frequency" required>
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="medicines[0][duration]" class="form-control" placeholder="Duration">
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="medicines[0][instruction]" class="form-control" placeholder="Instruction">
                        </div>
                        <div class="col-md-1 d-flex align-items-center">
                            <button type="button" class="btn btn-danger btn-sm remove-medicine d-none">×</button>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Add Medicine Button -->
            <button type="button" id="addMedicine" class="btn btn-secondary btn-sm mb-3">
                + Add Medicine
            </button>

            <!-- Submit Buttons -->
            <div>
                <button type="submit" class="btn btn-primary">Update Prescription</button>
                <a href="<?= base_url('doctor_dashboard') ?>" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
    let medicineIndex = <?= count($medicines ?? [0]) ?>;

    document.getElementById('addMedicine').addEventListener('click', function () {
        const medicinesDiv = document.getElementById('medicines');

        const row = document.createElement('div');
        row.classList.add('row', 'g-2', 'medicine-row', 'mb-2');
        row.innerHTML = `
            <div class="col-md-3">
                <input type="text" name="medicines[${medicineIndex}][medicine_name]" class="form-control" placeholder="Medicine Name" required>
            </div>
            <div class="col-md-2">
                <input type="text" name="medicines[${medicineIndex}][frequency]" class="form-control" placeholder="Frequency" required>
            </div>
            <div class="col-md-2">
                <input type="text" name="medicines[${medicineIndex}][duration]" class="form-control" placeholder="Duration">
            </div>
            <div class="col-md-4">
                <input type="text" name="medicines[${medicineIndex}][instruction]" class="form-control" placeholder="Instruction">
            </div>
            <div class="col-md-1 d-flex align-items-center">
                <button type="button" class="btn btn-danger btn-sm remove-medicine">×</button>
            </div>
        `;
        medicinesDiv.appendChild(row);
        medicineIndex++;
    });

    // Remove medicine row from UI (not DB yet)
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-medicine')) {
            e.target.closest('.medicine-row').remove();
        }
    });
</script>

</body>
</html>
