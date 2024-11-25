<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MstPengguna extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'constraint' => 255,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'id_pengguna' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'null' => false,
                'unique' => true,
            ],
            'email' => [
                'null' => false,
                'type' => 'VARCHAR',
                'unique' => true,
                'constraint' => '255',
            ],
            'username' => [
                'null' => false,
                'type' => 'VARCHAR',
                'unique' => true,
                'constraint' => '255',
            ],
            'nama' => [
                'null' => false,
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'passcode' => [
                'null' => false,
                'type' => 'VARCHAR',
                'constraint' => '255',
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
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('mstpengguna');
    }

    public function down()
    {
        $this->forge->dropTable('mstpengguna');
    }
}
