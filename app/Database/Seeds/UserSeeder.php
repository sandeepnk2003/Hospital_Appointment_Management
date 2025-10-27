<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
//         $hospitalModel = new \App\Models\HospitalModel();

// $data2= [
//     [
//         'hospital_name'   => 'CityCare Multispeciality Hospital',
//         'hospital_email'  => 'citycarehospital@gmail.com',
//         'hospital_contact'=> '9876543210',
//         'created_at'      => date('Y-m-d H:i:s'),
//         'updated_at'      => date('Y-m-d H:i:s'),
//     ],
//     [
//         'hospital_name'   => 'GreenLife Medical Center',
//         'hospital_email'  => 'greenlifehospital@gmail.com',
//         'hospital_contact'=> '9123456780',
//         'created_at'      => date('Y-m-d H:i:s'),
//         'updated_at'      => date('Y-m-d H:i:s'),
//     ],
// ];
// $this->db->table('hospitals')->insertBatch($data2);
        $data = [
            'username'      => 'Sandeep',
            'hospital_id'   =>'2',
            'email'         => 'nksandeep44@gmail.com',
            'password' => password_hash('admin@123', PASSWORD_DEFAULT), // 🔒 default password
            'role'          => 'superadmin',
            'phoneno'       =>'8050750015',
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];   
        $this->db->table('users')->insert($data);
                $data2 = [
            'username'      => 'Sandeep',
            'hospital_id'   =>'1',
            'email'         => 'sandeepnk2004@gmail.com',
            'password' => password_hash('admin@123', PASSWORD_DEFAULT), // 🔒 default password
            'role'          => 'superadmin',
            'phoneno'       =>'8050750015',
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];   
        $this->db->table('users')->insert($data2);
             

    }
}
