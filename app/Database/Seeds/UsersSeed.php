<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsersSeed extends Seeder
{
    public function run()
    {
        $data = [
            [
                'email' => 'director@user.com',
                'password' => md5('cao1234'),
                'firstname' => 'John',
                'lastname' => 'Black',
                'type'=> 'director'
            ],
            [
                'email' => 'Nick@user.com',
                'password' => md5('cao12345'),
                'firstname' => 'Nick',
                'lastname' => 'Grey',
                'type'=> 'teacher'
            ],
            [
                'email' => 'Ray@user.com',
                'password' => md5('cao12345'),
                'firstname' => 'Ray',
                'lastname' => 'Stanford',
                'type'=> 'teacher'
            ],
            [
                'email' => 'elza@user.com',
                'password' => md5('cao12345'),
                'firstname' => 'Elza',
                'lastname' => 'Moon',
                'type'=> 'teacher'
            ],
            [
                'email' => 'oliver@user.com',
                'password' => md5('cao123456'),
                'firstname' => 'Oliver',
                'lastname' => 'Smith',
                'type'=> 'student'
            ],
            [
                'email' => 'Christopher@user.com',
                'password' => md5('cao123456'),
                'firstname' => 'Christopher',
                'lastname' => 'Long',
                'type'=> 'student'
            ],
            [
                'email' => 'nina@user.com',
                'password' => md5('cao123456'),
                'firstname' => 'Nina',
                'lastname' => 'Kravitz',
                'type'=> 'student'
            ],
            [
                'email' => 'samanta@user.com',
                'password' => md5('cao123456'),
                'firstname' => 'Samanta',
                'lastname' => 'Smith',
                'type'=> 'student'
            ],

        ];
$this->db->table('users')->truncate();
        $this->db->table('users')->insertBatch($data);

    }
}
