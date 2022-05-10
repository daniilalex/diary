<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStudentsTable extends Migration
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
                    'constraint' => 25,
                ],
                'class' => [
                    'type' => 'VARCHAR',
                    'constraint' => 25,
                ],
                'description' => [
                    'type' => 'VARCHAR',
                    'constraint' => 250,
                ]
            ]
        );
        $this->forge->addKey('id', true);
        $this->forge->createTable('students');
    }

    public function down()
    {
        $this->forge->dropTable('students');
    }
}
