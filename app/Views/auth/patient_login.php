<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Login<?= $this->endSection() ?>

<?= $this->section('content') ?>

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

                <h2>Patient Login</h2>
<form action="<?= base_url('/patient/login') ?>" method="post">
    <?= csrf_field() ?>
    <input type="email" name="email" placeholder="Enter your email" required>
    <button type="submit" class="btn btn-outline-success" style="margin:10px 2px">Send OTP</button>
</form>

<!-- Links Row -->
<div style="display: flex; justify-content: space-between; margin-top: 15px;">
    <a href="#" style="text-decoration: none;" type="button">Forgot password?</a>
    <a href="/patient/register" style="text-decoration: none;"> Patient_Registration</a>
</div>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection() ?>
