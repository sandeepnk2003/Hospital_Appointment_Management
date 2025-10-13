<!DOCTYPE html>
<html>
<head>
    <title>Appointments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Appointment Management</h2>
   <div>
            <a href="<?= base_url('/doctor_dashboard'); ?>" class="btn btn-secondary me-2">Back</a>
            <a href="<?= base_url('appointments/create'); ?>" class="btn btn-success">+ Add appointment</a>
        </div>
</div>


    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success'); ?></div>
    <?php endif; ?>
      <!-- 🔍 Search and Filter Row -->
        <!-- <div class="row mb-3">
          <div class="col-md-6"> -->
            <!-- Search Bar -->
            <!-- <div class="input-group">
              <span class="input-group-text bg-white">
                <i class="bi bi-search"></i>
              </span>
              <input type="text" id="searchInput" class="form-control" placeholder="Search appointments, doctors, patients...">
            </div>
          </div>
          <div class="col-md-6"> -->
            <!-- Date Filter -->
            <!-- <div class="input-group">
              <span class="input-group-text bg-white">
                <i class="bi bi-calendar-event"></i>
              </span>
              <input type="date" id="dateFilter" class="form-control">
              <button class="btn btn-primary" id="filterBtn">Filter</button>
            </div>
          </div>
        </div> -->

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Patient</th>
                <th>Start</th>
                <th>End</th>
                <th>Status</th>
                <th colspan="2"><center>Action</center></th>
                <th>prescription</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($appointments)): ?>
                <?php foreach($appointments as $a): ?>
                    <tr>
                        <td><?= $a['id']; ?></td>
                        <td><?= esc($a['patient_name']); ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($a['start_datetime'])); ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($a['end_datetime'])); ?></td>
                        <td><?= esc($a['status']); ?></td>
                        
                <td class="text-center" colspan="2">
                  <?php if ($a['status'] === 'Scheduled'): ?>
                    <a href="<?= base_url('appointments/cancel/' . $a['id']); ?>" 
                       class="btn btn-danger btn-sm">
                      Cancel
                    </a>
                     <a href="<?= base_url('patients/appointments/reschedule/'. $a['id']); ?>" 
                       class="btn btn-success btn-sm">
                      Reshedule
                    </a>
                     <!-- <a href="<?= base_url('patients/appointments/update/'. $a['id']); ?>" 
                       class="btn btn-success btn-sm">
                      Completed
                    </a> -->
                  <?php elseif ($a['status'] === 'Cancelled'): ?>
                    
                       <span class="btn btn-sm btn-success">
                      <i class="bi bi-arrow-repeat"></i> Cancelled
                  </span>
                   <?php else: ?>
                    
                       <span class="btn btn-sm btn-success">
                      <i class="bi bi-arrow-repeat"></i> Completed
                  </span>
                  <?php endif; ?>
                </td>
                <!-- <td></td> -->
                <td>
                 <?php if ($a['status'] === 'Completed'): ?>
         <a href="<?= base_url('doctors/prescriptions/view/'.$a['prescription_id']); ?>" 
           class="btn btn-success btn-sm">
           👪 Patient Prescription
        </a>
        <?php endif; ?>
                 </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" class="text-center">No appointments found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
  document.getElementById('filterBtn').addEventListener('click', function() {
    let search = document.getElementById('searchInput').value;
    let date = document.getElementById('dateFilter').value;

    // Preserve the filter param from URL if it's already set
    let urlParams = new URLSearchParams(window.location.search);
    let filter = urlParams.get('filter') || '';

    let newUrl = "<?= base_url('appointments') ?>?search=" + encodeURIComponent(search) + "&date=" + encodeURIComponent(date);

    if (filter) {
      newUrl += "&filter=" + filter;
    }

    window.location.href = newUrl;
  });
</script>

</body>
</html>
