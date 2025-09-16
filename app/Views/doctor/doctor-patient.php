<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Patient Management</h2>
        <div>
            <a href="<?= base_url('/doctor_dashboard'); ?>" class="btn btn-secondary me-2">Back</a>
            <a href="<?= base_url('patients/create'); ?>" class="btn btn-success">+ Add Patient</a>
        </div>
    </div>



    <!-- Success Message -->
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success'); ?>
        </div>
    <?php endif; ?>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Patient Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>DOB</th>
                <th>Gender</th>
                <th>Created</th>
                <th>Updated</th>
                <th width="180">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($patients)): ?>
                <?php foreach($patients as $patient): ?>
                    <tr>
                        <td><?= $patient['id']; ?></td>
                        <td><?= esc($patient['name']); ?></td>
                        <td><?= esc($patient['email']); ?></td>
                        <td><?= esc($patient['phone']); ?></td>
                        <td><?= esc($patient['dob']); ?></td>
                        <td><?= esc($patient['gender']); ?></td>
                        <td><?= esc($patient['created_at']); ?></td>
                        <td><?= esc($patient['updated_at']); ?></td>
                        <td>
                            <a href="<?= base_url('patients/edit/'.$patient['id']); ?>" class="btn btn-sm btn-primary">Edit</a>
                            <a href="<?= base_url('patients/delete/'.$patient['id']); ?>" 
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Are you sure you want to delete this patient?');">
                               Delete
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" class="text-center">No patients found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
