<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Payment Slip #<?= esc($payment->payment_id) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
  body {
    font-family: DejaVu Sans, Arial, sans-serif;
    color: #222;
    margin: 40px;
    font-size: 14px;
  }
  .invoice-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
  }
  .invoice-header h2 {
    margin: 0;
    font-weight: 600;
    color: #198754;
  }
  table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
  }
  th, td {
    padding: 8px;
    border-bottom: 1px solid #ddd;
  }
  th {
    background: #f8f9fa;
    text-align: left;
  }
  .total-row {
    font-weight: bold;
    background: #f1f1f1;
  }
  .text-right { text-align: right; }
  .note {
    font-size: 13px;
    color: #555;
    margin-top: 10px;
  }
  hr {
    margin: 15px 0;
  }
</style>
</head>
<body>

<!-- Header -->
<div class="text-center mb-3">
  <h2><?= esc($payment->hospital_name) ?></h2>
</div>

<div class="invoice-header">
  <div>
    <strong>Hospital Contact:</strong> <?= esc($payment->hospital_contact) ?><br>
    <strong>Hospital Email:</strong> <?= esc($payment->hospital_email) ?>
  </div>
  <div class="text-end">
    <strong>Payment Slip #<?= esc($payment->payment_id) ?></strong><br>
    Date: <?= esc(date('d M Y', strtotime($payment->created_at))) ?><br>
    Status: <span class="text-success fw-bold"><?= ucfirst($payment->payment_status) ?></span>
  </div>
</div>

<hr>

<!-- Patient Details -->
<div>
  <strong>Patient:</strong> <?= esc($payment->patient_name) ?><br>
  <strong>Contact:</strong> <?= esc($payment->patient_contact) ?><br>
  <strong>Payment Mode:</strong> <?= ucfirst($payment->payment_mode) ?>
</div>

<!-- Item Table -->
<table>
  <thead>
    <tr>
      <th>#</th>
      <th>Item Name</th>
      <th>Qty</th>
      <th>Price (₹)</th>
      <th class="text-right">Total (₹)</th>
    </tr>
  </thead>
  <tbody>
    <?php 
      $grandTotal = 0; 
      foreach ($items as $i => $item): 
        $grandTotal += $item['total'];
    ?>
    <tr>
      <td><?= $i+1 ?></td>
      <td><?= esc($item['item_name']) ?></td>
      <td><?= esc($item['quantity']) ?></td>
      <td><?= number_format($item['price'], 2) ?></td>
      <td class="text-right"><?= number_format($item['total'], 2) ?></td>
    </tr>
    <?php endforeach; ?>
    <tr class="total-row">
      <td colspan="4" class="text-right">Grand Total</td>
      <td class="text-right"><?= number_format($grandTotal, 2) ?></td>
    </tr>
  </tbody>
</table>

<!-- Notes -->
<p class="note">
  <?= esc($payment->notes ?? '') ?><br><br>
  This is a computer-generated slip — no signature required.
</p>

</body>
</html>
