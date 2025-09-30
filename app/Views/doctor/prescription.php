<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Prescription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">
    <h2 class="mb-4">Add Prescription</h2>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <form action="<?= base_url('doctors/prescriptions/store') ?>" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="appointment_id" value="<?= esc($appointmentId) ?>">

        <!-- Notes -->
        

        <!-- Medicines Section -->
        <h5>Medicines</h5>
        <div id="medicines">
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
                <!-- <div class="col-md-4">
            <label for="notes" class="form-label">General Notes / Instructions</label>
            <textarea name="notes" id="notes" class="form-control" rows="1"></textarea>
        </div> -->
                <div class="col-md-1 d-flex align-items-center">
                    <button type="button" class="btn btn-danger btn-sm remove-medicine d-none">×</button>
                </div>
            </div>
        </div>

        <!-- Add Medicine Button -->
        <button type="button" id="addMedicine" class="btn btn-secondary btn-sm mb-3">
            + Add Medicine
        </button>

        <!-- Submit -->
        <div>
            <button type="submit" class="btn btn-primary">Save Prescription</button>
            <a href="<?= base_url('appointments/' . $appointmentId) ?>" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div>

<script>
    let medicineIndex = 1;

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

    // Remove medicine row
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-medicine')) {
            e.target.closest('.medicine-row').remove();
        }
    });
</script>

</body>
</html>
