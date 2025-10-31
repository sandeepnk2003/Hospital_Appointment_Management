<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\DoctorModel;
use App\Models\PatientModel;
use App\Models\AppointmentModel;
use App\Models\userModel;
use App\Models\HospitalModel;

class DashboardController extends ResourceController
{
   public function index()
{
    if (!session()->get('isLoggedIn')) {
        return redirect()->to('/auth/login');
    }

    $patientModel     = new \App\Models\PatientModel();
    $doctorModel      = new \App\Models\DoctorModel();
    $appointmentModel = new \App\Models\AppointmentModel();
    $hospital_id=session('hospital_id');
    // Counts
    $data['patientCount'] = $patientModel
    ->select('patients.id AS patient_id, patients.name, patients.email, appointments.id AS appointment_id')
    ->join('appointments', 'appointments.patient_id = patients.id')
    ->where('appointments.hospital_id', $hospital_id)
    ->groupBy('patients.id')
    ->countAllResults();
    // dd($data['patients']);

    $data['doctorCount']  = $doctorModel 
    ->join('users','users.id=doctors.userid')
     ->join('userhospital_junction', 'userhospital_junction.userid = users.id')
    ->where('userhospital_junction.hospital_id', $hospital_id)->countAllResults();


    $data['totalAppointments'] = $appointmentModel ->join('hospitals','hospitals.id=appointments.hospital_id')->where('hospital_id', $hospital_id)->countAllResults();

    // Today’s appointments
    $today = date('Y-m-d');
    $data['todayAppointments'] = $appointmentModel
     ->join('hospitals','hospitals.id=appointments.hospital_id')
     ->where('hospital_id', $hospital_id)
        ->where('DATE(start_datetime)', $today)
        ->countAllResults();

    // This week’s appointments (Monday–Sunday)
    $weekStart = date('Y-m-d', strtotime('monday this week'));
    $weekEnd   = date('Y-m-d', strtotime('sunday this week'));
    $data['weekAppointments'] = $appointmentModel
        ->join('hospitals','hospitals.id=appointments.hospital_id')
        ->where('hospital_id', $hospital_id)
        ->where('DATE(start_datetime) >=', $weekStart)
        ->where('DATE(start_datetime) <=', $weekEnd)
        ->countAllResults();

    // This month’s appointments
    $monthStart = date('Y-m-01');
    $monthEnd   = date('Y-m-t'); // last day of month
    $data['monthAppointments'] = $appointmentModel
        ->join('hospitals','hospitals.id=appointments.hospital_id')
         ->where('hospital_id', $hospital_id)
        ->where('DATE(start_datetime) >=', $monthStart)
        ->where('DATE(start_datetime) <=', $monthEnd)
        ->countAllResults();

    return view('dashboard/index', $data);
}
 public function doctor_index()
{
    if (!session()->get('isLoggedIn')) {
        return redirect()->to('/auth/login');
    }

  $patientModel     = new \App\Models\PatientModel();
    $doctorModel      = new \App\Models\DoctorModel();
    $appointmentModel = new \App\Models\AppointmentModel();
$doctor = $doctorModel
->select('doctors.id')
// ->join('hospitals','hospitals.id=doctors.hospital_id')
 ->join('userhospital_junction','userhospital_junction.userid=doctors.userid')
 ->where('userhospital_junction.hospital_id',session('hospital_id') )
 ->where('doctors.userid', session()->get('user_id'))
->first();
    // dd($doctor);
$doctorId = $doctor['id'];
// dd($doctorId);
$data['id']=$doctorId;
$hospital_id=session('hospital_id');
// dd($hospital_id);
//     print_r($doctorId);
//  dd(session()->get());
    // Today
    $today = date('Y-m-d');
    // dd($today);
    $data['todayAppointmentsCount'] = $appointmentModel
        ->where('hospital_id',$hospital_id)
        ->where('DATE(start_datetime)', $today)
        ->where('doctor_id',$doctorId)
        ->countAllResults();
    // dd( $data['todayAppointmentsCount'] );
    //  print_r($data);
    // This week (BETWEEN)
    $weekStart = date('Y-m-d', strtotime('monday this week'));
    $weekEnd   = date('Y-m-d', strtotime('sunday this week'));
    $data['weekAppointmentsCount'] = $appointmentModel
    // ->join('hospitals','hospitals.id=appointments.hospital_id')
    // ->where('hospital_id', session('hospital_id'))
        ->where('doctor_id', $doctorId)
        ->where("DATE(start_datetime) BETWEEN '$weekStart' AND '$weekEnd'", null, false)
        ->countAllResults();

    // This month (BETWEEN)
    $monthStart = date('Y-m-01');
    $monthEnd   = date('Y-m-t');
    $data['monthAppointmentsCount'] = $appointmentModel
        // ->join('hospitals','hospitals.id=appointments.hospital_id')
        // ->where('hospital_id', session('hospital_id'))
        ->where('doctor_id', $doctorId)
        ->where("DATE(start_datetime) BETWEEN '$monthStart' AND '$monthEnd'", null, false)
        ->countAllResults();
// dd($data);
    // Today’s appointments list
   $data['todayAppointments'] = $appointmentModel
    ->select('appointments.*, patients.name as patient_name,prescription.id as prescription_id,payments.id as payment_id')
      ->join('prescription', 'prescription.appointment_id = appointments.id', 'left')
    // ->join('hospitals','hospitals.id=appointments.hospital_id')
    ->join('patients', 'patients.id = appointments.patient_id')
    ->join('payments','payments.appointment_id=appointments.id','left')
    // ->where('appointments.hospital_id', session('hospital_id'))
    ->where('appointments.doctor_id', $doctorId)
    ->where("DATE(start_datetime) =", $today)   // 👈 correct
    ->orderBy('start_datetime', 'ASC')
    ->findAll();
//     echo $appointmentModel->getLastQuery(); 
// // exit;

    //   dd($data);


    return view('layouts/doctor_dashboard', $data);
}
public function patient_index()
{
    if (!session()->get('isLoggedIn') || session()->get('role') !== 'patient') {
        return redirect()->to('/patient/login');
    }



    $appointmentModel=new AppointmentModel();
    $patientId = session()->get('patient_id');
    $today = date('Y-m-d');

    // Upcoming appointments
     $data['upcomingCount'] = $appointmentModel
       ->join('hospitals','hospitals.id=appointments.hospital_id')
    // ->where('hospital_id', session('hospital_id'))
        ->where('patient_id', $patientId)
    // ->where('start_datetime >=', date('Y-m-d H:i:s'))
        ->where('status', 'Scheduled')
        ->countAllResults();

     // Completed
     $data['completedCount'] = $appointmentModel
        ->join('hospitals','hospitals.id=appointments.hospital_id')
     // ->where('hospital_id', session('hospital_id'))
        ->where('patient_id', $patientId)
        ->where('status', 'completed')
        ->countAllResults();

    // Canceled
    $data['canceledCount'] = $appointmentModel
    ->join('hospitals','hospitals.id=appointments.hospital_id')
        ->where('patient_id', $patientId)
        ->where('status', 'Cancelled')
        ->countAllResults();

     // Upcoming appointments list
     // $patientId = session()->get('patient_id');
     // $today     = date('Y-m-d H:i:s');

$data['upcomingAppointments'] = $appointmentModel
    ->select('appointments.*, users.username as doctor_name')
    // ->join('hospitals','hospitals.id=appointments.hospital_id')
    ->join('doctors', 'doctors.id = appointments.doctor_id')
    ->join('users','users.id=doctors.userid')
    // ->where('hospital_id', session('hospital_id'))
    ->where('appointments.patient_id', $patientId)  // ✅ filter by patient
    // ->where('start_datetime >=', $today) 
    ->where('appointments.status','Scheduled')    
    // ->where()      // ✅ upcoming only
    ->orderBy('start_datetime', 'ASC')
    ->findAll();


    return view('layouts/patient_dashboard', $data);
}

     
}
