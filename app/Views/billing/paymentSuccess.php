<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light p-4">

<div class="container">
    <div class="card shadow-lg rounded-4">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="bi bi-credit-card"></i> Payment Summary</h4>
            <span class="badge bg-dark">#<?= esc($payment['id']) ?></span>
        </div>

        <div class="card-body">
            <form action="<?= base_url('payments/markCompleted/'.$payment['id']); ?>" method="post">
                <div class="mb-3">
                    <p class="mb-1"><strong><i class="bi bi-currency-rupee"></i> Amount:</strong> ₹<?= esc($payment['total_amount']) ?></p>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold"><i class="bi bi-wallet2"></i> Payment Mode</label>
                    <select name="payment_mode" class="form-select w-50" required>
                        <option value="cash">Cash</option>
                        <option value="upi">UPI</option>
                        <option value="card">Card</option>
                    </select>
                </div>

                <div class="mb-3">
                    <strong><i class="bi bi-info-circle"></i> Status:</strong>
                    <?php if ($payment['payment_status'] === 'completed'): ?>
                        <span class="badge bg-success ms-2"><i class="bi bi-check-circle"></i> Completed</span>
                    <?php else: ?>
                        <span class="badge bg-warning text-dark ms-2"><i class="bi bi-hourglass-split"></i> Pending</span>
                    <?php endif; ?>
                </div>

                <div class="mt-4">
                    <?php if ($payment['payment_status'] === 'pending'): ?>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Mark as Payment Completed
                        </button>
                        <a href="<?= base_url('/payments') ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    <?php else: ?>
                        <a href="<?= base_url('billing/invoice/' . $payment['id']) ?>" class="btn btn-success">
                            <i class="bi bi-file-earmark-text"></i> Download Invoice
                        </a>
                        <a href="<?= base_url('/payments') ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
