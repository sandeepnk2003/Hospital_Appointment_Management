<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;
use App\Models\DoctorModel;


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
}
