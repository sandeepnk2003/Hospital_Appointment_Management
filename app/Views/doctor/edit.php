<!-- app/Views/doctor/create.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Doctor Info</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Card -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Add Doctor Info</h4>
                </div>
                <div class="card-body">

                    <!-- Flash Messages -->
                    <?php if(session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                    <?php endif; ?>
                    <?php if(session()->getFlashdata('success')): ?>
                        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                    <?php endif; ?>

                    <!-- User Info -->
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" value="<?= esc($doctor['username']) ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" value="<?= esc($doctor['email']) ?>" readonly>
                    </div>

                    <!-- Doctor Form -->
                    <form method="post" action="<?= base_url('doctors/edit/'.$doctor['doctor_id']) ?>">
                        <div class="mb-3">
                            <label class="form-label">Specialization</label>
                            <input type="text" name="specialization" class="form-control" value="<?= esc($doctor['specialization']) ?>" placeholder="e.g., Cardiologist" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Qualification</label>
                            <input type="text" name="qualification" class="form-control" value="<?= esc($doctor['qualification']) ?>" placeholder="e.g., MBBS, MD" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Experience (Years)</label>
                            <input type="number" name="experience" class="form-control" value="<?= esc($doctor['experience']) ?>" placeholder="e.g., 5" min="0" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Consultation Fee (₹)</label>
                            <input type="number" name="consultation_fee" class="form-control" value="<?= esc($doctor['consultation_fee']) ?>" placeholder="e.g., 500" min="0" required>
                        </div>

                      <button type="submit" class="btn btn-primary w-100">Update Doctor Info</button>
                    </form>

                    <div class="mt-3">
                        <a href="<?= base_url('doctors/') ?>" class="btn btn-secondary w-100">Back to doctor List</a>
                    </div>

                </div>
            </div>
            <!-- /Card -->
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
