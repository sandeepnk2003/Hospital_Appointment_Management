<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'username'      => 'Sandeep',
            'email'         => 'nksandeep44@gmail.com',
            'password' => password_hash('admin@123', PASSWORD_DEFAULT), // 🔒 default password
            'role'          => 'superadmin',
            'phoneno'       =>'8050750015',
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];

       
        $this->db->table('users')->insert($data);
    }
}
