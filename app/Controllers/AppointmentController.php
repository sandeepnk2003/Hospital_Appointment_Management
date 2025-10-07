<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\DoctorModel;
use App\Models\PatientModel;
use App\Models\AppointmentModel;
use App\Models\userModel;
use App\Models\DoctorAvailabilityModel;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AppointmentController extends ResourceController
{
 public function index()
{
    $appointmentModel = new AppointmentModel();

    $filter = $this->request->getGet('filter');   // today/week/month
    $search = $this->request->getGet('search');   // doctor/patient name
    $date   = $this->request->getGet('date');     // specific date
    $today  = date('Y-m-d');

    $builder = $appointmentModel
        ->select('appointments.*, patients.name as patient_name, users.username as doctor_name')
        ->join('patients', 'patients.id = appointments.patient_id')
        ->join('doctors', 'doctors.id = appointments.doctor_id')
        ->join('users', 'users.id = doctors.userid');

    // ✅ Apply filter: today/week/month
    if ($filter === 'today') {
        $builder->where('DATE(appointments.start_datetime)', $today);
    } elseif ($filter === 'week') {
        $startOfWeek = date('Y-m-d', strtotime('monday this week'));
        $endOfWeek   = date('Y-m-d', strtotime('sunday this week'));
        $builder->where('DATE(appointments.start_datetime) >=', $startOfWeek)
                ->where('DATE(appointments.start_datetime) <=', $endOfWeek);
    } elseif ($filter === 'month') {
        $startOfMonth = date('Y-m-01'); 
        $endOfMonth   = date('Y-m-t');
        $builder->where('DATE(appointments.start_datetime) >=', $startOfMonth)
                ->where('DATE(appointments.start_datetime) <=', $endOfMonth);
    }

    // ✅ Apply search filter (doctor name OR patient name)
    if (!empty($search)) {
        $builder->groupStart()
                ->like('patients.name', $search)
                ->orLike('users.username', $search)
                ->groupEnd();
    }

    // ✅ Apply date filter (exact match on start_datetime)
    if (!empty($date)) {
        $builder->where('DATE(appointments.start_datetime)', $date);
    }

    // ✅ Get results
    $appointments = $builder->orderBy('appointments.start_datetime', 'DESC')->findAll();

    return view('appointment/index', [
        'appointments' => $appointments,
        'filter'       => $filter,
        'search'       => $search,
        'date'         => $date
    ]);
}

    public function create()
{
    $doctorModel = new DoctorModel();
    $patientModel = new PatientModel();
    $appointmentModel = new AppointmentModel();
    $doctorAvailabilityModel = new DoctorAvailabilityModel();

    if ($this->request->getMethod() === 'POST') {
        $startTime = $this->request->getPost('start_time');

        // Convert to DateTime
        $startDateTime = new \DateTime($startTime);
        $endDateTime   = (clone $startDateTime)->modify('+15 minutes');

        // Get the day of the week (e.g., Monday, Tuesday, etc.)
        $dayOfWeek = $startDateTime->format('l');

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

        if ($overlap) {
            return redirect()->back()->withInput()->with('error', 'Doctor is already booked at this time!');
        }
      

        // Save appointment
        $appointmentModel->save([
            'doctor_id'  => $doctorId,
            'patient_id' => $this->request->getPost('patient_id'),
            'start_datetime' => $startDateTime->format('Y-m-d H:i:s'),
            'end_datetime'   => $endDateTime->format('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/appointments')->with('success', 'Appointment created successfully');
    }

    $data['doctors'] = $doctorModel->select('doctors.id, users.username as name')
                       ->join('users', 'users.id = doctors.userid')
                       ->findAll();
    $data['patients'] = $patientModel->findAll();
    return view('appointment/create', $data);
}


    public function store()
    {
        $appointmentModel = new AppointmentModel();

        $doctor_id = $this->request->getPost('doctor_id');
        $patient_id = $this->request->getPost('patient_id');
        $start = date('Y-m-d H:i:s', strtotime($this->request->getPost('start_datetime')));
        $end   = date('Y-m-d H:i:s', strtotime($this->request->getPost('end_datetime')));

        // ❌ Validation: End must be after Start
        if ($end <= $start) {
            return redirect()->back()->with('error', 'End time must be after start time')->withInput();
        }

        // ❌ Validation: Check for doctor double-booking
        $conflict = $appointmentModel
            ->where('doctor_id', $doctor_id)
            ->groupStart()
                ->where("('$start' BETWEEN start_datetime AND end_datetime)")
                ->orWhere("('$end' BETWEEN start_datetime AND end_datetime)")
                ->orWhere("(start_datetime BETWEEN '$start' AND '$end')")
                ->orWhere("(end_datetime BETWEEN '$start' AND '$end')")
            ->groupEnd()
            ->first();

        if ($conflict) {
            return redirect()->back()->with('error', 'This doctor already has an appointment in this time slot')->withInput();
        }

        // ✅ Save appointment
        $appointmentModel->save([
            'doctor_id'      => $doctor_id,
            'patient_id'     => $patient_id,
            'start_datetime' => $start,
            'end_datetime'   => $end,
            'status'         => 'Scheduled'
        ]);

        return redirect()->to('/appointments')->with('success', 'Appointment created successfully');
    }
    public function markComplete($id){
        $appointmentModel = new AppointmentModel();
    $appointmentModel->update($id, ['status' => 'Completed']);

    return redirect()->to(base_url('/doctor_dashboard'))->with('message', 'Appointment marked as completed.');
    }
    public function markCancel($id)
{
    $appointmentModel = new \App\Models\AppointmentModel();
    $appointmentModel->update($id, ['status' => 'Cancelled']);

    return redirect()->back()->with('message', 'Appointment canceled.');
}



public function export()
{
    $appointmentModel = new AppointmentModel();

    // Get query params (same as index)
    $filter = $this->request->getGet('filter');
    $search = $this->request->getGet('search');
    $date   = $this->request->getGet('date');
    $today  = date('Y-m-d');

    // Base query
    $builder = $appointmentModel
        ->select('appointments.id, patients.name as patient_name, users.username as doctor_name, appointments.start_datetime, appointments.end_datetime, appointments.status')
        ->join('patients', 'patients.id = appointments.patient_id')
        ->join('doctors', 'doctors.id = appointments.doctor_id')
        ->join('users', 'users.id = doctors.userid');

    // Apply filter: today / week / month
    if ($filter === 'today') {
        $builder->where('DATE(appointments.start_datetime)', $today);
    } elseif ($filter === 'week') {
        $startOfWeek = date('Y-m-d', strtotime('monday this week'));
        $endOfWeek   = date('Y-m-d', strtotime('sunday this week'));
        $builder->where('DATE(appointments.start_datetime) >=', $startOfWeek)
                ->where('DATE(appointments.start_datetime) <=', $endOfWeek);
    } elseif ($filter === 'month') {
        $startOfMonth = date('Y-m-01');
        $endOfMonth   = date('Y-m-t');
        $builder->where('DATE(appointments.start_datetime) >=', $startOfMonth)
                ->where('DATE(appointments.start_datetime) <=', $endOfMonth);
    }

    // Apply search (doctor or patient)
    if (!empty($search)) {
        $builder->groupStart()
                ->like('patients.name', $search)
                ->orLike('users.username', $search)
                ->groupEnd();
    }

    // Apply specific date filter
    if (!empty($date)) {
        $builder->where('DATE(appointments.start_datetime)', $date);
    }

    // Get filtered data
    $appointments = $builder->orderBy('appointments.start_datetime', 'DESC')->findAll();

    // Build Excel
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Headers
    $sheet->setCellValue('A1', 'ID');
    $sheet->setCellValue('B1', 'Patient');
    $sheet->setCellValue('C1', 'Doctor');
    $sheet->setCellValue('D1', 'Start Date/Time');
    $sheet->setCellValue('E1', 'End Date/Time');
    $sheet->setCellValue('F1', 'Status');

    // Data
    $row = 2;
    foreach ($appointments as $a) {
        $sheet->setCellValue('A' . $row, $a['id']);
        $sheet->setCellValue('B' . $row, $a['patient_name']);
        $sheet->setCellValue('C' . $row, $a['doctor_name']);
        $sheet->setCellValue('D' . $row, $a['start_datetime']);
        $sheet->setCellValue('E' . $row, $a['end_datetime']);
        $sheet->setCellValue('F' . $row, $a['status']);
        $row++;
    }

    $writer = new Xlsx($spreadsheet);
    $filename = 'appointments_filtered_' . date('Y-m-d_H-i-s') . '.xlsx';

    // Send response
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=\"{$filename}\"");
    header('Cache-Control: max-age=0');

    $writer->save('php://output');
    exit();
}

}
