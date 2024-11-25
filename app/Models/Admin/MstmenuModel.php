<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class MstmenuModel extends Model
{
    protected $table            = 'mstmenu';
    protected $primaryKey       = 'menu_id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nama','path','desc','status','time_created'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
    public function edit_rules()
    {
        return [
            'nama' => 'required',
            'path'=>'required',
            'status'=>'required',
        ];
    }
    public function add_rules()
    {
        return [
            'nama' => 'required|is_unique[mstmenu.nama]',
            'path'=>'required|is_unique[mstmenu.path]',
            'status'=>'required',
        ];
    }
    public function add_message()
    {
        return [
            'nama' => [
                'required' => 'Nama menu wajib diisi.',
                'is_unique' => 'Nama menu sudah terdaftar.',
            ],
            'path' => [
                'required' => 'Path menu wajib diisi.',
                'is_unique' => 'Path menu sudah terdaftar.',
            ],
            'status' => [
                'required' => 'Status menu wajib diisi.',
            ],
        ];
    }
}
