<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\DoctorModel;
use App\Models\PatientModel;
use App\Models\AppointmentModel;
use App\Models\userModel;
use App\Models\HospitalModel;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ApiController extends ResourceController
{
//    protected $formt=JSON;


   public function DoctorApi(){
    $AppointModel= new AppointmentModel();
    $doctorModel=new DoctorModel();
    $doctor=$doctorModel
    // ->withDeleted()
    ->select('doctors.*,users.username as doctors_name')
    ->join('users','users.id=doctors.userid')
    ->join('hospitals','hospitals.id=doctors.hospital_id')
    ->where('hospital_id', session('hospital_id'))
    ->findAll();
   $result =[];
   $today=date('y-m-d');
    foreach($doctor as $doctors){
        $appointments=$AppointModel
        ->join('hospitals','hospitals.id=appointments.hospital_id')
        ->where('hospital_id', session('hospital_id'))
        ->where('doctor_id',$doctors['id'])
        ->where('Date(start_datetime)',$today)
        ->where('status','Completed')
        ->countAllResults();

        $totalAmount=$appointments*$doctors['consultation_fee'];
        $result[]=[
            'doctor_id'=>$doctors['id'],
            'Doctor_name'=>$doctors['doctors_name'],
            'data'=>
            [
            'Today_total _Appointments'=>$appointments,
            'Total_amount'=>$totalAmount,
            ]

        ];
    }
        return $this->response->setJSON([
            'status'=>'success',
             'date'=>$today,
             'data'=>$result
        ]);
   



    // $Appointment=$AppointModel
    // ->select('')
   }
   public function doctorStatsTodayExcel()
{
    $doctorModel = new \App\Models\DoctorModel();
    $appointmentModel = new \App\Models\AppointmentModel();

    $today = date('Y-m-d');

    // Get all doctors
    $doctors = $doctorModel
        ->select('doctors.id, users.username as doctor_name, doctors.consultation_fee')
        ->join('users', 'users.id = doctors.userid')
        ->join('hospitals','hospitals.id=doctors.hospital_id')
        ->where('hospital_id', session('hospital_id'))
        ->findAll();

    // Create Spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Headers
    $sheet->setCellValue('A1', 'Doctor ID');
    $sheet->setCellValue('B1', 'Doctor Name');
    $sheet->setCellValue('C1', 'Consultation Fee');
    $sheet->setCellValue('D1', 'Today Total Appointments');
    $sheet->setCellValue('E1', 'Total Amount');

    $row = 2;

    foreach ($doctors as $doctor) {
        // Count today’s appointments
        $appointments = $appointmentModel
         ->join('hospitals','hospitals.id=appointments.hospital_id')
         ->where('hospital_id', session('hospital_id'))
            ->where('doctor_id', $doctor['id'])
            ->where('DATE(start_datetime)', $today)
            ->where('status','Completed')
            ->countAllResults();

        // Calculate total fee
        $totalAmount = $appointments * $doctor['consultation_fee'];

        // Fill row
        $sheet->setCellValue('A' . $row, $doctor['id']);
        $sheet->setCellValue('B' . $row, $doctor['doctor_name']);
        $sheet->setCellValue('C' . $row, $doctor['consultation_fee']);
        $sheet->setCellValue('D' . $row, $appointments);
        $sheet->setCellValue('E' . $row, $totalAmount);

        $row++;
    }

    // Download Excel
    $writer = new Xlsx($spreadsheet);
    $fileName = 'doctor_stats_today.xlsx';

    // Set response headers
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=\"$fileName\"");
    header('Cache-Control: max-age=0');

    $writer->save('php://output');
    exit;
}
}
