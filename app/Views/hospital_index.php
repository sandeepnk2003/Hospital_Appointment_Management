<!DOCTYPE html>
<html>
<head>
  <title>Select Hospital</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">
  <div class="card shadow p-4" style="width: 500px;">
    <h4 class="mb-4 text-center">Select Your Hospital</h4>

    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <form action="<?= base_url('select-hospital') ?>" method="post">
      <div class="mb-3">
        <?php foreach ($hospitals as $hospital): ?>
          <div class="form-check mb-2">
            <input class="form-check-input" type="radio" name="hospital_id" id="h<?= $hospital['id'] ?>" value="<?= $hospital['id'] ?>">
            <label class="form-check-label" for="h<?= $hospital['id'] ?>">
              <?= esc($hospital['hospital_name']) ?>
              <!-- <small class="text-muted">(<?= esc($hospital['hospital_email']) ?>)</small> -->
            </label>
          </div>
        <?php endforeach; ?>
      </div>

      <button type="submit" class="btn btn-primary w-100">Next</button>
    </form>
  </div>
</body>
</html>
