<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\PatientModel;
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

}