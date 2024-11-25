<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientsModel extends Model
{
    protected $table            = 'clients';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_client', 'email', 'nama', 'tmp_lahir', 'gender', 'telepon', 'passcode', 'pin'];

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

    public function signin_rules()
    {
        return [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[8]'
        ];
    }
    function generateRandomString($length = 6)
    {
        $characters = '01234567890';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
    public function register_rules()
    {
        return [
            'nama' => 'required|min_length[5]|max_length[30]',
            'email'    => 'required|valid_email|is_unique[clients.email]',
            'password'     => 'required|min_length[8]',
            // 'password'     => 'required|min_length[8]|regex_match[/(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[\W_])(?!.*\s)/]',
            'tempat_lahir' => 'required|min_length[3]',
            'tanggal_lahir' => 'required|valid_date[Y-m-d]',
            'jk' => 'required|max_length[1]',
            'telepon' => 'required|is_unique[clients.telepon]|regex_match[/^\+?\d{10,15}$/]|regex_match[/^\S*$/]',
        ];
    }
    public function update_rules()
    {
        return [
            'client_id' => 'required',
            'nama' => 'required|min_length[5]|max_length[30]',
            'email'    => 'required|valid_email',
            'tempat_lahir' => 'required|min_length[3]',
            'tanggal_lahir' => 'required|valid_date[Y-m-d]',
            'jk' => 'required|max_length[1]',
            'telepon' => 'required|regex_match[/^\+?\d{10,15}$/]|regex_match[/^\S*$/]',
        ];
    }
    public function register_message()
    {
        return [
            'client_id' => [
                'required' => 'Id client wajib diisi.',
            ],
            'nama' => [
                'required' => 'Nama wajib diisi.',
                'min_length' => 'Nama terlalu singkat minimal 5 karakter.',
                'max_length' => 'Nama terlalu penjang minimal 30 karakter.',
            ],
            'email' => [
                'required' => 'Email wajib diisi.',
                'valid_email' => 'Format email tidak sesuai',
                'is_unique' => 'Email sudah terdaftar.',
            ],
            'password' => [
                'required' => 'Password wajib diisi.',
                'min_length' => 'Password harus memiliki minimal 8 karakter.',
                'regex_match' => 'Password harus mengandung setidaknya satu angka, satu simbol, satu huruf besar, satu huruf kecil, dan tidak boleh mengandung spasi.',
            ],
            'tempat_lahir' => [
                'required' => 'Tempat lahir wajib diisi.',
                'min_length' => 'Tempat lahir terlalu singkat minimal 3 karakter.',
            ],
            'tanggal_lahir' => [
                'required' => 'Tanggal lahir wajib diisi.',
                'valid_date' => 'Format tanggal tidak valid, gunakan format Y-m-d (contoh: 2023-01-01).',
            ],
            'telepon' => [
                'required' => 'Nomor telepon/WhatsApp wajib diisi.',
                'min_length' => 'Nomor telepon/WhatsApp terlalu singkat minimal 6 karakter.',
                'is_unique' => 'Nomor telepon/WhatsApp sudah terdaftar.',
                'regex_match' => 'Format nomor telepon tidak valid. Nomor harus terdiri dari 10 hingga 15 digit, tidak mengandung spasi, dan opsional menggunakan tanda "+" di awal.',
            ],
            'jk' => [
                'required' => 'Jenis kelamin wajib diisi.',
                'max_length' => 'Format jenis kelamin tidak valid harus L/P',
            ],
        ];
    }
    public function cek_email_telepon($email, $telepon, $id)
    {
        $db = db_connect('default');
        $where_condition = "";
        $where_condition2 = "";
        $arrayWhere1 = array();
        $arrayWhere2 = array();
        if ($id && strlen($id) > 0) {
            if (strlen($where_condition) > 0) {
                $where_condition .= " AND ";
                $where_condition2 .= " AND ";
            } else {
                $where_condition .= " WHERE ";
                $where_condition2 .= " WHERE ";
            }
            $where_condition .= "id_client != ? ";
            $where_condition2 .= "id_client != ? ";
            array_push(
                $arrayWhere1,
                array(
                    $id
                )
            );
            array_push(
                $arrayWhere2,
                array(
                    $id
                )
            );
        }
        if ($email && strlen($email) > 0) {
            if (strlen($where_condition) > 0) {
                $where_condition .= " AND ";
            } else {
                $where_condition .= " WHERE ";
            }
            $where_condition .= "email = ? ";
            array_push(
                $arrayWhere1,
                array(
                    $email
                )
            );
        }

        $sql1 = "SELECT id from clients " . $where_condition;
        $rResult1 = $this->db->query($sql1, $arrayWhere1);
        $row1 = $rResult1->getRowArray();
        if ($row1 != null) {
            return array(
                'status' => false,
                'msg' => 'email sudah terdaftar',
            );
        }

        if ($telepon && strlen($telepon) > 0) {
            if (strlen($where_condition2) > 0) {
                $where_condition2 .= " AND ";
            } else {
                $where_condition2 .= " WHERE ";
            }
            $where_condition2 .= "telepon = ? ";
            array_push(
                $arrayWhere2,
                array(
                    $telepon
                )
            );
        }

        $sql2 = "SELECT id from clients " . $where_condition2;
        $rResult1 = $this->db->query($sql2, $arrayWhere2);
        $row2 = $rResult1->getRowArray();
        if ($row2 != null) {
            return array(
                'status' => false,
                'msg' => 'Nomor telepon sudah terdaftar',
            );
        }
        return array(
            'status' => true,
        );
    }
}
