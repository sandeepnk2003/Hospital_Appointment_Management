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

<h2>Enter OTP</h2>
<form action="<?= base_url('/patient/verify-otp') ?>" method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="email" value="<?= esc($email) ?>">
    <input type="text" name="otp" placeholder="Enter OTP" required>
    <button type="submit">Verify OTP</button>
</form>

<!-- Just for testing -->
<p><strong>Test OTP:</strong> <?= esc($otp) ?></p>

                <p class="mt-3 mb-1 text-center">
                    <a href="#">Forgot password?</a>
                </p>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection() ?>
