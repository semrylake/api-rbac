<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class MstPengguna extends Model
{
    protected $table            = 'mstpengguna';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_pengguna', 'email', 'username', 'nama', 'passcode','pin'];

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
            'username' => 'required|min_length[5]|is_unique[mstpengguna.username]|regex_match[/^\S*$/]',
            'email' => 'required|valid_email|is_unique[mstpengguna.email]',
            'password' => 'required|min_length[6]|regex_match[/(?=.*\d)(?=.*[\W_])(?!.*\s)/]',
            'nama' => 'required',
        ];
    }
    public function edit_rules()
    {
        return [
            'username' => 'min_length[5]|regex_match[/^\S*$/]',
            'email' => 'valid_email',
            // 'password' => 'min_length[6]|regex_match[/(?=.*\d)(?=.*[\W_])(?!.*\s)/]',
        ];
    }
    public function add_message()
    {
        return [
            'nama' => [
                'required' => 'Nama wajib diisi.',
            ],
            'username' => [
                'required' => 'Username wajib diisi.',
                'is_unique' => 'Username sudah terdaftar.',
                'min_length' => 'Username terlalu singkat minimal 5 karakter.',
                'regex_match' => 'Username tidak boleh mengandung spasi.',
            ],
            'email' => [
                'required' => 'Email wajib diisi.',
                'is_unique' => 'Email sudah terdaftar.',
                'valid_email' => 'Format email tidak valid.',
            ],
            'password' => [
                'required' => 'Password wajib diisi.',
                'min_length' => 'Password terlalu singkat minimal 6 karakter.',
                'regex_match' => 'Password harus mengandung setidaknya satu angka, satu simbol, satu huruf besar, satu huruf kecil, dan tidak boleh mengandung spasi.',
            ],
        ];
    }
}
