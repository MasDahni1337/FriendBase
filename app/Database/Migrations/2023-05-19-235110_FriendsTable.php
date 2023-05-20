<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FriendsTable extends Migration
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
            ],
            'genderID' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'age' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('friends');
    }

    public function down()
    {
        $this->forge->dropTable('friends');
    }
}