<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Book Appointment</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-primary text-white text-center">
          <h4>Book an Appointment</h4>
        </div>
        
        <?php if(session()->getFlashdata('error')): ?>
          <div class="alert alert-danger"><?= session()->getFlashdata('error'); ?></div>
        <?php endif; ?>

        <div class="card-body p-4">
          <form method="post" action="<?= base_url('patient/book/save') ?>">

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

            <!-- Doctor -->
            <div class="mb-3">
              <label for="doctor_id" class="form-label fw-bold">Select Doctor</label>
              <select name="doctor_id" id="doctor_id" class="form-select" required>
                <option value="">-- Choose Doctor --</option>
              </select>
            </div>

            <!-- Date -->
            <div class="mb-3">
              <label for="date" class="form-label fw-bold">Appointment Date</label>
              <input type="date" name="date" id="date" class="form-control" required>
            </div>

            <!-- Time -->
            <div class="mb-3">
              <label for="time" class="form-label fw-bold">Appointment Time</label>
              <input type="time" name="time" id="time" class="form-control" required>
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-success">Book Appointment</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
  // When hospital is selected
  $('#hospital_id').on('change', function() {
    var hospitalId = $(this).val();

    // Clear doctor list
    $('#doctor_id').html('<option value="">-- Choose Doctor --</option>');

    if (hospitalId) {
      $.ajax({
        url: "<?= base_url('patient/getDoctorsByHospital') ?>",
        type: "POST",
        data: { hospital_id: hospitalId },
        dataType: "json",
        success: function(doctors) {
          if (doctors.length > 0) {
            $.each(doctors, function(index, doctor) {
              $('#doctor_id').append('<option value="'+doctor.id+'">'+doctor.name+' ('+doctor.specialization+')</option>');
            });
          } else {
            $('#doctor_id').append('<option value="">No doctors available</option>');
          }
        }
      });
    }
  });
});
</script>

</body>
</html>
