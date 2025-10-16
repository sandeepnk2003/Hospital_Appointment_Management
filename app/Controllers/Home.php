<?php

namespace App\Controllers;
use App\Models\HospitalModel;

class Home extends BaseController
{
    public function index(): string
    {
      $hospitalmodel=new HospitalModel();
      $data['hospitals']=$hospitalmodel
      ->findAll();
        return view('hospital_index',$data);
    }
   public function setHospital()
    {
        $hospitalId = $this->request->getPost('hospital_id');

        if (!$hospitalId) {
            return redirect()->back()->with('error', 'Please select a hospital.');
        }

        $hospitalModel = new HospitalModel();
        $hospital = $hospitalModel->find($hospitalId);

        if (!$hospital) {
            return redirect()->back()->with('error', 'Invalid hospital selected.');
        }

        // ✅ Store hospital info in session
        session()->set([
            // 'hospital_id' => $hospital['id'],
            'hospital_name' => $hospital['hospital_name']
        ]);
        // dd(session()->get('hospital_id'));

        // ✅ Redirect to hospital dashboard or login
        return redirect()->to('/hospitaldashboard');
    }
    public function index1(){
        return view('index');
    }
}
