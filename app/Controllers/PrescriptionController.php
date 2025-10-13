<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Dompdf\Dompdf;
use App\Models\PrescriptionModel;
use App\Models\PrescriptionMedicineModel;
use App\Models\HospitalModel;

class PrescriptionController extends ResourceController
{
   public function download($id)
{
    $prescriptionModel = new PrescriptionModel();
    $medicineModel     = new PrescriptionMedicineModel();

    // Fetch prescription header
    $prescription = $prescriptionModel
        ->select('prescription.*,visits.reason,visits.doctor_comments,users.username as doctor_name, patients.name as patient_name')
        ->join('appointments', 'appointments.id = prescription.appointment_id')
        ->join('doctors','appointments.doctor_id=doctors.id')
        ->join('users','doctors.userid=users.id')
        ->join('patients','appointments.patient_id=patients.id')
        ->join('visits','appointments.id=visits.appointment_id')
        ->find($id);

    if (!$prescription) {
        return redirect()->back()->with('error', 'Prescription not found.');
    }

    // Fetch medicines
    $medicines = $medicineModel->where('prescription_id', $id)->findAll();

    // Load HTML view for PDF
    $html = view('patient/prescriptionpdf', [
        'prescription' => $prescription,
        'medicines'    => $medicines
    ]);

    // Generate PDF
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Send PDF to browser
    return $dompdf->stream("prescription_$id.pdf", ["Attachment" => true]);
}
   public function patientPrescription($appointmentId){
    return view('doctor/prescription',[
        'appointmentId' => $appointmentId
    ]);
   }
   public function prescriptionStore()
{
    $db = \Config\Database::connect();
    $db->transStart();

    $prescriptionModel = new PrescriptionModel();
    $medicineModel     = new PrescriptionMedicineModel();

    try {
        // Step 1: Save prescription header
        $prescriptionData = [
            'hospital_id'=>session('hospital_id'),
            'appointment_id' => $this->request->getPost('appointment_id'),
            'created_at'     => date('Y-m-d H:i:s'),
            'updated_at'     => date('Y-m-d H:i:s')
        ];

        $prescriptionModel->insert($prescriptionData);
        $prescriptionId = $prescriptionModel->getInsertID();

        // Step 2: Save multiple medicines
        $medicines = $this->request->getPost('medicines'); // <-- FIXED
        foreach ($medicines as $med) {
            $medicineModel->insert([
                'hospital_id'=>session('hospital_id'),
                'prescription_id' => $prescriptionId,
                'medicine_name'   => $med['medicine_name'],
                'frequency'       => $med['frequency'],
                'duration'        => $med['duration'],
                'instruction'     => $med['instruction']
            ]);
        }

        $db->transComplete();

        return redirect()->to('doctor_dashboard')
                         ->with('success', 'Prescription added successfully.');
    } catch (\Exception $e) {
        $db->transRollback();
        return redirect()->back()->with('error', 'Failed to save prescription: ' . $e->getMessage());
    }
}
public function displayPrescription($id){
//    public function edit($prescriptionId)
// {
    $prescriptionModel = new \App\Models\PrescriptionModel();
    $medicineModel = new PrescriptionMedicineModel();

    // Get main prescription info
    $prescription = $prescriptionModel
        ->select('prescription.*, patients.name as patient_name, users.username as doctor_name')
        ->join('appointments','prescription.appointment_id=appointments.id')
        ->join('patients','patients.id=appointments.patient_id')
        ->join('doctors','doctors.id=appointments.doctor_id')
        ->join('users','users.id=doctors.userId')
        ->where('prescription.id', (int)$id)
        ->first();

    // Get all medicines for this prescription
    $medicines = $medicineModel
        ->where('prescription_id', $id)
        ->findAll();

    return view('doctor/edit_prescription', [
        'prescription' => $prescription,
        'medicines'    => $medicines
    ]);
}
public function update($prescriptionId=null)
{
    $medicineModel = new PrescriptionMedicineModel();

    $medicines = $this->request->getPost('medicines');

    if (!empty($medicines)) {
        foreach ($medicines as $med) {
            if (!empty($med['id'])) {
                // Update existing medicine
                $medicineModel->update($med['id'], [
                    'medicine_name' => $med['medicine_name'],
                    'frequency'     => $med['frequency'],
                    'duration'      => $med['duration'],
                    'instruction'   => $med['instruction']
                ]);
            } else {
                // Insert new medicine
                $medicineModel->insert([
                    'hospital_id'=>session('hospital_id'),
                    'prescription_id' => $prescriptionId,
                    'medicine_name'   => $med['medicine_name'],
                    'frequency'       => $med['frequency'],
                    'duration'        => $med['duration'],
                    'instruction'     => $med['instruction']
                ]);
            }
        }
    }

    return redirect()->to(base_url('doctor_dashboard'))
                     ->with('success', 'Prescription updated successfully.');
}
public function displayOnlyPrescription($id){
//    public function edit($prescriptionId)
// {
    $prescriptionModel = new \App\Models\PrescriptionModel();
    $medicineModel = new PrescriptionMedicineModel();

    // Get main prescription info
    $prescription = $prescriptionModel
        ->select('prescription.*, patients.name as patient_name, users.username as doctor_name')
        ->join('appointments','prescription.appointment_id=appointments.id')
        ->join('patients','patients.id=appointments.patient_id')
        ->join('doctors','doctors.id=appointments.doctor_id')
        ->join('users','users.id=doctors.userId')
        ->where('prescription.id', (int)$id)
        ->first();

    // Get all medicines for this prescription
    $medicines = $medicineModel
        ->where('prescription_id', $id)
        ->findAll();

    return view('doctor/viewPrescription', [
        'prescription' => $prescription,
        'medicines'    => $medicines
    ]);
}
}

