<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Mstmenu extends Migration
{ public function up()
    {
        $this->forge->addField([
            'menu_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'null' => false,
                'unique' => true,
            ],
            'nama' => [
                'null' => false,
                'type' => 'VARCHAR',
                'unique' => true,
                'constraint' => '255',
            ],
            'path' => [
                'null' => false,
                'type' => 'VARCHAR',
                'constraint' => '255',
                'unique' => true,
            ],
            'desc' => [
                'null' => false,
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'status' => [
                'null' => false,
                'type' => 'VARCHAR',
                'constraint' => '20',
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => false
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => false
            ],
        ]);
        $this->forge->addPrimaryKey('menu_id');
        $this->forge->createTable('mstmenu');
    }

    public function down()
    {
        $this->forge->dropTable('mstmenu');
    }
}
