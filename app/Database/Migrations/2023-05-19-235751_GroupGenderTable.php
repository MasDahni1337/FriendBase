<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class GroupGenderTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('groupGender');
    }

    public function down()
    {
        $this->forge->dropTable('groupGender');
    }
}
