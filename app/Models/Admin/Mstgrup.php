<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class Mstgrup extends Model
{
  protected $table            = 'mstgrup';
  protected $primaryKey       = 'id';
  protected $useAutoIncrement = true;
  protected $returnType       = 'array';
  protected $useSoftDeletes   = false;
  protected $protectFields    = true;
  protected $allowedFields    = ['grupid', 'aplikasiid', 'namagrup', 'desc'];

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

  public function add_rules()
  {
    return [
      'nama' => 'required|is_unique[mstgrup.namagrup]',
    ];
  }
  public function add_message()
  {
    return [
      'nama' => [
        'required' => 'Nama grup wajib diisi.',
        'is_unique' => 'Nama grup sudah terdaftar.',
      ],
    ];
  }
}
