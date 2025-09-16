<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Add User</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-6">
      <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-primary text-white text-center rounded-top-4">
          <h3 class="mb-0"> Add New User</h3>
        </div>
        <div class="card-body p-4">
          <form action="<?= base_url('users/store') ?>" method="post">
            
            <!-- Username -->
            <div class="mb-3">
              <label class="form-label fw-semibold">👤 Username</label>
              <input type="text" name="username" class="form-control form-control-lg rounded-3" placeholder="Enter username" required>
            </div>

            <!-- Email -->
            <div class="mb-3">
              <label class="form-label fw-semibold">📧 Email</label>
              <input type="email" name="email" class="form-control form-control-lg rounded-3" placeholder="Enter email" required>
            </div>

            <!-- Role -->
            <div class="mb-3">
              <label class="form-label fw-semibold">🎭 Role</label>
              <select name="role" class="form-select form-select-lg rounded-3">
                <option value="superadmin">SuperAdmin</option>
                <option value="admin">Admin</option>
                <option value="doctor">Doctor</option>
              </select>
            </div>

            <!-- Password -->
            <div class="mb-3">
              <label class="form-label fw-semibold">🔑 Password</label>
              <input type="password" name="password" class="form-control form-control-lg rounded-3" placeholder="Enter password" required>
            </div>

             <div class="mb-3">
              <label class="form-label fw-semibold">Phone no</label>
              <input type="text" name="phoneno" id="phoneno" class="form-control form-control-lg rounded-3" placeholder="Enter phoneno" required>
            </div>

            <!-- Buttons -->
            <div class="d-flex justify-content-between mt-4">
              <a href="<?= base_url('users') ?>" class="btn btn-outline-secondary btn-lg px-4 rounded-3">Cancel</a>
              <button type="submit" class="btn btn-success btn-lg px-4 rounded-3">💾 Save User</button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
