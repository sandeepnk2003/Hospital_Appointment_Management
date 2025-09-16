<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit User</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #74ebd5 0%, #ACB6E5 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .card {
      border-radius: 1rem;
    }
    .form-control, .form-select {
      border-radius: 0.5rem;
    }
    .btn {
      border-radius: 0.5rem;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
      <div class="card shadow-lg">
        <div class="card-header bg-primary text-white text-center">
          <h4 class="mb-0"><i class="bi bi-pencil-square"></i> Edit User</h4>
        </div>
        <div class="card-body p-4">

          <!-- Validation Errors -->
          <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
          <?php endif; ?>
          <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
          <?php endif; ?>

          <!-- Edit Form -->
          <form action="<?= base_url('users/update/' . $user['id']) ?>" method="post">
            <?= csrf_field() ?>

            <!-- Username -->
            <div class="mb-3">
              <label class="form-label fw-semibold"><i class="bi bi-person"></i> Username</label>
              <input type="text" name="username" value="<?= esc($user['username']) ?>" 
                     class="form-control form-control-lg" required>
            </div>

            <!-- Email -->
            <div class="mb-3">
              <label class="form-label fw-semibold"><i class="bi bi-envelope"></i> Email</label>
              <input type="email" name="email" value="<?= esc($user['email']) ?>" 
                     class="form-control form-control-lg" required>
            </div>

            <!-- Role -->
            <div class="mb-3">
              <label class="form-label fw-semibold"><i class="bi bi-person-badge"></i> Role</label>
              <select id="roleSelect" name="role" class="form-select form-select-lg">
                <option value="admin" <?= $user['role'] === 'superadmin' ? 'selected' : '' ?>>SuperAdmin</option>
              <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="doctor" <?= $user['role'] === 'doctor' ? 'selected' : '' ?>>Doctor</option>
                <!-- <option value="patient" <?= $user['role'] === 'patient' ? 'selected' : '' ?>>Patient</option> -->
                <!-- <option value="other" <?= !in_array($user['role'], ['admin','doctor','patient']) ? 'selected' : '' ?>>Other...</option> -->
              </select>

              <!-- Custom role input -->
              <!-- <input type="text" id="customRole" name="custom_role"
                     class="form-control form-control-lg mt-2 <?= !in_array($user['role'], ['admin','doctor',]) ? '' : 'd-none' ?>"
                     value="<?= !in_array($user['role'], ['admin','doctor',]) ? esc($user['role']) : '' ?>"
                     placeholder="Enter custom role"> -->
            </div>
 <div class="mb-3">
              <label class="form-label fw-semibold"><i class="bi bi-envelope"></i> Phoneno</label>
              <input type="email" name="phoneno" value="<?= esc($user['phoneno']) ?>" 
                     class="form-control form-control-lg" required>
            </div>
            <!-- Password -->
            <!-- <div class="mb-3">
              <label class="form-label fw-semibold"><i class="bi bi-key"></i> Password</label>
              <input type="password" name="password" class="form-control form-control-lg"
                     placeholder="Leave blank to keep current">
            </div> -->

            <!-- Buttons -->
            <div class="d-flex justify-content-between mt-4">
              <a href="<?= base_url('users') ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back
              </a>
              <button type="submit" class="btn btn-success px-4">
                <i class="bi bi-save"></i> Update
              </button>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>

<!-- <script>
  const roleSelect = document.getElementById('roleSelect');
  const customRole = document.getElementById('customRole');

  function toggleCustomRole() {
    if (roleSelect.value === 'other') {
      customRole.classList.remove('d-none');
      customRole.setAttribute('required', true);
    } else {
      customRole.classList.add('d-none');
      customRole.removeAttribute('required');
    }
  }

  roleSelect.addEventListener('change', toggleCustomRole);
</script> -->

</body>
</html>
