<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;
use App\Models\DoctorModel;
use App\Models\PatientModel;
use App\Models\AppointmentModel;
use App\Models\VisitModel;


class UserController extends ResourceController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    // Show all users
    public function index()
    {
      
        $userModel   = new UserModel();
        $doctorModel = new DoctorModel();

        // Fetch all users
        $users = $userModel->findAll();

        // Fetch all doctor user IDs
        $doctorRecords = $doctorModel->select('userid')->findAll(); 
        $doctorUserIds = array_column($doctorRecords, 'userid'); // extract IDs into plain array

        // Pass to view
        return view('users/index', [
            'users'         => $users,
            'doctorUserIds' => $doctorUserIds
        ]);
    }


    

    // Show create form
    public function create()
    {
        return view('users/create');
    }

    // Save new user
    public function store()
{
    $validation = \Config\Services::validation();

    $rules = [
        'username' => 'required|min_length[3]',
        'email'    => 'required|valid_email|is_unique[users.email]',
        'password' => 'required|min_length[6]',
        'role'     => 'required'
    ];

    if (!$this->validate($rules)) {
        return redirect()->to('/users/create')
            ->withInput()
            ->with('validation', $validation); // send validation object
    }

    $data = [
        'username' => $this->request->getPost('username'),
        'email'    => $this->request->getPost('email'),
        'password' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
        'role'     => $this->request->getPost('role'),
        'phoneno'  =>$this->request->getPost('phoneno'),
    ];
    $this->userModel->save($data);

    return redirect()->to('/users')->with('success', 'User created successfully.');
}


    // Show edit form
    public function edit($id=null)
    {
        $data['user'] = $this->userModel->find($id);
        return view('users/edit', $data);
    }

    // Update user
    public function update($id=null)
    {
        $data = [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'role'     => $this->request->getPost('role'),
        ];

        if ($this->request->getPost('password')) {
            $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        $this->userModel->update($id, $data);

        return redirect()->to('/users')->with('success', 'User updated successfully');
    }

    // Soft delete user
    public function delete($id=null)
    {
        $this->userModel->delete($id);
        return redirect()->to('/users')->with('success', 'User deleted successfully');
    }
     public function index2($patientId)
    {
        $patientModel = new PatientModel();
        $visitModel   = new VisitModel();

        // Fetch patient details
        $patient = $patientModel->find($patientId);
        if (!$patient) {
            return redirect()->back()->with('error', 'Patient not found');
        }

        // Fetch visits
        $visits = $visitModel->where('patient_id', $patientId)
                             ->orderBy('date', 'ASC')
                             ->findAll();

        // Prepare chart data
        $visitDates = [];
        $weightData = [];
        $systolicData = [];
        $diastolicData = [];

        foreach($visits as $v){
            $visitDates[] = date('d M', strtotime($v['date']));
            $weightData[] = floatval(str_replace('kg','',$v['weight']));
            $bpParts = explode('/', $v['blood_pressure']);
            $systolicData[]  = intval($bpParts[0] ?? 0);
            $diastolicData[] = intval($bpParts[1] ?? 0);
        }

        return view('users/index2', [
            'patient'       => $patient,
            'visits'        => $visits,
            'visitDates'    => $visitDates,
            'weightData'    => $weightData,
            'systolicData'  => $systolicData,
            'diastolicData' => $diastolicData
        ]);
    }
}
