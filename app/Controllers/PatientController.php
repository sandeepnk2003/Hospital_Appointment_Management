<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\PatientModel;
use App\Models\AppointmentModel;
use App\Models\DoctorModel;
use App\Models\UserModel;

class PatientController extends ResourceController
{
public function index()
    {
      
        $patientModel = new PatientModel();

        // fetch all patients
        $data['patients'] = $patientModel->findAll();

        // pass to view as associative array
        return view('patient/index', $data);
    }
     public function create()
    {
        if($this->request->getMethod()==='POST'){
          $patientModel = new PatientModel();
          $patientModel->save([
            'name' => $this->request->getPost('name'),
            'email'        => $this->request->getPost('email'),
            'phone'        => $this->request->getPost('phone'),
            'gender'       => $this->request->getPost('gender'),
            'dob'          => $this->request->getPost('dob'),
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
$data['appt'] = $appointmentModel
    ->select('appointments.*, users.username as doctor_name, doctors.specialization')
    ->join('doctors', 'doctors.id = appointments.doctor_id')
    ->join('users', 'users.id = doctors.userid')
    ->where('appointments.patient_id', $patientId)
    ->orderBy('appointments.start_datetime', 'DESC')
    ->findAll();

return view('patient/appointments_history', $data);
}
  
 public function book()
    {
        // Load doctors for dropdown
        $doctorModel = new DoctorModel();
        $data['doctors'] = $doctorModel
        ->select('users.username as name,doctors.*')
        ->join('users','users.id=doctors.userid')
        ->find();

        return view('patient/book_appointment', $data);
    }
    public function saveBooking()
    {
        $appointmentModel = new AppointmentModel();

        $data = [
            'patient_id'     => session()->get('patient_id'),  // logged-in patient
            'doctor_id'      => $this->request->getPost('doctor_id'),
            'start_datetime' => $this->request->getPost('date') . ' ' . $this->request->getPost('time'),
            'status'         => 'Scheduled',
        ];

        if ($appointmentModel->insert($data)) {
            return redirect()->to(base_url('patient/appointments/history'))
                             ->with('success', 'Appointment booked successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to book appointment.');
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