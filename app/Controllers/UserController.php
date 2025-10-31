<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;
use App\Models\DoctorModel;
use App\Models\PatientModel;
use App\Models\AppointmentModel;
use App\Models\VisitModel;
use App\Models\HospitalModel;
use App\Models\UserHospiJunctionModel;

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
        $users = $userModel
    ->join('userhospital_junction', 'userhospital_junction.userid = users.id')
    ->where('userhospital_junction.hospital_id', session('hospital_id'))
    ->findAll();

// ✅ Fetch all doctor user IDs globally (not just this hospital)
$doctorRecords = $doctorModel->select('userid')->findAll();
$doctorUserIds = array_column($doctorRecords, 'userid');

// ✅ Filter users who are not doctors yet
$filteredUsers = array_filter($users, function ($user) use ($doctorUserIds) {
    return !in_array($user['id'], $doctorUserIds);
});

// ✅ Pass both: all users and list of doctorUserIds
return view('users/index', [
    'users'         => $users, // show all users of hospital
    'doctorUserIds' => $doctorUserIds // to check in view if doctor info exists
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
    $userModel = new \App\Models\UserModel();

    $hospitalId = (int) session('hospital_id');
    if (!$hospitalId) {
        return redirect()->back()->with('error', 'Please select a hospital first.');
    }

    // // Get input data
    // $email = $this->request->getPost('email');

    // Define validation rules
    $rules = [
        'username' => 'required|min_length[3]',
        'email'    => [
            'rules'  => 'required|valid_email',
            'errors' => [
                'required'    => 'Email is required',
                'valid_email' => 'Please enter a valid email address',
            ],
        ],
        'password' => 'required|min_length[6]',
        'role'     => 'required'
    ];

    // Run normal validation first
    if (!$this->validate($rules)) {
        return redirect()->to('/users/create')
               ->withInput()
               ->with('validation', $validation);
    }

    // 🔹 Custom inline validation for email + hospital_id
     $userModel = new UserModel();  
        $junctionModel = new UserHospiJunctionModel();

        $email = $this->request->getPost('email');
        // $hospitalId = $this->request->getPost('hospital_id');

        // Step 1️⃣: Check if user exists
        $user = $userModel->where('email', $email)->first();

        if (!$user) {
            // Step 2️⃣: Create new user if not exists
            $userData = [
                'username' => $this->request->getPost('username'),
                'email'    => $email,
                'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'role'     => $this->request->getPost('role'),
                'phoneno'  => $this->request->getPost('phoneno'),
            ];
            $userModel->insert($userData);
            $userId = $userModel->getInsertID();
        } else {
            // Step 3️⃣: User exists, get ID
            $userId = $user['id'];
        }

        // Step 4️⃣: Check if this user-hospital link already exists
        $exists = $junctionModel
            ->where('userid', $userId)
            ->where('hospital_id', $hospitalId)
            ->first();

        if ($exists) {
            // Step 5️⃣: Already linked
            return redirect()->back()->with('info', 'User already linked to this hospital');
        }

        // Step 6️⃣: Create new link
        $junctionModel->insert([
            'hospital_id' => $hospitalId,
            'userid'      => $userId,
            'created_at' =>date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/users')->with('success', 'User successfully added to hospital');
    
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
