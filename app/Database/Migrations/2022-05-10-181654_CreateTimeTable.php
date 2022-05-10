<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTimeTable extends Migration
{
    public function up()
    {
        $this->forge->addField(
            [
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true,
                ],
                'class_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                ],
                'lesson_number' => [
                    'type' => 'INT',
                    'constraint' => 1,
                ],
                'cabinet' => [
                    'type' => 'VARCHAR',
                    'constraint' => '30',
                ],
                'week_day' => [
                    'type' => 'ENUM',
                    'constraint' => ['monday','tuesday','wednesday','thursday','friday'],
                ],
                'lesson_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                ],
                'teacher_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                ],

                'created_at datetime default current_timestamp',
            ]
        );
        $this->forge->addKey('id', true);
        $this->forge->createTable('timetable');
    }

    public function down()
    {
        $this->forge->dropTable('timetable');
    }
}
