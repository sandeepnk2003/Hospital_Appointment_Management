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
            'hospital_id'    =>session('hospital_id'),
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
            ->join('hospitals','hospitals.id=doctors.hospital_id')
            ->join('users', 'users.id = doctors.userid')
            ->where('doctors.hospital_id',$hospital_id )
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
        $appointmentModel = new AppointmentModel();

        $appointmentId = $this->request->getPost('appointment_id');
        $patientId     = $this->request->getPost('patient_id');
        $DoctorId     = $this->request->getPost('doctor_id');

    $existingVisit = $visitModel->where('appointment_id', $appointmentId)->first();
    if ($existingVisit) {
        return redirect()->back()->with('error', 'Visit already completed for this appointment.');
    }
        $data = [
            'hospital_id'=>session('hospital_id'),
            'appointment_id'  => $appointmentId,
            'patient_id'      => $patientId,
            'doctor_id'       => $DoctorId,
            'date'            => date('Y-m-d'),
            'reason'          => $this->request->getPost('reason'),
            'weight'          => $this->request->getPost('weight'),
            'blood_pressure'  => $this->request->getPost('blood_pressure'),
            'doctor_comments' => $this->request->getPost('doctor_comments'),
        ];

        $visitModel->insert($data);

        // Mark appointment as completed
        $appointmentModel->update($appointmentId, [
            'status' => 'completed'
        ]);

        return redirect()->to('appointments/complete/'.$appointmentId)->with('success', 'Visit completed successfully!');
    }
    public function index2($patientId)
    {
        $patientModel = new PatientModel();
        $visitModel   = new VisitModel();

        // Fetch patient details
        $patient = $patientModel->find($patientId);
        if (!$patient) {
            return redirect()->back()->with('error', 'Patient not found');
        }

        // Fetch visits
        $visits = $visitModel->where('patient_id', $patientId)
                            // ->where('hodpital_id',session('hospital_id'))
                             ->orderBy('date', 'ASC')
                             ->findAll();

        // Prepare chart data
        $visitDates = [];
        $weightData = [];
        $systolicData = [];
        $diastolicData = [];

        foreach($visits as $v){
            $visitDates[] = date('d M', strtotime($v['date']));
            $weightData[] = floatval(str_replace('kg','',$v['weight']));
            $bpParts = explode('/', $v['blood_pressure']);
            $systolicData[]  = intval($bpParts[0] ?? 0);
            $diastolicData[] = intval($bpParts[1] ?? 0);
        }

        return view('patient/index2', [
            'patient'       => $patient,
            'visits'        => $visits,
            'visitDates'    => $visitDates,
            'weightData'    => $weightData,
            'systolicData'  => $systolicData,
            'diastolicData' => $diastolicData
        ]);
    }


}

