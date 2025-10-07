<!DOCTYPE html>
<html>
<head>
    <title>Doctor Availability</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
    <h2 class="mb-4">Doctor Availability</h2>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="accordion" id="dayAccordion">
        <?php foreach ($days as $index => $day): ?>
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading<?= $index ?>">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $index ?>">
                        <?= $day ?>
                    </button>
                </h2>
                <div id="collapse<?= $index ?>" class="accordion-collapse collapse" data-bs-parent="#dayAccordion">
                    <div class="accordion-body">

                        <!-- List existing availabilities -->
                        <?php if (!empty($availabilities[$day])): ?>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Doctor</th>
                                        <th>Shift</th>
                                        <th>Start</th>
                                        <th>End</th>
                                        <th>Available</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($availabilities[$day] as $a): ?>
                                        <tr>
                                            <td><?= esc($a['doctors_name']) ?></td>
                                            <td><?= esc($a['shift_name']) ?></td>
                                            <td><?= esc($a['start_time']) ?></td>
                                            <td><?= esc($a['end_time']) ?></td>
                                           <td>
    <div class="form-check form-switch">
        <input class="form-check-input toggle-availability" 
               type="checkbox" 
               data-id="<?= $a['id'] ?>" 
               <?= $a['is_available'] ? 'checked' : '' ?>>
    </div>
</td>
                                            <td>
                                                <a href="availability/edit/<?= $a['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                                <a href="availability/delete/<?= $a['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p class="text-muted">No availability added for <?= $day ?> yet.</p>
                        <?php endif; ?>

                        <!-- Add button (opens modal) -->
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal" onclick="setDay('<?= $day ?>')">
                            Add Availability
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="availability/store" method="post">
                <div class="modal-header">
                    <h5 class="modal-title">Add Availability</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="day_of_week" id="dayField">

                    <div class="mb-3">
                        <label>Doctor</label>
                        <select name="doctor_id" class="form-control" required>
                            <?php foreach ($doctors as $d): ?>
                                <option value="<?= $d['id'] ?>"><?= esc($d['doctors_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Shift</label>
                        <select name="shift_name" class="form-control">
                            <option value="Morning">Morning</option>
                            <option value="Evening">Evening</option>
                            <option value="Night">Night</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Start Time</label>
                        <input type="time" name="start_time" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>End Time</label>
                        <input type="time" name="end_time" class="form-control" required>
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" name="is_available" class="form-check-input" checked>
                        <label class="form-check-label">Is Available?</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function setDay(day) {
    document.getElementById("dayField").value = day;
}
</script>
<script>
document.querySelectorAll('.toggle-availability').forEach(function(toggle) {
    toggle.addEventListener('change', function() {
        const id = this.getAttribute('data-id');
        const isAvailable = this.checked ? 1 : 0;

        fetch(`availability/toggle/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            },
            body: JSON.stringify({is_available: isAvailable})
        })
        .then(response => response.json())
        .then(data => {
            if (data.status !== 'success') {
                alert('Failed to update availability');
                this.checked = !this.checked; // revert toggle
            }
        })
        .catch(err => {
            alert('Error updating availability');
            this.checked = !this.checked; // revert toggle
        });
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
