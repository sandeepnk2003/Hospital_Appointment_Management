<!DOCTYPE html>
<html>
<head>
    <title>Doctor Availability</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Doctor Availability</h2>
    <a href="<?= base_url('patient/dashboard'); ?>" class="btn btn-secondary">Back</a>
</div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="accordion" id="dayAccordion">
        <?php foreach ($days as $index => $day): ?>
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading<?= $index ?>">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $index ?>">
                        <?= $day ?>
                    </button>
                </h2>
                <div id="collapse<?= $index ?>" class="accordion-collapse collapse" data-bs-parent="#dayAccordion">
                    <div class="accordion-body">

                        <!-- List existing availabilities -->
                        <?php if (!empty($availabilities[$day])): ?>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Doctor</th>
                                        <th>Specialization</th>
                                        <th>Shift</th>
                                        <th>Available(from)</th>
                                        <th>To</th>
                                        <!-- <th>Available</th>
                                        <th>Action</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($availabilities[$day] as $a): ?>
                                        <tr>
                                            <td><?= esc($a['doctors_name']) ?></td>
                                            <td><?= esc($a['doctorSpecalization']) ?></td>
                                            <td><?= esc($a['shift_name']) ?></td>
                                            <td><?= esc($a['start_time']) ?></td>
                                            <td><?= esc($a['end_time']) ?></td>                
                                </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p class="text-muted">No availability added for <?= $day ?> yet.</p>
                        <?php endif; ?>

                        <!-- Add button (opens modal)
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal" onclick="setDay('<?= $day ?>')">
                            Add Availability
                        </button> -->
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>



<script>
function setDay(day) {
    document.getElementById("dayField").value = day;
}
</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
