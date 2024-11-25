<?php

namespace App\Controllers\Mstgroup;

use App\Models\Admin\Mstgrup as AdminMstgrup;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\ResponseTrait;
use Ramsey\Uuid\Uuid;

class Mstgrup extends ResourceController
{
  protected $format = 'json';
  public function __construct()
  {
    $this->mstmodel = new AdminMstgrup();
  }

  use ResponseTrait;
  public function tambah()
  {
    if (!$this->validate($this->mstmodel->add_rules(), $this->mstmodel->add_message())) {
      // Jika validasi gagal, kirim respons JSON dengan error message
      return $this->respond(
        array(
          'status' => false,
          'messages' => 'Gagal menyimpan data. Data tidak valid',
          'error' => $this->validator->getErrors()
        )
      );
    }
    $desc = null;
    if (strlen($this->request->getVar('desc')) > 0) {
      $desc = $this->request->getVar('desc');
    }
    $data = [
      'grupid' => Uuid::uuid4()->toString(),
      'namagrup' => $this->request->getVar('nama'),
      'aplikasiid' => getenv('APP_ID'),
      'desc' => $desc,
    ];
    $this->mstmodel->save($data);
    return $this->respond(array("status" => true, "messages" => "Data berhasil disimpan"));
  }
  public function getlist(){
    $page = $this->request->getVar('page') ?? 1;
    $size = $this->request->getVar('size') ?? 5;
    $offset = ($page - 1) * $size;
    $listpengguna = $this->mstmodel->select('grupid as kode, namagrup')->orderBy('created_at', 'DESC')->paginate($size, 'default', $offset);
    $total_pengguna = $this->mstmodel->countAllResults();
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
}
