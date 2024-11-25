<?php

namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Models\ClientsModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class ClientControllers extends ResourceController
{
    use ResponseTrait;
    public function getlist()
    {
        $client_model = new ClientsModel();
        $page = $this->request->getVar('page') ?? 1;
        $size = $this->request->getVar('size') ?? 10;
        $offset = ($page - 1) * $size;
        $clients_list = $client_model->select('id_client,nama,email,telepon')->orderBy('created_at', 'DESC')->paginate($size, 'default', $offset);
        $total_client = $client_model->countAllResults();
        $number = ($page <= 0) ? null : $page;
        $totalPages = ($size <= 0) ? null : ceil($total_client / $size);
        $firstPage = ($number === 1);
        $lastPage = ($number === $totalPages);

        return $this->respond(
            array(
                "status" => true,
                "data" => $clients_list,
                "messages" => "Berhasil mengambil data",
                'pagination' => [
                    'page' => $page,
                    'size' => $size,
                    'total_data' => $total_client,
                    'number' => $number,
                    'firstPage' => $firstPage,
                    'lastPage' => $lastPage,
                ],
            )
        );
    }
    public function update_client()
    {
        $id = $this->request->getVar('client_id');
        $modelclient = new ClientsModel();
        if (!$this->validate($modelclient->update_rules(), $modelclient->register_message())) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $clientSelect = $modelclient
            ->select('id')
            ->where('id_client', $this->request->getVar('client_id'))
            ->first();
        if ($clientSelect == null) {
            return $this->respond(
                array(
                    "status" => false,
                    "messages" => "Client tidak diketahui",
                )
            );
        }
        $cek_akun_terdaftar = $modelclient->cek_email_telepon(
            $this->request->getVar('email'),
            $this->request->getVar('telepon'),
            $this->request->getVar('client_id')
        );
        if ($cek_akun_terdaftar["status"] == false) {
            return $this->respond(
                array(
                    "status" => false,
                    "messages" => $cek_akun_terdaftar['msg']
                ),
                400
            );
        }
        $data = [
            'email' => $this->request->getVar('email'),
            'nama' => $this->request->getVar('nama'),
            'tmp_lahir' => $this->request->getVar('tempat_lahir'),
            'tgl_lahir' => $this->request->getVar('tanggal_lahir'),
            'gender' => $this->request->getVar('jk'),
            'telepon' => $this->request->getVar('telepon'),
            
        ];
        if (!$modelclient->update($clientSelect['id'], $data)) {
            return $this->respond(
                array(
                    "status" => false,
                    "messages" => "Gagal mengubah data",
                ),
                500
            );
        }
        return $this->respond(
            array(
                "status" => false,
                "messages" => "Berhasil mengubah data",
            ),
        );
    }
    public function detail_client()
    {
        $rules = [
            'client_id' => 'required'
        ];
        $id = $this->request->getVar('client_id');
        $modelclient = new ClientsModel();
        if (!$this->validate($rules, $modelclient->register_message())) {
            return $this->failValidationErrors($this->validator->getErrors());
        }
        $detaildata = $modelclient
            ->select('id_client,email,nama,tmp_lahir,tgl_lahir,gender,telepon,updated_at')
            ->where('id_client', $id)
            ->first();
        if ($detaildata == null) {
            return $this->respond(
                array(
                    "status" => false,
                    "messages" => "Client tidak ditemukan",
                )
            );
        }
        return $this->respond(
            array(
                "status" => true,
                "messages" => "Client ditemukan",
                "data" => $detaildata
            )
        );
    }
}
