<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LessonsSeed extends Seeder
{
    public function run()
    {
        $data = [
            [
                'title' => 'math',


            ],
            [
                'title' => 'english',

            ],
            [
                'title' => 'history',

            ],
            [
                'title' => 'chemistry',

            ],
            [
                'title' => 'biology',


            ],
            [
                'title' => 'geography',

            ],
            [
                'title' => 'spanish',

            ],
            [
                'title' => 'physics',

            ],
            [
                'title' => 'programming',

            ],
            [
                'title' => 'economic',

            ],
            [
                'title' => 'french',

            ],

        ];
        $this->db->table('lessons')->truncate();
        $this->db->table('lessons')->insertBatch($data);

    }
}
