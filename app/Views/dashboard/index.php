<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>
  <h1>Welcome, <?= session()->get('name') ?>!</h1>
  <p>You are logged in as <strong><?= session()->get('role') ?></strong>.</p>
<?= $this->endSection() ?>