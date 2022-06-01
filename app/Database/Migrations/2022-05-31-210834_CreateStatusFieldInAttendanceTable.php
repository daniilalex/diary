<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStatusFieldInAttendanceTable extends Migration
{
    public function up()
    {
        $fields = [
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['late', 'missing'],
            ],
        ];

        $this->forge->addColumn('attendance', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('attendance', 'status');
    }
}