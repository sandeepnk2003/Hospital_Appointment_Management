<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;
use App\Models\DoctorModel;
use App\Models\PatientModel;
use App\Models\AppointmentModel;
use App\Models\VisitModel;
use App\Models\PrescriptionModel;
use App\Models\PrescriptionMedicineModel;
use App\Models\HospitalModel;
use App\Models\VisitReasonModel;

class DoctorController extends ResourceController
{

  public function adddoctor($id)
{
    $userModel = new UserModel();
    $doctorModel = new DoctorModel();

    // Fetch the user by ID
    $user = $userModel->find($id);

    if (!$user) {
        return redirect()->back()->with('error', 'User not found');
    }

    // Check if doctor info already exists
    $exists = $doctorModel->where('userid', $id)->first();
    if ($exists) {
        return redirect()->back()->with('error', 'Doctor info already exists for this user.');
    }

    // If the request is POST, insert doctor info
    if ($this->request->getMethod() === 'POST') {
        $data = [
            // 'hospital_id'    =>session('hospital_id'),
            'userid'          => $id,
            'specialization'  => $this->request->getPost('specialization'),
            'qualification'   => $this->request->getPost('qualification'),
            'experience'      => $this->request->getPost('experience'),
            'consultation_fee'=> $this->request->getPost('consultation_fee'),
        ];

        // Insert into doctors table
        $doctorModel->insert($data);

        return redirect()->to(base_url('users/'))
                         ->with('success', 'Doctor info added successfully!');
    }

    // Show the create view with user data
    return view('doctor/create', ['user' => $user]);
}
public function listdoctor(){
   
        $doctorModel = new DoctorModel();
        $userModel   = new UserModel();
   $hospital_id=session('hospital_id');
//    dd($hospital_id);
        // Join doctors with users to fetch doctor + user info
        $doctors = $doctorModel
            ->select('doctors.id as doctor_id, doctors.*, users.username, users.email')
            ->join('users', 'users.id = doctors.userid')
            ->join('userhospital_junction','userhospital_junction.userid=users.id')
            ->where('userhospital_junction.hospital_id',$hospital_id )
            ->findAll();
// dd($doctors);
        return view('doctor/index', ['doctors' => $doctors]);
    }

public function edit($id=null)
    {
       $doctorModel = new DoctorModel();
        $userModel   = new UserModel();

        // Join doctors with users to fetch doctor + user info
        $doctor = $doctorModel
            ->select('doctors.id as doctor_id, doctors.*, users.username, users.email')
            ->join('users', 'users.id = doctors.userid')
            ->find($id);

        if (!$doctor) {
            return redirect()->to(base_url('doctors'))->with('error', 'Doctor not found');
        }

        if ($this->request->getMethod() === 'POST') {
            $data = [
                'hospital_id'      =>session('hospital_id'),
                'specialization'   => $this->request->getPost('specialization'),
                'qualification'    => $this->request->getPost('qualification'),
                'experience'       => $this->request->getPost('experience'),
                'consultation_fee' => $this->request->getPost('consultation_fee'),
            ];
            $doctorModel->update($id, $data);

            return redirect()->to(base_url('doctors/'))->with('success', 'Doctor updated successfully');
        }

        return view('doctor/edit', ['doctor' => $doctor]);
    }

    public function delete($id=null)
    {
        $doctorModel = new DoctorModel();
        $doctorModel->delete($id);

        return redirect()->to(base_url('doctors'))->with('success', 'Doctor deleted successfully');
    }
    public function doctorPatient($id){
        $patient=new PatientModel();
    $data['patients']=$patient->
    select('patients.*')->
    join('appointments','appointments.patient_id=patients.id')
    // ->join('hospitals','hospitals.id=appointm.hospital_id')
    ->join('doctors','appointments.doctor_id=doctors.id')->
    where('doctors.id',$id)
    ->where('appointments.hospital_id', session('hospital_id'))
    ->
    groupBy('patients.id')->
    findAll();
    // dd($data);

return view('doctor/doctor-patient', $data);
    }
     public function addVisit($appointmentId)
    {
        $appointmentModel = new AppointmentModel();
        $appointment = $appointmentModel
        ->select('patients.name as patient_name,appointments.*')
        ->join('patients','patients.id=appointments.patient_id')
        ->find($appointmentId);
     
        if (!$appointment) {
            return redirect()->back()->with('error', 'Appointment not found');
        }

        return view('doctor/doctor_visit_form', ['appointment' => $appointment]);
    }
       public function saveVisit()
    {
    $visitModel = new VisitModel();
    $reasonModel = new VisitReasonModel();
    $appointmentModel=new AppointmentModel();
    $appointmentId = $this->request->getPost('appointment_id');
    $hospitalId = $this->request->getPost('hospital_id');
    $patientId = $this->request->getPost('patient_id');
    $doctorId = $this->request->getPost('doctor_id');
    $weight = $this->request->getPost('weight');
    $bloodPressure = $this->request->getPost('blood_pressure');
    $reasons = $this->request->getPost('reason');        // array
    $diagnoses = $this->request->getPost('diagnosis');   // array

    // Step 1: Save visit info
    $visitData = [
        'hospital_id'    => $hospitalId,
        'appointment_id' => $appointmentId,
        'patient_id'     => $patientId,
        'doctor_id'      => $doctorId,
        'date'           => date('Y-m-d'),
        'weight'         => $weight,
        'blood_pressure' => $bloodPressure,
        'created_at'     => date('Y-m-d H:i:s'),
    ];

    if (!$visitModel->insert($visitData)) {
        return redirect()->back()->with('error', 'Failed to save visit.');
    }

    $visitId = $visitModel->getInsertID();

    // Step 2: Save multiple reasons
    foreach ($reasons as $index => $reasonText) {
        $diagnosisText = $diagnoses[$index] ?? null;
        if (!empty(trim($reasonText)) || !empty(trim($diagnosisText))) {
            $reasonModel->insert([
                'visit_id'   => $visitId,
                'reason'     => $reasonText,
                'diagnosis'  => $diagnosisText,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }

        $appointmentModel->update($appointmentId, [
            'status' => 'completed'
        ]);

        return redirect()->to('appointments/complete/'.$appointmentId)->with('success', 'Visit completed successfully!');
    }
    public function index2($patientId)
    {
        $patientModel = new PatientModel();
        $visitModel = new VisitModel();
        $visitReasonModel = new VisitReasonModel();

        // ✅ Fetch patient
        $patient = $patientModel->find($patientId);
        if (!$patient) {
            return redirect()->back()->with('error', 'Patient not found');
        }

        // ✅ Fetch all visits
        $visits = $visitModel
          ->select('visits.*,hospitals.hospital_name,users.username as doctor_name')
             ->join('appointments','appointments.id=visits.appointment_id')
             ->join('doctors','doctors.id=appointments.doctor_id')
             ->join('users','users.id=doctors.userid')
             ->join('hospitals','hospitals.id=visits.hospital_id')
            ->where('visits.patient_id', $patientId)
            ->orderBy('date', 'ASC')
            ->findAll();
// dd($visits);
        // ✅ Attach reasons and diagnoses to each visit
        foreach ($visits as &$visit) {
            $visit['reasons'] = $visitReasonModel
                ->where('visit_id', $visit['id'])
                ->findAll();
        }
        unset($visit);

        // ✅ Prepare chart data
        $visitDates = [];
        $weightData = [];
        $systolicData = [];
        $diastolicData = [];

        foreach ($visits as $v) {
            $visitDates[] = date('d M', strtotime($v['date']));
            $weightData[] = floatval(str_replace('kg', '', $v['weight']));
            $bpParts = explode('/', $v['blood_pressure']);
            $systolicData[] = intval($bpParts[0] ?? 0);
            $diastolicData[] = intval($bpParts[1] ?? 0);
        }

        // ✅ Pass to view
        return view('patient/index2', [
            'patient'       => $patient,
            'visits'        => $visits,
            'visitDates'    => $visitDates,
            'weightData'    => $weightData,
            'systolicData'  => $systolicData,
            'diastolicData' => $diastolicData,
        ]);
    }
}

