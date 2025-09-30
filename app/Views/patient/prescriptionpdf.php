<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Prescription</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; margin: 30px; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; }
        .header p { margin: 2px 0; font-size: 14px; }
        .info { margin-bottom: 20px; }
        .info p { margin: 4px 0; }
        h3 { margin-top: 20px; margin-bottom: 10px; border-bottom: 1px solid #000; padding-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; font-size: 14px; }
        th { background: #f2f2f2; }
        .footer { margin-top: 40px; text-align: right; font-size: 14px; }
    </style>
</head>
<body>

    <!-- Hospital Header -->
    <div class="header">
        <h1>Sandeep Hospital</h1>
        <p>AI Innovation center Gangamma layout Agrahara main road Kogilu Yelahanka - 560064</p>
        <p>Phone: +91 8050750015 | Email: nksandeep@citycare.com</p>
    </div>

    <!-- Patient & Doctor Info -->
    <div class="info">
        <p><strong>Patient Name:</strong> <?= esc($prescription['patient_name'] ?? 'N/A') ?></p>
        <p><strong>Doctor Name:</strong> <?= esc($prescription['doctor_name'] ?? 'N/A') ?></p>
        <p><strong>Appointment ID:</strong> <?= esc($prescription['appointment_id']) ?></p>
        <p><strong>Prescription ID:</strong> <?= esc($prescription['id']) ?></p>
        <p><strong>Date:</strong> <?= date('d M Y', strtotime($prescription['created_at']?? 'N/A')) ?></p>
    </div>

    <!-- Reason & Doctor Comments -->
    <div class="info">
        <p><strong>Reason for Visit:</strong> <?= esc($prescription['reason'] ?? 'N/A') ?></p>
        <p><strong>Doctor’s Comments:</strong> <?= esc($prescription['doctor_comments'] ?? 'N/A') ?></p>
    </div>

    <!-- Prescription Table -->
    <h3>Prescription Details</h3>
    <table>
        <thead>
            <tr>
                <th>Medicine Name</th>
                <th>Frequency</th>
                <th>Duration</th>
                <th>Instruction</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($medicines as $med): ?>
            <tr>
                <td><?= esc($med['medicine_name']) ?></td>
                <td><?= esc($med['frequency']) ?></td>
                <td><?= esc($med['duration']) ?></td>
                <td><?= esc($med['instruction']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>Doctor's Signature ____________________</p>
    </div>

</body>
</html>
