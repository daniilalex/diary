<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ClassesSeed extends Seeder
{
    public function run()
    {
        $this->db->table('classes')->truncate();
        $data = [
            [
                'title' => '10c',
                'max_week_lessons' => 33

            ],
            [
                'title' => '11b',
                'max_week_lessons' => 34
            ],
            [
                'title' => '12c',
                'max_week_lessons' => 35
            ],
            [
                'title' => '10a',
                'max_week_lessons' => 32
            ],

        ];

        $this->db->table('classes')->insertBatch($data);

    }
}
