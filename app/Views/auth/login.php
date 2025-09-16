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

                <form action="<?= site_url('auth/login') ?>" method="post">
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                        <div class="input-group-append">
                            <!-- <div class="input-group-text"><span class="fas fa-envelope"></span></div>
                        </div> -->
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                        <!-- <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-lock"></span></div>
                        </div> -->
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block btn-flat" >Sign In</button>
                             <a href="<?= base_url('/patient/login') ?>" 
   class="btn btn-outline-success"
   style="margin-left:10px; font-weight:600; border-radius:25px; padding:8px 20px; transition:0.3s;">Patient Login</a>
                        </div>
                    </div>
                </form>

                <p class="mt-3 mb-1 text-center">
                    <a href="#">Forgot password?</a>
                </p>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection() ?>
