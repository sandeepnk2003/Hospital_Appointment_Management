<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<h2>Manage Users</h2>

<a href="<?= base_url('/users/create'); ?>" class="btn btn-success mb-3">Add New User</a>
<a href="<?= base_url('/dashboard'); ?>" class="btn btn-secondary mb-3">Back</a>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?= esc($user['id']) ?></td>
            <td><?= esc($user['username']) ?></td>
            <td><?= esc($user['email']) ?></td>
            <td><?= esc($user['role']) ?></td>
            <td>
                <a href="<?= base_url('users/edit/'.$user['id']); ?>" class="btn btn-primary btn-sm">Edit</a>
                <a href="<?= base_url('users/delete/'.$user['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete user?')">Delete</a>

                <?php if ($user['role'] === 'doctor' && !in_array($user['id'], $doctorUserIds)): ?>
                    <a href="<?= base_url('users/add_from_user/'.$user['id']); ?>" class="btn btn-warning btn-sm">Add Doctor Info</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
