<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctor Availability</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .accordion-button:not(.collapsed) {
            background-color: #198754;
            color: #fff;
        }
        .accordion-button:focus {
            box-shadow: none;
        }
        .table th {
            background-color: #e9ecef;
        }
    </style>
</head>
<body class="p-4">
    <div class="container">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0 text-success">Doctor Availability</h2>
            <a href="<?= base_url('patient/doctorinfo'); ?>" class="btn btn-secondary">Back</a>
        </div>

        <!-- Flash message -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <!-- Accordion by Day -->
        <div class="accordion" id="dayAccordion">
            <?php if (!empty($days)): ?>
                <?php foreach ($days as $index => $day): ?>
                    <div class="accordion-item mb-2">
                        <h2 class="accordion-header" id="heading<?= $index ?>">
                            <button class="accordion-button collapsed" type="button" 
                                    data-bs-toggle="collapse" 
                                    data-bs-target="#collapse<?= $index ?>" 
                                    aria-expanded="false" aria-controls="collapse<?= $index ?>">
                                <?= esc($day) ?>
                            </button>
                        </h2>

                        <div id="collapse<?= $index ?>" 
                             class="accordion-collapse collapse" 
                             aria-labelledby="heading<?= $index ?>" 
                             data-bs-parent="#dayAccordion">

                            <div class="accordion-body">
                                <?php if (!empty($availabilities[$day])): ?>
                                    <table class="table table-bordered table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Hospital Name</th>
                                                <th>Doctor</th>
                                                <th>Shift</th>
                                                <th>Start Time</th>
                                                <th>End Time</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($availabilities[$day] as $a): ?>
                                                <tr>
                                                    <td><?= esc($a['hospital_name']) ?></td>
                                                    <td><?= esc($a['doctors_name']) ?></td>
                                                    <td><?= esc($a['shift_name']) ?></td>
                                                    <td><?= esc($a['start_time']) ?></td>
                                                    <td><?= esc($a['end_time']) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php else: ?>
                                    <p class="text-muted mb-0">No availability records for this day.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted">No days or availability data found.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
