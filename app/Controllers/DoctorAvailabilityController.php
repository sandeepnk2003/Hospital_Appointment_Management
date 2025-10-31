<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\DoctorModel;
use App\Models\DoctorAvailabilityModel;
use App\Models\UserModel;
use App\Models\HospitalModel;

use CodeIgniter\Controller;

class DoctorAvailabilityController extends ResourceController
{
    public function index()
    {
        $availabilityModel = new DoctorAvailabilityModel();
        $doctorModel = new DoctorModel();

        $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
        $data['days'] = $days;

        // Fetch all doctors for dropdown
        $data['doctors'] = $doctorModel
            ->select('doctors.id, users.username as doctors_name')
            ->join('users', 'users.id = doctors.userid')
             ->join('userhospital_junction','userhospital_junction.userid=users.id')
            ->where('userhospital_junction.hospital_id',session('hospital_id') )
            ->findAll();

        // Fetch all availability records
        $availabilitiesRaw = $availabilityModel
            ->select('doctor_availability.*, users.username as doctors_name')
            ->join('doctors', 'doctors.id = doctor_availability.doctor_id')
            ->join('users', 'users.id = doctors.userid')
             ->join('userhospital_junction','userhospital_junction.userid=users.id')
            ->where('userhospital_junction.hospital_id',session('hospital_id') )
            ->orderBy('day_of_week', 'ASC')
            ->findAll();


        // Group availabilities by day
        $grouped = [];
        foreach ($days as $day) {
            $grouped[$day] = array_filter($availabilitiesRaw, function($a) use ($day) {
                return $a['day_of_week'] === $day;
            });
        }

        $data['availabilities'] = $grouped;

        return view('doctor_availability/index', $data);
    }
     public function store()
    {
        $availabilityModel = new DoctorAvailabilityModel();

        $data = [
            'hospital_id'=>session('hospital_id'),
            'doctor_id'   => $this->request->getPost('doctor_id'),
            'day_of_week' => $this->request->getPost('day_of_week'),
            'start_time'  => $this->request->getPost('start_time'),
            'end_time'    => $this->request->getPost('end_time'),
            'shift_name'  => $this->request->getPost('shift_name'),
            'is_available'=> $this->request->getPost('is_available') ? 1 : 0,
        ];

        $availabilityModel->save($data);

        return redirect()->to('/availability')->with('success', 'Availability added successfully');
    }
   public function editDoctor($id)
{
    $availabilityModel = new DoctorAvailabilityModel();
    $doctorModel = new DoctorModel();

    // If form is submitted (POST)
    if ($this->request->getMethod() === 'POST') {
        $data = [
            // 'hospital_id' => session('hospital_id'),
            // 'doctor_id'   => $this->request->getPost('doctor_id'),
            'day_of_week'  => $this->request->getPost('day_of_week'),
            'start_time'   => $this->request->getPost('start_time'),
            'end_time'     => $this->request->getPost('end_time'),
            'shift_name'   => $this->request->getPost('shift_name'),
            'is_available' => $this->request->getPost('is_available') ? 1 : 0,
        ];

        $availabilityModel->update($id, $data);

        return redirect()->to('/availability')->with('success', 'Availability updated successfully');
    }

    // Else (GET request) → show edit form
    $data['doctor'] = $doctorModel
        ->select('doctors.id, users.username as doctors_name')
        ->join('users', 'users.id = doctors.userid')
         ->join('userhospital_junction','userhospital_junction.userid=users.id')
            ->where('userhospital_junction.hospital_id',session('hospital_id') )
        ->findAll();

    $data['avail'] = $availabilityModel
        ->select('doctor_availability.*, users.username as doctors_name')
        ->join('doctors', 'doctors.id = doctor_availability.doctor_id')
        ->join('users', 'users.id = doctors.userid')
        ->join('userhospital_junction','userhospital_junction.userid=users.id')
        ->where('userhospital_junction.hospital_id',session('hospital_id') )
        ->where('doctor_availability.id', $id)
        ->first(); // use first() for single record

    return view('doctor_availability/edit', $data);
}

    public function toggle($id)
{
    if ($this->request->isAJAX()) {
        $availabilityModel = new \App\Models\DoctorAvailabilityModel();
        $slot = $availabilityModel->find($id);

        if ($slot) {
            $newStatus = $slot['is_available'] ? 0 : 1;
            $availabilityModel->update($id, ['is_available' => $newStatus]);
            return $this->response->setJSON(['status' => 'success']);
        }

        return $this->response->setJSON(['status' => 'error']);
    }

    return redirect()->to('availability');
}
public function patient_doctorAvailability(){
     $availabilityModel = new DoctorAvailabilityModel();
        $doctorModel = new DoctorModel();

        $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
        $data['days'] = $days;

        // Fetch all doctors for dropdown
        $data['doctors'] = $doctorModel
            ->select('doctors.id, users.username as doctors_name')
            ->join('hospitals','hospitals.id=doctors.hospital_id')
            ->join('users', 'users.id = doctors.userid')
            ->where('doctors.hospital_id', session('hospital_id'))
            ->findAll();

        // Fetch all availability records
        $availabilitiesRaw = $availabilityModel
            ->select('doctor_availability.*, users.username as doctors_name,doctors.specialization as doctorSpecalization')
            ->join('doctors', 'doctors.id = doctor_availability.doctor_id')
            ->join('hospitals','hospitals.id=doctors.hospital_id')
            ->join('users', 'users.id = doctors.userid')
            ->where('is_available',1)
            ->where('doctors.hospital_id', session('hospital_id'))
            ->orderBy('day_of_week', 'ASC')
            ->findAll();

        // Group availabilities by day
        $grouped = [];
        foreach ($days as $day) {
            $grouped[$day] = array_filter($availabilitiesRaw, function($a) use ($day) {
                return $a['day_of_week'] === $day;
            });
        }

        $data['availabilities'] = $grouped;

        return view('doctor_availability/patientindex', $data);
}
public function doctorAvailability()
{
    $hospitalModel = new \App\Models\HospitalModel();
    $data['hospitals'] = $hospitalModel->findAll();

    $data['days'] = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    $data['availabilities'] = [];

    return view('doctor_availability/patientindex', $data);
}

public function getAvailabilityByHospital()
{
    $hospital_id = $this->request->getPost('hospital_id');

    if (!$hospital_id) {
        return $this->response->setJSON(['error' => 'Hospital ID missing']);
    }

    $availabilityModel = new DoctorAvailabilityModel();
    $doctors = $availabilityModel
        ->select('doctor_availability.*, users.username as doctors_name, doctors.specialization as doctorSpecalization')
        ->join('doctors', 'doctors.id = doctor_availability.doctor_id')
        ->join('users', 'users.id = doctors.userid')
         ->join('userhospital_junction','userhospital_junction.userid=users.id')
            ->where('userhospital_junction.hospital_id',session('hospital_id') )
        ->findAll();

    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    $grouped = [];

    foreach ($days as $day) {
        $grouped[$day] = [];
    }

    foreach ($doctors as $d) {
        $grouped[$d['day']][] = $d;
    }

    return $this->response->setJSON($grouped);
}
public function deleteAvail($id){
     $availabilityModel = new DoctorAvailabilityModel();
     $deletedata= $availabilityModel->delete($id);
    if(!$deletedata){
      return redirect()->to('/availability')->with('success', 'Availability could not be deleted');
    }
    else{
         return redirect()->to('/availability')->with('success', 'Availability deleted successfully');
    }
    }
    public function doctorInfo(){
        $doctorModel=new DoctorModel();
        $data['doctors']=$doctorModel->
        select('doctors.*,users.username')
        ->join('users','users.id=doctors.userid')
        ->findAll();
        return view('patient/doctorinfo',$data);
        // dd($data);
    }
    public function doctorsAvailability($id){
          $doctoravailModel=new DoctorAvailabilityModel();
          $doctorModel = new DoctorModel();

        $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
        $data['days'] = $days;

          $availabilitiesRaw = $doctoravailModel
            ->select('doctor_availability.*, hospitals.hospital_name,users.username as doctors_name')
            ->join('doctors', 'doctors.id = doctor_availability.doctor_id')
            ->join('hospitals','hospitals.id=doctor_availability.hospital_id')
            ->join('users', 'users.id = doctors.userid')
            ->where('is_available',1)
            ->where('doctors.id',$id)
            ->orderBy('day_of_week', 'ASC')
            ->findAll();
//   dd($availabilitiesRaw);
        // Group availabilities by day
        $grouped = [];
        foreach ($days as $day) {
            $grouped[$day] = array_filter($availabilitiesRaw, function($a) use ($day) {
                return $a['day_of_week'] === $day;
            });
        }

        $data['availabilities'] = $grouped;
        return view('patient/doctor_availability',$data);

    }
}

