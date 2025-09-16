<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;
use App\Models\DoctorModel;
use App\Models\PatientModel;


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

        // Join doctors with users to fetch doctor + user info
        $doctors = $doctorModel
            ->select('doctors.id as doctor_id, doctors.*, users.username, users.email')
            ->join('users', 'users.id = doctors.userid')
            ->findAll();

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
    join('appointments','appointments.patient_id=patients.id')->
    join('doctors','appointments.doctor_id=doctors.id')->
    where('doctors.id',$id)->
    groupBy('patients.id')->
    findAll();

return view('doctor/doctor-patient', $data);
    }
}

