<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\DoctorModel;
use App\Models\PatientModel;
use App\Models\AppointmentModel;
use App\Models\userModel;

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

    // Counts
    $data['patientCount'] = $patientModel->countAllResults();
    $data['doctorCount']  = $doctorModel->countAllResults();
    $data['totalAppointments'] = $appointmentModel->countAllResults();

    // Today’s appointments
    $today = date('Y-m-d');
    $data['todayAppointments'] = $appointmentModel
        ->where('DATE(start_datetime)', $today)
        ->countAllResults();

    // This week’s appointments (Monday–Sunday)
    $weekStart = date('Y-m-d', strtotime('monday this week'));
    $weekEnd   = date('Y-m-d', strtotime('sunday this week'));
    $data['weekAppointments'] = $appointmentModel
        ->where('DATE(start_datetime) >=', $weekStart)
        ->where('DATE(start_datetime) <=', $weekEnd)
        ->countAllResults();

    // This month’s appointments
    $monthStart = date('Y-m-01');
    $monthEnd   = date('Y-m-t'); // last day of month
    $data['monthAppointments'] = $appointmentModel
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
$doctor = $doctorModel->where('userid', session()->get('user_id'))->first();
$doctorId = $doctor['id'];
$data['id']=$doctorId;
//     print_r($doctorId);
//  dd(session()->get());
    // Today
    $today = date('Y-m-d');
    $data['todayAppointmentsCount'] = $appointmentModel
        ->where('DATE(start_datetime)', $today)
        ->where('doctor_id',$doctorId)
        ->countAllResults();
    
    //  print_r($data);
    // This week (BETWEEN)
    $weekStart = date('Y-m-d', strtotime('monday this week'));
    $weekEnd   = date('Y-m-d', strtotime('sunday this week'));
    $data['weekAppointmentsCount'] = $appointmentModel
        ->where('doctor_id', $doctorId)
        ->where("DATE(start_datetime) BETWEEN '$weekStart' AND '$weekEnd'", null, false)
        ->countAllResults();

    // This month (BETWEEN)
    $monthStart = date('Y-m-01');
    $monthEnd   = date('Y-m-t');
    $data['monthAppointmentsCount'] = $appointmentModel
        ->where('doctor_id', $doctorId)
        ->where("DATE(start_datetime) BETWEEN '$monthStart' AND '$monthEnd'", null, false)
        ->countAllResults();

    // Today’s appointments list
   $data['todayAppointments'] = $appointmentModel
    ->select('appointments.*, patients.name as patient_name')
    ->join('patients', 'patients.id = appointments.patient_id')
    ->where('doctor_id', $doctorId)
    ->where("DATE(start_datetime) =", $today)   // 👈 correct
    ->orderBy('start_datetime', 'ASC')
    ->findAll();
//     echo $appointmentModel->getLastQuery(); 
// // exit;

//         print_r($data);

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
        ->where('patient_id', $patientId)
        // ->where('start_datetime >=', date('Y-m-d H:i:s'))
        ->where('status', 'Scheduled')
        ->countAllResults();

    // Completed
    $data['completedCount'] = $appointmentModel
        ->where('patient_id', $patientId)
        ->where('status', 'completed')
        ->countAllResults();

    // Canceled
    $data['canceledCount'] = $appointmentModel
        ->where('patient_id', $patientId)
        ->where('status', 'Cancelled')
        ->countAllResults();

    // Upcoming appointments list
// $patientId = session()->get('patient_id');
// $today     = date('Y-m-d H:i:s');

$data['upcomingAppointments'] = $appointmentModel
    ->select('appointments.*, users.username as doctor_name')
    ->join('doctors', 'doctors.id = appointments.doctor_id')
    ->join('users','users.id=doctors.userid')
    ->where('appointments.patient_id', $patientId)  // ✅ filter by patient
    // ->where('start_datetime >=', $today) 
    ->where('appointments.status','Scheduled')          // ✅ upcoming only
    ->orderBy('start_datetime', 'ASC')
    ->findAll();


    return view('layouts/patient_dashboard', $data);
}

     
}
