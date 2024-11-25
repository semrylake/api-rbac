<?php

namespace App\Controllers\Mstpengguna;

use App\Models\Admin\MstPengguna as AdminMstPengguna;
use App\Models\ClientsModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\ResponseTrait;
use Ramsey\Uuid\Uuid;

class MstPengguna extends ResourceController
{
  protected $format = 'json';
  public function __construct()
  {
    $this->modelpengguna = new AdminMstPengguna();
  }
  use ResponseTrait;
  public function add()
  {
    if (!$this->validate($this->modelpengguna->add_rules(), $this->modelpengguna->add_message())) {
      // Jika validasi gagal, kirim respons JSON dengan error message
      return $this->respond(
        array(
          'status' => false,
          'messages' => 'Gagal menyimpan data. Data tidak valid',
          'error' => $this->validator->getErrors()
        )
      );
    }
    $modelclient = new ClientsModel();
    $newpasscode = hash_hmac("sha256", $this->request->getVar('password'), getenv('LOGIC_KEY'));
    $data = [
      'id_pengguna' => Uuid::uuid4()->toString(),
      'email' => $this->request->getVar('email'),
      'username' => $this->request->getVar('username'),
      'nama' => $this->request->getVar('nama'),
      'passcode' => $newpasscode,
      'pin' => $modelclient->generateRandomString(),
    ];
    $this->modelpengguna->save($data);
    return $this->respond(array("status" => true, "messages" => "Data berhasil disimpan"));
  }
  public function getlist()
  {
    $page = $this->request->getVar('page') ?? 1;
    $size = $this->request->getVar('size') ?? 10;
    $offset = ($page - 1) * $size;
    $listpengguna = $this->modelpengguna->select('id_pengguna as kode_pengguna, nama, email, username')->orderBy('created_at', 'DESC')->paginate($size, 'default', $offset);
    $total_pengguna = $this->modelpengguna->countAllResults();
    $number = ($page <= 0) ? null : $page;
    $totalPages = ($size <= 0) ? null : ceil($total_pengguna / $size);
    $firstPage = ($number === 1);
    $lastPage = ($number === $totalPages);

    return $this->respond(
      array(
        "status" => true,
        "data" => $listpengguna,
        "messages" => "Berhasil mengambil data",
        'pagination' => [
          'page' => $page,
          'size' => $size,
          'total_data' => $total_pengguna,
          'number' => $number,
          'firstPage' => $firstPage,
          'lastPage' => $lastPage,
        ],
      )
    );
  }
  public function delete_data()
  {
    $kode = $this->request->getVar('kode');
    $post = $this->modelpengguna->select('id')->where('id_pengguna', $kode)->first();

    if ($post) {
      $this->modelpengguna->where('id', $post["id"])->delete();
      return $this->respond(array("status" => true, "messages" => "Data berhasil dihapus"));
    } else {
      return $this->respond(array("status" => false, "messages" => "Data tidak ditemukan"));
    }
  }

  public function update_data()
  {
    $kode = $this->request->getVar('kode');
    $post = $this->modelpengguna->select('id')->where('id_pengguna', $kode)->first();
    // var_dump( $post);
    // die;
    if (!empty($post)) {
      if (!$this->validate($this->modelpengguna->edit_rules(), $this->modelpengguna->add_message())) {
        return $this->respond(
          array(
            'status' => false,
            'messages' => 'Gagal menyimpan data. Data tidak valid',
            'error' => $this->validator->getErrors()
          )
        );
      }

      $nama = $this->request->getVar('nama');
      $username = $this->request->getVar('username');
      $email = $this->request->getVar('email');
      $password = $this->request->getVar('password');
      $data = array();
      if (strlen($nama) > 0) {
        $data["nama"] = $nama;
      }
      if (strlen($email) > 0) {
        $post3 = $this->modelpengguna->select('id')
          ->where('email', $email)
          ->where('id_pengguna !=', $kode)->first();
        if ($post3) {
          $errordata["email"] = "Email sudah terdaftar.";
          return $this->respond(
            array(
              'status' => false,
              'messages' => 'Gagal menyimpan data. Data tidak valid',
              'error' => $errordata
            )
          );
        }
        $data["email"] = $email;
      }
      if (strlen($username) > 0) {
        $post2 = $this->modelpengguna->select('id')
          ->where('username', $username)
          ->where('id_pengguna !=', $kode)->first();
        if ($post2) {
          $errordata["username"] = "Username sudah terdaftar.";
          return $this->respond(
            array(
              'status' => false,
              'messages' => 'Gagal menyimpan data. Data tidak valid',
              'error' => $errordata
            )
          );
        }
        $data["username"] = $username;
      }
      if (strlen($password) > 0) {
        if (strlen($password < 6)) {
          $errordata["password"] = "Password terlalu singkat minimal 6 karakter.";
          return $this->respond(
            array(
              'status' => false,
              'messages' => 'Gagal menyimpan data. Data tidak valid',
              'error' => $errordata
            )
          );
        }
        $pattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_])(?!.*\s).+$/';
        if (preg_match($pattern, $password)) {
          $newpasscode = hash_hmac("sha256", $password, getenv('LOGIC_KEY'));
          $data["password"] = $newpasscode;
        } else {
          $errordata["password"] = "Password harus mengandung setidaknya satu angka, satu simbol, satu huruf besar, satu huruf kecil, dan tidak boleh mengandung spasi.";
          return $this->respond(
            array(
              'status' => false,
              'messages' => 'Gagal menyimpan data. Data tidak valid',
              'error' => $errordata
            )
          );
        }
      }

      try {
        $this->modelpengguna->update($post["id"], $data);
        return $this->respond(array("status" => true, "messages" => "Data berhasil diupdate"));
      } catch (\Throwable $th) {
        var_dump($th);
        die;
      }
    } else {
      return $this->respond(array("status" => false, "messages" => "Gagal update data. Data tidak ditemukan"));
    }
  }
}
