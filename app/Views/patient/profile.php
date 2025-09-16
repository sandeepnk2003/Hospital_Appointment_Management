<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-primary text-white text-center">
          <h4 class="mb-0">My Profile</h4>
        </div><!DOCTYPE html>
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
        <h2>Profile</h2>
       
    </div>

    <!-- Error Message -->
    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error'); ?>
        </div>
    <?php endif; ?>

        <div class="card-body p-4">
          <div class="mb-3">
            <label class="form-label fw-bold">Name</label>
            <div class="form-control bg-light"><?= esc($patient['name']) ?></div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold">Email</label>
            <div class="form-control bg-light"><?= esc($patient['email']) ?></div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold">Phone</label>
            <div class="form-control bg-light"><?= esc($patient['phone']) ?></div>
          </div>
             <div class="mb-3">
            <label class="form-label fw-bold">date Of Birth</label>
            <div class="form-control bg-light"><?= esc($patient['dob']) ?></div>
          </div>
             <div class="mb-3">
            <label class="form-label fw-bold">Gender</label>
            <div class="form-control bg-light"><?= esc($patient['gender']) ?></div>
          </div>
        </div>
        <div class="card-footer text-center bg-white">
          <a href="<?= base_url('patients/edit/'.session()->get('patient_id')) ?>" class="btn btn-warning px-4">
            <i class="bi bi-pencil-square"></i> Edit Profile
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>