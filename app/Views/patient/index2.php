<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Patient Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">

<div class="container py-4">

  <a href="<?= base_url('/doctor_dashboard'); ?>" class="btn btn-secondary mb-3">Back</a>

  <!-- Patient Info -->
  <div class="card mb-4 shadow-sm">
      <div class="card-body d-flex align-items-center">
          <img src="https://via.placeholder.com/100" alt="Profile" class="rounded-circle me-3">
          <div>
              <h5 class="card-title mb-1"><?= esc($patient['name']) ?></h5>
              <p class="mb-0 text-muted">
                DOB: <?= esc($patient['dob']) ?> | Gender: <?= esc($patient['gender']) ?>
              </p>
              <p class="mb-0 text-muted">Contact: <?= esc($patient['phone']) ?></p>
          </div>
      </div>
  </div>

  <!-- Visit History -->
  <div class="card mb-4 shadow-sm">
      <div class="card-body">
          <h5 class="card-title">Visit History</h5>
          <div class="accordion" id="visitAccordion">

              <?php if(!empty($visits)): ?>
                  <?php foreach ($visits as $index => $visit): ?>
                      <div class="accordion-item mb-2">
                          <h2 class="accordion-header" id="heading<?= $index ?>">
                              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $index ?>">
                                  <?= date('d M Y', strtotime($visit['date'])) ?> - Visit #<?= esc($visit['id']) ?>
                              </button>
                          </h2>

                          <div id="collapse<?= $index ?>" class="accordion-collapse collapse" data-bs-parent="#visitAccordion">
                              <div class="accordion-body">
                                  <p><strong>Hospital Name:</strong> <?= esc($visit['hospital_name']) ?></p>
                                  <p><strong>Doctor Name:</strong> <?= esc($visit['doctor_name']) ?></p>
                                  <p><strong>Date:</strong> <?= esc(date('d M Y', strtotime($visit['date']))) ?></p>
                                  <p><strong>Weight:</strong> <?= esc($visit['weight']) ?> kg</p>
                                  <p><strong>Blood Pressure:</strong> <?= esc($visit['blood_pressure']) ?></p>

                                  <?php if (!empty($visit['reasons'])): ?>
                                      <h6 class="mt-3">Reasons & Diagnoses:</h6>
                                      <ul class="list-group mb-3">
                                          <?php foreach ($visit['reasons'] as $reason): ?>
                                              <li class="list-group-item">
                                                  <strong>Reason:</strong> <?= esc($reason['reason']) ?><br>
                                                  <strong>Diagnosis:</strong> <?= esc($reason['diagnosis']) ?>
                                              </li>
                                          <?php endforeach; ?>
                                      </ul>
                                  <?php endif; ?>
                              </div>
                          </div>
                      </div>
                  <?php endforeach; ?>
              <?php else: ?>
                  <p class="text-muted">No visits recorded yet.</p>
              <?php endif; ?>

          </div>
      </div>
  </div>

  <!-- Charts -->
  <div class="row">
      <div class="col-md-6 mb-4">
          <div class="card shadow-sm">
              <div class="card-body">
                  <h5 class="card-title">Weight Trend</h5>
                  <canvas id="weightChart"></canvas>
              </div>
          </div>
      </div>
      <div class="col-md-6 mb-4">
          <div class="card shadow-sm">
              <div class="card-body">
                  <h5 class="card-title">Blood Pressure Trend</h5>
                  <canvas id="bpChart"></canvas>
              </div>
          </div>
      </div>
  </div>

</div>

<!-- ChartJS -->
<script>
  const labels = <?= json_encode(array_column($visits, 'date')) ?>;
  const weights = <?= json_encode(array_column($visits, 'weight')) ?>;
  const bpData = <?= json_encode(array_column($visits, 'blood_pressure')) ?>;

  const systolic = bpData.map(v => parseInt(v.split('/')[0]));
  const diastolic = bpData.map(v => parseInt(v.split('/')[1]));

  new Chart(document.getElementById('weightChart'), {
    type: 'line',
    data: { labels, datasets: [{ label: 'Weight (kg)', data: weights, borderColor: 'blue', backgroundColor: 'rgba(0,0,255,0.1)', tension: 0.3 }] }
  });

  new Chart(document.getElementById('bpChart'), {
    type: 'line',
    data: { labels, datasets: [
      { label: 'Systolic (mmHg)', data: systolic, borderColor: 'red', backgroundColor: 'rgba(255,0,0,0.1)', tension: 0.3 },
      { label: 'Diastolic (mmHg)', data: diastolic, borderColor: 'green', backgroundColor: 'rgba(0,255,0,0.1)', tension: 0.3 }
    ]}
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
