<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColumnPinClientTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('clients', [
            'pin' => [
                'type'       => 'VARCHAR',
                'constraint' => 6,
                'null'       => false,
            ],
        ]);
    }

    public function down()
    {
        // Menghapus kolom 'pin' jika rollback
        $this->forge->dropColumn('clients', 'pin');
    }
}
