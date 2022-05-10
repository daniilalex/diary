<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTeacherTable extends Migration
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
             ] ,
             'name' => [
                 'type' => 'VARCHAR',
                 'constraint' => 250,
             ],
                'study_area' => [
                    'type' => 'VARCHAR',
                    'constraint' => 250,
                ],
                'description' => [
                    'type' => 'VARCHAR',
                    'constraint' => 250,
                ]
            ]
        );
        $this->forge->addKey('id', true);
        $this->forge->createTable('teachers');
    }

    public function down()
    {
        $this->forge->dropTable('teachers');
    }
}
