<?php

namespace App\Controllers;

use App\Models\ClientsModel;
use CodeIgniter\HTTP\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;
use Ramsey\Uuid\Uuid;

class AuthControllers extends ResourceController
{
    protected $format = 'json';


    use ResponseTrait;
    public function register()
    {
        $modelclient = new ClientsModel();
        if (!$this->validate($modelclient->register_rules(), $modelclient->register_message())) {
            // Jika validasi gagal, kirim respons JSON dengan error message
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $client = new ClientsModel();
        $newpasscode = hash_hmac("sha256", $this->request->getVar('password'), getenv('LOGIC_KEY'));
        $data = [
            'id_client' => Uuid::uuid4()->toString(),
            'email' => $this->request->getVar('email'),
            'nama' => $this->request->getVar('nama'),
            'tmp_lahir' => $this->request->getVar('tempat_lahir'),
            'tgl_lahir' => $this->request->getVar('tanggal_lahir'),
            'gender' => $this->request->getVar('jk'),
            'telepon' => $this->request->getVar('telepon'),
            'passcode' => $newpasscode,
            'pin' => $modelclient->generateRandomString(),
        ];
        $client->save($data);
        return $this->respond(array("status" => true, "messages" => "Data berhasil disimpan"));
    }

    public function login()
    {
        $modelclient = new ClientsModel();
        $email = $this->request->getVar('email');
        $password = hash_hmac("sha256", $this->request->getVar('password'), getenv('LOGIC_KEY'));
        if (!$this->validate($modelclient->signin_rules(), $modelclient->register_message())) {
            return $this->respond(
                array(
                    'status' => false,
                    'messages' => 'Proses login gagal',
                    'error' => $this->validator->getErrors()
                )
            );
            // return $this->failValidationErrors($this->validator->getErrors());
        }
        $clientmodel = new ClientsModel();
        $client = $clientmodel->where(['email' => $email, 'passcode' => $password])->first();
        if (empty($client)) {
            return $this->respond(array("status" => false, "messages" => "Email atau password tidak terdaftar"));
        }
        $id = $client["id"];
        $dataupdate = [
            'pin' => $modelclient->generateRandomString()
        ];
        if ($clientmodel->update($id, $dataupdate)) {
            $token = getenv('JWT_SECRET');
            $nowTime = time();
            $exp = $nowTime + 3600 * 1.5; // 1 jam
            $payload = [
                'iss' => getenv('app.baseURL'),
                'sub' => 'logintoken',
                'iat' => $nowTime,
                'exp' => $exp,
                'client' => $client["email"],
            ];
            $token = JWT::encode($payload, $token, 'HS256');
            return $this->respond([
                'status' => true,
                'token' => $token,
                'exp_token' => $exp
            ]);
        } else {
            return $this->respond(array("status" => false, "messages" => "Proses login gagal, terjadi keselahan pada sistem"));
        }
    }
}
