<!DOCTYPE html>
<html>
<head>
    <title>Doctors List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<div class="container">
    <h2 class="mb-4">Paymet Management</h2>
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
                <th>appointment_id</th>
                <th>Doctor Name</th>
                <th>Patient Name</th>
                <th>Total Amount</th>
                <th>payment Mode</th>
                <th>Payment Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($payments)): ?>
                <?php foreach ($payments as $doctor): ?>
                    <tr>
                        <td><?= esc($doctor['appointment_id']) ?></td>
                        <td><?= esc($doctor['doctor_name']) ?></td>
                        <td><?= esc($doctor['patient_name']) ?></td>
                        <td>₹<?= esc($doctor['total_amount']) ?></td>
                        <td><?= esc($doctor['payment_mode']) ?></td>
                        <td><?= esc($doctor['payment_status']) ?></td>
                        <td >
                         <?php if (($doctor['payment_status']=='pending')): ?>
            <!-- ✅ No prescription or billing yet -->
            <a href="<?= base_url('payments/success/' .$doctor['id']); ?>" class="btn btn-danger btn-sm">
            Complete
            </a>
            <?php else:?>
               <button class="btn btn-primary btn-sm"> <?= esc($doctor['payment_status']) ?></button> 
            <?php endif; ?>
         

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
