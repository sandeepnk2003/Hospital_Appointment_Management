<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctor Information</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border-radius: 15px;
        }
        .table th {
            background-color: #198754;
            color: white;
        }
        .status-available {
            color: green;
            font-weight: bold;
        }
        .status-unavailable {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body class="p-4">

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0 text-success">Doctor Information</h2>
            <a href="<?= base_url('patient/dashboard'); ?>" class="btn btn-secondary">Back</a>
        </div>

        <form method="get" action="<?= base_url('patient/doctoravailability'); ?>" class="mb-4">
    <div class="row g-2 align-items-center">
        <div class="col-md-4">
            <input type="text" name="doctor_name" value="<?= esc($doctor_name ?? '') ?>" 
                   class="form-control" placeholder="Search by Doctor Name">
        </div>
        <div class="col-md-4">
            <input type="text" name="specialization" value="<?= esc($specialization ?? '') ?>" 
                   class="form-control" placeholder="Search by Specialization">
        </div>
        <div class="col-md-4 d-flex">
            <button type="submit" class="btn btn-success me-2">Search</button>
            <a href="<?= base_url('patient/doctoravailability'); ?>" class="btn btn-secondary">Reset</a>
        </div>
    </div>
</form>
    <div class="card shadow-sm">
        <div class="card-body">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>

            <?php if (!empty($doctors) && is_array($doctors)): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-center">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Doctor Name</th>
                                <th>Specialization</th>
                                <th>Qualification</th>
                                <th>Experience (Years)</th>
                                <th>Consultation Fees (₹)</th>
                                <th>Check Availability</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($doctors as $doc): ?>
                                <tr>
                                    <td><?= esc($doc['id']) ?></td>
                                    <td><?= esc($doc['username']) ?></td>
                                    <td><?= esc($doc['specialization'] ?? '—') ?></td>
                                    <td><?= esc($doc['qualification'] ?? '—') ?></td>
                                    <td><?= esc($doc['experience'] ?? '—') ?></td>
                                    <td><?= esc($doc['consultation_fee'] ?? '—') ?></td>
                                    <td>
                                        <a href="<?= base_url('patient/doctor_availability/' . $doc['id']) ?>" class="btn btn-outline-success btn-sm">
                                            View Availability 
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted text-center">No doctors found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
