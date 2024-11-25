<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Mstgrup extends Migration
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
        'grupid' => [
            'type' => 'CHAR',
            'constraint' => 36,
            'null' => false,
            'unique' => true,
        ],
        'aplikasiid' => [
            'type' => 'CHAR',
            'constraint' => 36,
            'null' => true,
            'unique' => false,
        ],
        'namagrup' => [
            'null' => false,
            'type' => 'VARCHAR',
            'unique' => true,
            'constraint' => '255',
        ],
        'desc' => [
            'null' => true,
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
    $this->forge->createTable('mstgrup');
    }

    public function down()
    {
      $this->forge->dropTable('mstgrup');
    }
}
