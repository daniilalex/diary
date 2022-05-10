<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsersSeed extends Seeder
{
    public function run()
    {
        $data = [
            [
                'title' => 'Teacher'
            ],
            [
                'title' => 'Admin'
            ],
            [
                'title' => 'Director'
            ],

        ];

        $this->db->table('users')->insertBatch($data);

    }
}
