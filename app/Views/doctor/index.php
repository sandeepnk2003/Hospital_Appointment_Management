<!DOCTYPE html>
<html>
<head>
    <title>Doctors List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<div class="container">
    <h2 class="mb-4">Doctors List</h2>
    <a href="<?= base_url('/dashboard'); ?>" class="btn btn-secondary mb-3">Back</a>



    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Specialization</th>
                <th>Qualification</th>
                <th>Experience</th>
                <th>Consultation Fee</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($doctors)): ?>
                <?php foreach ($doctors as $doctor): ?>
                    <tr>
                        <td><?= esc($doctor['username']) ?></td>
                        <td><?= esc($doctor['email']) ?></td>
                        <td><?= esc($doctor['specialization']) ?></td>
                        <td><?= esc($doctor['qualification']) ?></td>
                        <td><?= esc($doctor['experience']) ?> years</td>
                        <td>₹<?= esc($doctor['consultation_fee']) ?></td>
                        <td>
                            <a href="<?= base_url('doctors/edit/'.$doctor['doctor_id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="<?= base_url('doctors/delete/'.$doctor['id']) ?>" 
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Are you sure you want to delete this doctor?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">No doctors found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
