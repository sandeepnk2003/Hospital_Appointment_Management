<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Patient</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Add New Patient</h2>
        <a href="<?= base_url('patient'); ?>" class="btn btn-secondary">Back</a>
    </div>

    <!-- Error Message -->
    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error'); ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('patients/create'); ?>" method="post">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label for="name" class="form-label">Patient Name</label>
            <input type="text" name="name" id="name" class="form-control" 
                   value="<?= old('name'); ?>" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" name="email" id="email" class="form-control" 
                   value="<?= old('email'); ?>" required>
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Phone Number</label>
            <input type="text" name="phone" id="phone" class="form-control" 
                   value="<?= old('phone'); ?>" required>
        </div>

        <div class="mb-3">
            <label for="dob" class="form-label">Date of Birth</label>
            <input type="date" name="dob" id="dob" class="form-control" 
                   value="<?= old('dob'); ?>" required>
        </div>

        <div class="mb-3">
            <label for="gender" class="form-label">Gender</label>
            <select name="gender" id="gender" class="form-control" required>
                <option value="">Select Gender</option>
                <option value="male" <?= old('gender') == 'Male' ? 'selected' : '' ?>>Male</option>
                <option value="female" <?= old('gender') == 'Female' ? 'selected' : '' ?>>Female</option>
                <option value="other" <?= old('gender') == 'Other' ? 'selected' : '' ?>>Other</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Save Patient</button>
    </form>
</div>
</body>
</html>
