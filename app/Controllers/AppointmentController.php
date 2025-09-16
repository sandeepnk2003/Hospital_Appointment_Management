<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\DoctorModel;
use App\Models\PatientModel;
use App\Models\AppointmentModel;
use App\Models\userModel;

class AppointmentController extends ResourceController
{
  public function index()
    {
        $appointmentModel = new AppointmentModel();

        $data['appointments'] = $appointmentModel
            ->select('appointments.*, patients.name as patient_name, users.username as doctor_name')
            ->join('patients', 'patients.id = appointments.patient_id')
            ->join('doctors', 'doctors.id = appointments.doctor_id')
            ->join('users','users.id=doctors.userid')
            ->orderBy('appointments.start_datetime', 'ASC')
            ->findAll();

        return view('appointment/index', $data);
    }
    public function create()
{
    $doctorModel = new DoctorModel();
    $patientModel = new PatientModel();
    $appointmentModel = new AppointmentModel();

    if ($this->request->getMethod() === 'POST') {
        $startTime = $this->request->getPost('start_time');

        // Convert to DateTime
        $startDateTime = new \DateTime($startTime);
        $endDateTime   = (clone $startDateTime)->modify('+15 minutes');

        // Validation: Check if doctor is already booked
        $doctorId = $this->request->getPost('doctor_id');
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

    return redirect()->back()->with('message', 'Appointment marked as completed.');
    }
    public function markCancel($id)
{
    $appointmentModel = new \App\Models\AppointmentModel();
    $appointmentModel->update($id, ['status' => 'Cancelled']);

    return redirect()->back()->with('message', 'Appointment canceled.');
}
}
