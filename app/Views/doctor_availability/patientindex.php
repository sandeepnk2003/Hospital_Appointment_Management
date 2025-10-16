<!DOCTYPE html>
<html>
<head>
  <title>Doctor Availability</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="p-4 bg-light">
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Doctor Availability</h2>
    <a href="<?= base_url('patient/dashboard'); ?>" class="btn btn-secondary">Back</a>
  </div>

  <!-- Success message -->
  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
  <?php endif; ?>

  <!-- Hospital Selection -->
  <div class="mb-3">
    <label for="hospital_id" class="form-label fw-bold">Select Hospital</label>
    <select name="hospital_id" id="hospital_id" class="form-select" required>
      <option value="">-- Select Hospital --</option>
      <?php foreach ($hospitals as $hospital): ?>
        <option value="<?= $hospital['id'] ?>"><?= esc($hospital['hospital_name']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>

  <!-- Availability Section -->
  <div id="availabilityContainer">
    <div class="alert alert-info text-center">Please select a hospital to view doctor availability.</div>
  </div>
</div>

<script>
$(document).ready(function() {
  $('#hospital_id').on('change', function() {
    let hospital_id = $(this).val();

    if (hospital_id === '') {
      $('#availabilityContainer').html('<div class="alert alert-info text-center">Please select a hospital to view doctor availability.</div>');
      return;
    }

    $.ajax({
      url: "<?= base_url('patient/getAvailabilityByHospital') ?>", // your controller function
      type: "POST",
      data: {
        hospital_id: hospital_id,
        <?= csrf_token() ?>: "<?= csrf_hash() ?>" // if CSRF is enabled
      },
      dataType: "json",
      beforeSend: function() {
        $('#availabilityContainer').html('<div class="text-center p-4"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Loading availability...</p></div>');
      },
      success: function(response) {
        // Build the accordion dynamically
        let html = '<div class="accordion" id="dayAccordion">';
        let hasData = false;

        $.each(response, function(day, availabilities) {
          html += `
            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#${day.replace(/\s/g, '')}">
                  ${day}
                </button>
              </h2>
              <div id="${day.replace(/\s/g, '')}" class="accordion-collapse collapse" data-bs-parent="#dayAccordion">
                <div class="accordion-body">
          `;

          if (availabilities.length > 0) {
            hasData = true;
            html += `
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Doctor</th>
                    <th>Specialization</th>
                    <th>Shift</th>
                    <th>Available From</th>
                    <th>To</th>
                  </tr>
                </thead>
                <tbody>
            `;
            $.each(availabilities, function(i, a) {
              html += `
                <tr>
                  <td>${a.doctors_name}</td>
                  <td>${a.doctorSpecalization}</td>
                  <td>${a.shift_name}</td>
                  <td>${a.start_time}</td>
                  <td>${a.end_time}</td>
                </tr>
              `;
            });
            html += `</tbody></table>`;
          } else {
            html += `<p class="text-muted">No availability added for ${day}.</p>`;
          }

          html += `</div></div></div>`;
        });

        html += '</div>';

        if (!hasData) {
          html = '<div class="alert alert-warning text-center">No availability found for this hospital.</div>';
        }

        $('#availabilityContainer').html(html);
      },
      error: function(xhr, status, error) {
        console.log(xhr.responseText);
        $('#availabilityContainer').html('<div class="alert alert-danger text-center">Something went wrong. Please try again.</div>');
      }
    });
  });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
