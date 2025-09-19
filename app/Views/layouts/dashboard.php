<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Appointment Portal | Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    .sidebar {
      min-height: 100vh;
      background-color: #343a40;
    }
    .sidebar .nav-link {
      color: #adb5bd;
    }
    .sidebar .nav-link.active {
      background-color: #495057;
      color: #fff;
    }
    .content {
      flex-grow: 1;
      padding: 20px;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Appointment Portal</a>
    <div class="d-flex">
      <span class="navbar-text text-white me-3">
        Logged in as <strong><?= session()->get('role') ?></strong>
      </span>
      <a href="<?= base_url('logout') ?>" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-3 col-lg-2 sidebar p-3">
      <h5 class="text-white">Menu</h5>
      <ul class="nav flex-column">
        <li class="nav-item">
          <a href="<?= base_url('dashboard') ?>" class="nav-link active">
            <i class="bi bi-speedometer2"></i> Dashboard
          </a>
        </li>
        <?php if (session()->get('role') === 'superadmin'): ?>
          <li class="nav-item"><a href="<?= base_url('users/') ?>" class="nav-link"><i class="bi bi-people"></i> Manage Users</a></li>
        <?php endif; ?>

        <?php if (session()->get('role') === 'admin' || session()->get('role') === 'superadmin'): ?>
          <li class="nav-item"><a href="<?= base_url('doctors') ?>" class="nav-link"><i class="bi bi-person-badge"></i> Manage Doctors</a></li>
          <li class="nav-item"><a href="<?= base_url('patients') ?>" class="nav-link"><i class="bi bi-heart"></i> Manage Patients</a></li>
        <?php endif; ?>

        <?php if (session()->get('role') === 'doctor'): ?>
          <li class="nav-item"><a href="<?= base_url('patients') ?>" class="nav-link"><i class="bi bi-heart"></i> My Patients</a></li>
        <?php endif; ?>

        <?php if (session()->get('role') !== 'patient'): ?>
          <li class="nav-item"><a href="<?= base_url('appointments') ?>" class="nav-link"><i class="bi bi-calendar-check"></i> Appointments</a></li>
        <?php endif; ?>
      </ul>
    </div>

    <!-- Content -->
    <div class="col-md-9 col-lg-10 content">
      <div class="container">
        <h1 class="mb-4">Welcome, <?= session()->get('username') ?>!</h1>

       
        <!-- First row: stats -->
        <div class="row g-3">
          <div class="col-md-4">
  <a href="<?= base_url('doctors') ?>" class="text-decoration-none text-reset">
    <div class="card text-bg-primary h-100">
      <div class="card-body">
        <h5 class="card-title">Doctors</h5>
        <p class="card-text fs-4"><?= $doctorCount ?? 0 ?></p>
      </div>
    </div>
  </a>
</div>

          <div class="col-md-4">
            <a href="<?= base_url('patients') ?>" class="text-decoration-none text-reset">
            <div class="card text-bg-success">
              <div class="card-body">
                <h5 class="card-title">Patients</h5>
                <p class="card-text fs-4"><?= $patientCount ?? 0 ?></p>
              </div>
            </div>
        </a>
          </div>
          <div class="col-md-4">
            <a href="<?= base_url('appointments') ?>" class="text-decoration-none text-reset">
            <div class="card text-bg-warning">
              <div class="card-body">
                <h5 class="card-title">Appointments</h5>
                <p class="card-text fs-4"><?= $totalAppointments ?? 0 ?></p>
              </div>
            </div>
        </a>
          </div>
        </div>

        <!-- Second row: appointments summary -->
       <div class="row g-3 mt-3">
  <div class="col-md-4">
    <div class="card border-primary position-relative">
      <div class="card-body">
        <h6 class="card-title text-primary"><i class="bi bi-calendar-day"></i> Today</h6>
        <p class="card-text fs-5"><?= $todayAppointments ?? 0 ?> Appointments</p>
        <a href="/appointments?filter=today" class="stretched-link"></a>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card border-success position-relative">
      <div class="card-body">
        <h6 class="card-title text-success"><i class="bi bi-calendar-week"></i> This Week</h6>
        <p class="card-text fs-5"><?= $weekAppointments ?? 0 ?> Appointments</p>
        <a href="/appointments?filter=week" class="stretched-link"></a>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card border-warning position-relative">
      <div class="card-body">
        <h6 class="card-title text-warning"><i class="bi bi-calendar-month"></i> This Month</h6>
        <p class="card-text fs-5"><?= $monthAppointments ?? 0 ?> Appointments</p>
        <a href="/appointments?filter=month" class="stretched-link"></a>
      </div>
    </div>
  </div>
</div>

      </div>
    </div>
  </div>
</div>

<!-- Footer -->
<footer class="bg-dark text-white text-center py-2 mt-auto">
  &copy; <?= date('Y') ?> Appointment Portal
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- <script>
  document.getElementById('filterBtn').addEventListener('click', function() {
    let search = document.getElementById('searchInput').value;
    let date = document.getElementById('dateFilter').value;

    // Redirect to appointments page with query params
    window.location.href = "<?= base_url('appointments') ?>?search=" + encodeURIComponent(search) + "&date=" + encodeURIComponent(date);
  });
</script> -->
</body>
</html>
