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
        <?php if (session()->get('role') === 'doctor'): ?>
          <li class="nav-item">
            <a href="<?= base_url('doctors/patients/'.$id)?>" class="nav-link">
              <i class="bi bi-heart"></i> My Patients
            </a>
          </li>
           <?php endif; ?>
          <?php if (session()->get('role') === 'doctor'): ?>
          <li class="nav-item">
            <a href="<?= base_url('doctors/patients/'.$id)?>" class="nav-link">
              <i class="bi bi-gear"></i>  Settings
            </a>
          </li>
        <?php endif; ?>
      </ul>
    </div> <!-- ❌ Sidebar ends properly here -->

    <!-- Main Content -->
    <div class="col-md-9 col-lg-10 content">
      <div class="container">
        <h1 class="mb-4">Welcome, <?= session()->get('username') ?>!</h1>   

        <h2>Doctor Dashboard</h2>

        <div class="row mb-4">
          <div class="col-md-4">
            <div class="card text-center border-primary">
              <div class="card-body">
                <h5 class="text-primary">Today</h5>
                <p class="h4"><?= $todayAppointmentsCount; ?> Appointments</p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card text-center border-success">
              <div class="card-body">
                <h5 class="text-success">This Week</h5>
                <p class="h4"><?= $weekAppointmentsCount; ?> Appointments</p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card text-center border-warning">
              <div class="card-body">
                <h5 class="text-warning">This Month</h5>
                <p class="h4"><?= $monthAppointmentsCount; ?> Appointments</p>
              </div>
            </div>
          </div>
        </div>

       <h4>Today’s Appointments</h4>
<?php if (count($todayAppointments) > 0): ?>
  <table class="table table-bordered mt-3">
    <thead>
      <tr>
        <th>Patient</th>
        <th>Start Time</th>
        <th>End Time</th>
        <th>Status</th>
        <th>Actions</th> <!-- 👈 New column -->
      </tr>
    </thead>
    <tbody>
      <?php foreach ($todayAppointments as $appt): ?>
        <tr>
          <td><?= esc($appt['patient_name']); ?></td>
          <td><?= date('d/m/Y H:i', strtotime($appt['start_datetime'])); ?></td>
          <td><?= date('d/m/Y H:i', strtotime($appt['end_datetime'])); ?></td>
          <td><?= esc($appt['status']); ?></td>
         <td>
    <?php if ($appt['status'] === 'Scheduled'): ?>
        <a href="<?= base_url('doctors/visit/'.$appt['id']); ?>" 
           class="btn btn-success btn-sm">
           <i class="bi bi-check-circle"></i> Complete
        </a>
        <a href="<?= base_url('appointments/cancel/'.$appt['id']); ?>" 
           class="btn btn-danger btn-sm">
           <i class="bi bi-x-circle"></i> Cancel
        </a>
         <?php elseif ($appt['status'] === 'Completed'): ?>
           <span class="btn btn-success btn-sm">
           <i class="bi bi-check-circle"></i> Completed</span>
         <?php else :?>
           <span class="btn btn-danger btn-sm">
           <i class="bi bi-check-circle"></i> Cancelled</span>
    <?php endif; ?>
    <a href="<?= base_url('doctors/dashboard2/'.$appt['patient_id']); ?>" 
           class="btn btn-success btn-sm">
           👪 Patient_info
        </a>
         <?php if ($appt['status'] === 'Completed'): ?>
         <a href="<?= base_url('doctors/prescription/'.$appt['id']); ?>" 
           class="btn btn-success btn-sm">
           👪 Patient Prescription
        </a>
        <?php endif; ?>
</td>

        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php else: ?>
  <p class="text-muted mt-3">No appointments scheduled today.</p>
<?php endif; ?>
      </div>
    </div> <!-- ✅ Main content ends properly -->
  </div>
</div>
<!-- <script>
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".edit-status-btn").forEach(button => {
        button.addEventListener("click", function() {
            let row = this.closest("tr");
            let apptId = this.getAttribute("data-id");

            row.querySelector("td:last-child").innerHTML = `
                <a href="<?= base_url('doctors/visit/'); ?>${apptId}" 
                   class="btn btn-success btn-sm">
                   <i class="bi bi-check-circle"></i> Complete
                </a>
                <a href="<?= base_url('appointments/cancel/'); ?>${apptId}" 
                   class="btn btn-danger btn-sm">
                   <i class="bi bi-x-circle"></i> Cancel
                </a>
            `;
        });
    });
});
</script> -->


    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-2 mt-auto">
    &copy; <?= date('Y') ?> Appointment Portal
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
