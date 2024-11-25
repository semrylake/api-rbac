<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColumnPinMstPengguna extends Migration
{
    public function up()
    {
        $this->forge->addColumn('mstpengguna', [
            'pin' => [
                'type'       => 'VARCHAR',
                'constraint' => 6,
                'null'       => false,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('mstpengguna', 'pin');
    }
}
