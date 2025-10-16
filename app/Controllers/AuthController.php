<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;
use App\Models\PatientModel;
use App\Models\OtpModel;
use App\Models\DoctorModel;
use App\Models\HospitalModel;




class AuthController extends ResourceController
{
   public function login()
    {
        
// echo $this->request->getMethod(); 
// exit;
        if ($this->request->getMethod() === 'POST') {
            $userModel = new UserModel();
            $session   = session();
            $email    = $this->request->getVar('email');
            $password = $this->request->getVar('password');
            // echo "$email";
            // echo $password;

            $user = $userModel->where('email', $email)
            ->where('hospital_id',session('hospital_id'))
            ->first();
        
            // print_r($user);

            if ($user && password_verify($password, $user['password'])) {
                $session->set([
                    'user_id'    => $user['id'],
                    'username'   => $user['username'],
                    'role'       => $user['role'],
                    'isLoggedIn' => true
                ]); 
                if(session()->get('role')==='doctor'){
                      return redirect()->to('/doctor_dashboard');   
                }

                return redirect()->to('/dashboard');
            } else {
                return redirect()->back()->with('error', 'Invalid login credentials');
            }
        }

        return view('auth/login');
    }

    public function logout()
    {
         $session = session();
         $hospitalData = [
        'hospital_id'   => $session->get('hospital_id'),
        'hospital_name' => $session->get('hospital_name'),
    ];
    // dd($hospitalData);
            // Remove only user-specific data
    $session->remove(['user_id', 'email', 'username', 'role', 'isLoggedIn']);
        $newSession = session();
    $newSession->set($hospitalData);
    //    dd(session()->get());
        return redirect()->to('auth/login');
    }
        public function Patient_login()
    {
        if ($this->request->getMethod() === 'POST') {
            $email = $this->request->getPost('email');

            $patientModel = new PatientModel();
            $patient = $patientModel
            // ->join('hospitals','hospitals.id=patients.hospital_id')
            ->where('email', $email)
            ->where('hospital_id', session('hospital_id'))
            ->first();
            // $patient['session']=session('hospital_id');
//    dd($patient);
            if (!$patient) {
                return redirect()->back()->with('error', 'No patient found with this email');
            }

            // Generate OTP
            $otp = rand(100000, 999999);

            $otpModel = new OtpModel();
            $otpModel->insert([
                'hospital_id'=>session('hospital_id'),
                'patient_id' => $patient['id'],
                'otp'        => $otp,
                'expires_at' => date('Y-m-d H:i:s', strtotime('+5 minutes')),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            // ⚠️ For now just show OTP (later we can send via email/SMS)
            return view('auth/patient_otp', [
                'email' => $email,
                'otp'   => $otp   // for testing only
            ]);
        }

        return view('auth/patient_login');
    }

    public function Patient_verifyOtp()
    {
        if ($this->request->getMethod() === 'POST') {
            $email = $this->request->getPost('email');
            $otp   = $this->request->getPost('otp');

            $patientModel = new PatientModel();
            $patient = $patientModel
            //  ->join('hospitals','hospitals.id=patients.hospital_id')
            ->where('hospital_id', session('hospital_id'))
            ->where('email', $email)->first();

            if (!$patient) {
                return redirect()->back()->with('error', 'Invalid email');
            }

            $otpModel = new OtpModel();
            $otpRecord = $otpModel
                // ->join('hospitals','hospitals.id=otps.hospital_id')
                // ->where('hospital_id', session('hospital_id'))
                ->where('patient_id', $patient['id'])
                ->where('otp', $otp)
                ->where('expires_at >=', date('Y-m-d H:i:s'))
                ->orderBy('id', 'DESC')
                ->first();

           if ($otpRecord) {
         session()->set([
        'patient_id'   => $patient['id'],
        'patient_name' => $patient['name'],
        'role'         => 'patient',   // ✅ role
        'isLoggedIn'   => true,        // ✅ consistency with other logins
        'isPatient'    => true
    ]);
//    dd(session()->get());

    return redirect()->to('/patient/dashboard');
}

             else {
                return redirect()->back()->with('error', 'Invalid or expired OTP');
            }
        }

        return redirect()->to('/patient/login');
    }

    public function Patient_logout()
    {
         $session = session();
         $hospitalData = [
              'hospital_id'   => $session->get('hospital_id'),
              'hospital_name' => $session->get('hospital_name'),
         ];
         $session->remove(['patient_id', 'patient_name', 'role', 'isPatient', 'isLoggedIn']);
         $newSession = session();
         $newSession->set($hospitalData);
         return redirect()->to('/patient/login');
    }


}
