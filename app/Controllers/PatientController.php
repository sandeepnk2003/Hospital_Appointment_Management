<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\PatientModel;
use App\Models\AppointmentModel;
use App\Models\DoctorModel;
use App\Models\UserModel;
use App\Models\DoctorAvailabilityModel;
use App\Models\HospitalModel;


class PatientController extends ResourceController
{
public function index()
    {
      
        $patientModel = new PatientModel();

        // fetch all patients
        $data['patients'] = $patientModel
        ->join('appointments','appointments.patient_id=patients.id')
        // ->join('hospitals','hospitals.id=patients.hospital_id')
        ->where('hospital_id', session('hospital_id'))
        ->findAll();
    //  dd($data);
        // pass to view as associative array
        return view('patient/index', $data);
    }
    public function create()
{
    helper(['form']);

    if ($this->request->getMethod() === 'POST') {
        $patientModel = new PatientModel();

        // Define validation rules
        $rules = [
            'name'  => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email',
            'phone' => 'required|min_length[10]|max_length[15]',
            'gender'=> 'required',
            'dob'   => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        // Check if email already exists for this hospital
        $email = $this->request->getPost('email');
        // $hospitalId = session('hospital_id');
        $exist = $patientModel
            // ->where('hospital_id', $hospitalId)
            ->where('email', $email)
            ->first();

        if ($exist) {
            return redirect()->back()->withInput()->with('error', 'This email is already registered');
        }

        // Save patient
        $patientModel->save([   
            // 'hospital_id' => $hospitalId,
            'name'        => $this->request->getPost('name'),
            'email'       => $email,
            'phone'       => $this->request->getPost('phone'),
            'gender'      => $this->request->getPost('gender'),
            'dob'         => $this->request->getPost('dob'),
        ]);

        return redirect()->to('/patients')->with('success', 'Patient added successfully');
    }

    return view('patient/create');
}

  public function edit($id = null)
{
    $patientModel = new PatientModel();
    $data['patients'] = $patientModel->find($id);

    if (!$data['patients']) {
        return redirect()->to('/patient')->with('error', 'Patient not found');
    }

    return view('patient/edit', $data);
}

public function update($id = null)
{
    $patientModel = new PatientModel();

    $datainfo = [
        'name'       => $this->request->getPost('name'),
        'email'      => $this->request->getPost('email'),
        'phone'      => $this->request->getPost('phone'),
        'dob'        => $this->request->getPost('dob'),
        'gender'     => $this->request->getPost('gender'),
        'updated_at' => date('Y-m-d H:i:s')
    ];

    $patientModel->update($id, $datainfo);

    return redirect()->to('/patients')->with('success', 'Patient updated successfully');
}
public function delete($id=null){
    $patientModel = new PatientModel();
    $patientModel->delete($id);
    return redirect()->to('/patients')->with('success', 'Patient deleted  successfully');
}
public function profile()
{
    if (!session()->get('isLoggedIn') || session()->get('role') !== 'patient') {
        return redirect()->to('/patient/login');
    }

    $patientId = session()->get('patient_id');
    $patientModel = new \App\Models\PatientModel();
    $data['patient'] = $patientModel->find($patientId);

    return view('patient/profile', $data);
}
public function appointmentHistory()
{
    if (!session()->get('isLoggedIn') || session()->get('role') !== 'patient') {
        return redirect()->to('/patient/login');
    }

    $patientId = session()->get('patient_id');
    $appointmentModel = new \App\Models\AppointmentModel();
    // $appointmentId=$appointmentModel
    // ->select('id')
    // ->where('appointments.patient_id', $patientId)
    // ->first();
    // dd($appointmentId);

     $data['appt'] = $appointmentModel
    ->select('appointments.*, users.username as doctor_name, doctors.specialization,prescription.id as prescription_id,hospitals.hospital_name as hospital_name')
    ->join('hospitals','hospitals.id=appointments.hospital_id')
    ->join('doctors', 'doctors.id = appointments.doctor_id')
    ->join('users', 'users.id = doctors.userid')
    ->join('prescription','prescription.appointment_id=appointments.id')
    ->where('appointments.patient_id', $patientId)
    ->orderBy('appointments.start_datetime', 'DESC')
    ->findAll();

return view('patient/appointments_history', $data);
}
  
 public function book()
    {
        $hospitalModel = new HospitalModel();
        $doctorModel = new DoctorModel();

        // Load all hospitals
        $data['hospitals'] = $hospitalModel->findAll();

        // Optional: initially empty doctors list
        $data['doctors'] = [];

        return view('patient/book_appointment', $data);
    }

     public function getDoctorsByHospital()
    {
        $hospital_id = $this->request->getPost('hospital_id');

        $doctorModel = new DoctorModel();
        $doctors = $doctorModel
            ->select('users.username as name, doctors.*')
            ->join('users', 'users.id = doctors.userid')
            ->where('doctors.hospital_id', $hospital_id)
            ->findAll();

        return $this->response->setJSON($doctors);
    }

    public function saveBooking()
    {
        $appointmentModel = new AppointmentModel();

         $patientId= session()->get('patient_id');
            $start = $this->request->getPost('date') . ' ' . $this->request->getPost('time');

    // Convert to DateTime
    $startDateTime = new \DateTime($start);
    $endDateTime   = (clone $startDateTime)->modify('+15 minutes');

    $dayOfWeek = $startDateTime->format('l');
$doctorAvailabilityModel=new DoctorAvailabilityModel();
        // Validation: Check if doctor is already booked
        $doctorId = $this->request->getPost('doctor_id');
    
        $availability = $doctorAvailabilityModel
    ->where('doctor_id', $doctorId)
    ->where('day_of_week', $dayOfWeek)
    ->where('is_available', 1)
    ->first();

if (!$availability) {
    return redirect()->back()
        ->with('error', "Doctor is not available on $dayOfWeek.");
}

// 2️⃣ Check if requested time falls inside availability range
$requestedStart = $startDateTime->format('H:i:s');
$requestedEnd   = $endDateTime->format('H:i:s');

if ($requestedStart < $availability['start_time'] || $requestedEnd > $availability['end_time']) {
    return redirect()->back()
        ->with(
            'error',
            "Doctor is available on $dayOfWeek from " .
            date('h:i A', strtotime($availability['start_time'])) . " to " .
            date('h:i A', strtotime($availability['end_time'])) . "."
        );
}
    $overlap = $appointmentModel->where('doctor_id', $doctorId)
        ->where('start_datetime <=', $endDateTime->format('Y-m-d H:i:s'))
        ->where('end_datetime >=', $startDateTime->format('Y-m-d H:i:s'))
        ->first();

    $patientOverlap = $appointmentModel->where('patient_id', $patientId)
    ->where('start_datetime <=', $endDateTime->format('Y-m-d H:i:s'))
    ->where('end_datetime >=', $startDateTime->format('Y-m-d H:i:s'))
    ->first();

    if ($overlap) {
        return redirect()->back()->withInput()->with('error', 'Doctor is already booked at this time!');
    }
    if ($patientOverlap) {
    return redirect()->back()->withInput()->with('error', 'You already have an appointment at this time!');
}
   $hospital_id=$this->request->getVar('hospital_id');
//    dd($hospital_id);
     $data = [
        'hospital_id'=> $hospital_id,
        'patient_id'     => session()->get('patient_id'),
        'doctor_id'      => $doctorId,
        'start_datetime' => $startDateTime->format('Y-m-d H:i:s'),
        'end_datetime'   => $endDateTime->format('Y-m-d H:i:s'),
        'status'         => 'Scheduled',
    ];

    if ($appointmentModel->insert($data)) {
        return redirect()->to(base_url('patient/appointments/history'))
                         ->with('success', 'Appointment rebooked successfully!');
    } else {
        return redirect()->back()->with('error', 'Failed to rebook appointment.');
    }
    }
   public function rebook($id)
{
    $appointmentModel = new AppointmentModel();
    $doctorModel = new DoctorModel();
    $UserModel= new userModel();

        $appointment = $appointmentModel->find($id);
  
        if (!$appointment) {
            return redirect()->to(base_url('patient/appointments/history'))
                            ->with('error', 'Appointment not found.');
        }

        // Get doctor details
      $doctor = $doctorModel
        ->withDeleted()
    ->select('users.username as doctor_name, doctors.*')
    ->join('users', 'users.id = doctors.userid')
    ->where('doctors.id', $appointment['doctor_id'])
    ->first();
       if (!empty($doctor['deleted_at'])) {
        return redirect()->to(base_url('patient/appointments/history'))
                         ->with('error', 'This doctor is no longer available at the hospital.');
    }

    $data['appointment'] = $appointment;
    $data['doctor'] = $doctor;

    return view('patient/rebook_appointment', $data);
}
public function saveRebook()
{
    $appointmentModel=new AppointmentModel();
    $patientId= session()->get('patient_id');
   if ($this->request->getMethod() === 'POST') {
    $start = $this->request->getPost('date') . ' ' . $this->request->getPost('time');

    // Convert to DateTime
    $startDateTime = new \DateTime($start);
    $endDateTime   = (clone $startDateTime)->modify('+15 minutes');

    // Validation: Check if doctor is already booked
    $doctorId = $this->request->getPost('doctor_id');
    $overlap = $appointmentModel->where('doctor_id', $doctorId)
        ->where('start_datetime <=', $endDateTime->format('Y-m-d H:i:s'))
        ->where('end_datetime >=', $startDateTime->format('Y-m-d H:i:s'))
        ->first();

    $patientOverlap = $appointmentModel->where('patient_id', $patientId)
    ->where('start_datetime <=', $endDateTime->format('Y-m-d H:i:s'))
    ->where('end_datetime >=', $startDateTime->format('Y-m-d H:i:s'))
    ->first();

    if ($overlap) {
        return redirect()->back()->withInput()->with('error', 'Doctor is already booked at this time!');
    }
    if ($patientOverlap) {
    return redirect()->back()->withInput()->with('error', 'You already have an appointment at this time!');
}
   
     $data = [
        'hospital_id'=>session('hospital_id'),
        'patient_id'     => session()->get('patient_id'),
        'doctor_id'      => $doctorId,
        'start_datetime' => $startDateTime->format('Y-m-d H:i:s'),
        'end_datetime'   => $endDateTime->format('Y-m-d H:i:s'),
        'status'         => 'Scheduled',
    ];

    if ($appointmentModel->insert($data)) {
        return redirect()->to(base_url('patient/appointments/history'))
                         ->with('success', 'Appointment rebooked successfully!');
    } else {
        return redirect()->back()->with('error', 'Failed to rebook appointment.');
    }
}}
public function reschedule($id)
{
    $appointmentModel = new AppointmentModel();
    $doctorModel = new doctorModel();
  

    $appointment = $appointmentModel->find($id);


      $doctor = $doctorModel
        ->withDeleted()
    ->select('users.username as doctor_name, doctors.*')
    ->join('users', 'users.id = doctors.userid')
    ->where('doctors.id', $appointment['doctor_id'])
    ->first();
    $data['doctor']=$doctor;

    $data['appointment']=$appointment;

    if (!$appointment) {
        return redirect()->to(base_url('patient/appointments/history'))
                         ->with('error', 'Appointment not found.');
    }

    // Pass appointment to view
    return view('patient/reschedule_appointment', [
        'appointment' => $appointment,
        'doctor'      => $doctor
    ]);
}

public function saveReschedule($id)
{
    $appointmentModel = new AppointmentModel();
    $appointment = $appointmentModel->find($id);

    if (!$appointment) {
        return redirect()->to(base_url('patient/appointments/history'))
                         ->with('error', 'Appointment not found.');
    }

    $date = $this->request->getPost('date');
    $time = $this->request->getPost('time');
    $startDateTime = new \DateTime("$date $time");
    $endDateTime   = (clone $startDateTime)->modify('+15 minutes');

    // Validation: check if doctor is free
    $overlap = $appointmentModel
        ->where('doctor_id', $appointment['doctor_id'])
        ->where('id !=', $id) // exclude current appointment
        ->where('start_datetime <=', $endDateTime->format('Y-m-d H:i:s'))
        ->where('end_datetime >=', $startDateTime->format('Y-m-d H:i:s'))
        ->first();

    if ($overlap) {
        return redirect()->back()->withInput()->with('error', 'Doctor is already booked at this time!');
    }

    // Update appointment
    $appointment['start_datetime'] = $startDateTime->format('Y-m-d H:i:s');
    $appointment['end_datetime']   = $endDateTime->format('Y-m-d H:i:s');
    $appointmentModel->save($appointment);

    return redirect()->to(base_url('patient/appointments/history'))
                     ->with('success', 'Appointment rescheduled successfully!');
}
public function UpdateAppointment($id){

    $appointmentModel=new AppointmentModel();
     $appointmentModel->update($id, ['status' => 'Completed']);
    return redirect()->back()->with('message', 'Appointment marked as completed.');


}


}