<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Billing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table td input { width: 100%; }
        .total-row { font-weight: bold; }
    </style>
</head>
<body class="p-4 bg-light">
<div class="container">
    <div class="card shadow-lg rounded-4">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Patient Billing</h4>
        </div>
        <div class="card-body">

            <!-- Patient & Doctor Info -->
            <form method="post" action="<?= base_url('payments/save') ?>">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Patient Name</label>
                        <input type="text" name="patient_name" class="form-control" value="<?= esc($patient_name ?? '') ?>" readonly>
                        <input type="hidden" name="patient_id" value="<?= esc($patient_id ?? '') ?>">
                        <input type="hidden" name="hospital_id" value="<?= esc($hospital_id ?? '') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Doctor</label>
                        <input type="text" name="doctor_name" class="form-control" value="<?= esc($doctor_name ?? '') ?>" readonly>
                        <input type="hidden" name="doctor_id" value="<?= esc($doctor_id ?? '') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Appointment ID</label>
                        <input type="text" name="appointment_id" class="form-control" value="<?= esc($id ?? '') ?>" readonly>
                    </div>
                </div>

                <hr>

                <!-- Billing Items -->
                <h5>Bill Details</h5>
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Price (₹)</th>
                            <th>Total (₹)</th>
                            <!-- <th>Action</th> -->
                        </tr>
                    </thead>
                    <tbody id="billingItems">
                        <!-- Default: Doctor Consultation Fee -->
                        <tr>
                            <td><input type="text" name="items[0][item_name]" class="form-control" value="Consultation Fee" readonly></td>
                            <td><input type="number" name="items[0][quantity]" class="form-control qty" value="1" min="1"></td>
                            <td><input type="number" name="items[0][price]" class="form-control price" value="<?= 
                            esc($consultation_fee ?? 0) ?>" readonly></td>
                            <td><input type="text" class="form-control total" readonly></td>
                            <!-- <td></td> -->
                        </tr> 
                    </tbody>
                </table>

                <div class="text-end mb-3">
                    <button type="button" class="btn btn-success" id="addItem">+ Add Item</button>
                </div>

                <hr>

                <!-- Grand Total -->
                <div class="d-flex justify-content-between align-items-center">
                    <h5>Total Amount:</h5>
                    <h4 class="text-primary mb-0">₹ <span id="grandTotal">0.00</span></h4>
                    <input type="hidden" name="total_amount" id="hiddenTotal">
                </div>

                <hr>

                <!-- Payment Method -->
                <!-- <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Payment Mode</label>
                        <select name="payment_mode" class="form-select" required>
                            <option value="cash">Cash</option>
                            <option value="upi">UPI</option>
                            <option value="card">Card</option> -->
                            <!-- <option value="razorpay">Razorpay</option>
                            <option value="stripe">Stripe</option> -->
                        <!-- </select>
                    </div> -->
                    <div class="col-md-8">
                        <label class="form-label">Notes</label>
                        <input type="text" name="notes" class="form-control" placeholder="Any remarks">
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary px-4">Save & Generate Bill</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    let tableBody = document.getElementById('billingItems');
    let addItemBtn = document.getElementById('addItem');
    let itemIndex = 1;

    function updateTotals() {
        let rows = tableBody.querySelectorAll('tr');
        let grandTotal = 0;

        rows.forEach(row => {
            let qty = parseFloat(row.querySelector('.qty').value) || 0;
            let price = parseFloat(row.querySelector('.price').value) || 0;
            let total = qty * price;
            row.querySelector('.total').value = total.toFixed(2);
            grandTotal += total;
        });

        document.getElementById('grandTotal').innerText = grandTotal.toFixed(2);
        document.getElementById('hiddenTotal').value = grandTotal.toFixed(2);
    }

    // Initial calculation
    updateTotals();

    tableBody.addEventListener('input', updateTotals);


    addItemBtn.addEventListener('click', function() {
        let newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td><input type="text" name="items[${itemIndex}][item_name]" class="form-control" placeholder="Item Name"></td>
            <td><input type="number" name="items[${itemIndex}][quantity]" class="form-control qty" value="1" min="1"></td>
            <td><input type="number" name="items[${itemIndex}][price]" class="form-control price" value="0"></td>
            <td><input type="text" class="form-control total" readonly></td>
            <td><button type="button" class="btn btn-danger btn-sm remove-item">x</button></td>
        `;
        tableBody.appendChild(newRow);
        itemIndex++;
        updateTotals();
    });

    tableBody.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item')) {
            e.target.closest('tr').remove();
            updateTotals();
        }
    });
});
</script>
</body>
</html>
