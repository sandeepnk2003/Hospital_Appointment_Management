<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Login<?= $this->endSection() ?>

<?= $this->section('content') ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<div class="hold-transition login-page" style="background: linear-gradient(135deg, #6c5ce7, #00b894); height: 100vh; display: flex; justify-content: center; align-items: center;">

    <div class="login-box">
        <div class="login-logo text-center" style="color: #fff; font-size: 2rem; font-weight: bold;">
            AppointmentPortal
        </div>

        <div class="card shadow-lg">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Sign in to start your session</p>

                <!-- Error Message -->
                <?php if(session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>

              <form action="<?= site_url('auth/login') ?>" method="post">

    <div class="mb-3">
        <label for="hospital_id" class="form-label fw-bold">Select Hospital</label>
        <select name="hospital_id" id="hospital_id" class="form-select" required>
            <option value="">Select Hospital</option>
            <?php foreach($hospitals as $hospital): ?>
                <option value="<?= $hospital['id'] ?>"><?= esc($hospital['hospital_name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="input-group mb-3">
        <input type="email" name="email" class="form-control" placeholder="Email" required>
    </div>

    <div class="input-group mb-3">
        <input type="password" name="password" class="form-control" placeholder="Password" required>
    </div>

    <div class="row">
        <div class="col-12 d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">Sign In</button>
            <a href="<?= base_url('/patient/login') ?>" 
               class="btn btn-outline-success fw-semibold rounded-pill px-4">Patient Login</a>
        </div>
    </div>

</form>

<!-- Activate Select2 -->
<script>
$(document).ready(function() {
    $('#hospital_id').select2({
        placeholder: "Search or select hospital...",
        allowClear: true,
        width: '100%'
    });
});
</script>

<!-- Search filter script -->
<!-- <script>
document.getElementById('hospitalSearch').addEventListener('keyup', function () {
    let filter = this.value.toLowerCase();
    let options = document.querySelectorAll('#hospitalSelect option');
    options.forEach(option => {
        let text = option.textContent.toLowerCase();
        option.style.display = text.includes(filter) ? '' : 'none';
    });
});
</script> -->


                <p class="mt-3 mb-1 text-center">
                    <a href="#">Forgot password?</a>
                    <!-- <a href="/">Select Hospitals</a> -->
                </p>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection() ?>
