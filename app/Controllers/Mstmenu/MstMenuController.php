<?php

namespace App\Controllers\Mstmenu;

use App\Models\Admin\MstmenuModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\ResponseTrait;
use Ramsey\Uuid\Uuid;

class MstMenuController extends ResourceController
{
    protected $format = 'json';
    public function __construct(){
        $this->mstmenu_model = new MstmenuModel();
    }

    use ResponseTrait;
    public function update_menu()
    {
        $menuId = $this->request->getVar('kode');
        $post = $this->mstmenu_model->find($menuId);

        if($post){
            if (!$this->validate($this->mstmenu_model->edit_rules(), $this->mstmenu_model->add_message())) {
                // Jika validasi gagal, kirim respons JSON dengan error message
                return $this->respond(
                    array(
                        'status' => false,
                        'messages' => 'Gagal menyimpan data. Data tidak valid',
                        'error' => $this->validator->getErrors()
                    )
                );
            }
    
            $nama = $this->request->getVar('nama');
            $path = $this->request->getVar('path');
            $desc = $this->request->getVar('desc');
            $status = $this->request->getVar('status');
            $data = array();
            if(strlen($nama)>0){
                $data["nama"] = $nama;
            }
            if(strlen($path)>0){
                $data["path"] = $path;
            }
            if(strlen($desc)>0){
                $data["desc"] = $desc;
            }
            if(strlen($status)>0){
                $data["status"] = $status;
            }
            try {
                //code...
                $this->mstmenu_model->update($post["menu_id"],$data);
                return $this->respond(array("status" => true, "messages" => "Data berhasil diupdate"));
            } catch (\Throwable $th) {
                //throw $th;
                var_dump($th);
                die;
            }
        }else{
            return $this->respond(array("status" => false, "messages" => "Gagal update menu, menu tidak ditemukan"));
        }
    }
    public function add()
    {
        if (!$this->validate($this->mstmenu_model->add_rules(), $this->mstmenu_model->add_message())) {
            // Jika validasi gagal, kirim respons JSON dengan error message
            return $this->respond(
                array(
                    'status' => false,
                    'messages' => 'Gagal menyimpan data. Data tidak valid',
                    'error' => $this->validator->getErrors()
                )
            );
        }
        $data = [
            'menu_id' => Uuid::uuid4()->toString(),
            'nama' => $this->request->getVar('nama'),
            'path' => $this->request->getVar('path'),
            'desc' => $this->request->getVar('desc'),
            'status' => $this->request->getVar('status'),
            // 'time_created' => date('Y-m-d H:i:s'),
        ];
        try {
            //code...
            $this->mstmenu_model->save($data);
            return $this->respond(array("status" => true, "messages" => "Data berhasil disimpan"));
        } catch (\Throwable $th) {
            //throw $th;
            var_dump($th);
            die;
        }
    }

    public function getlist()
    {
        $page = $this->request->getVar('page') ?? 1;
        $size = $this->request->getVar('size') ?? 10;
        $offset = ($page - 1) * $size;
        $menulist = $this->mstmenu_model->select('menu_id as kode_menu,nama,desc,path as url,status')->orderBy('created_at', 'DESC')->paginate($size, 'default', $offset);
        $total_menu = $this->mstmenu_model->countAllResults();
        $number = ($page <= 0) ? null : $page;
        $totalPages = ($size <= 0) ? null : ceil($total_menu / $size);
        $firstPage = ($number === 1);
        $lastPage = ($number === $totalPages);

        return $this->respond(
            array(
                "status" => true,
                "data" => $menulist,
                "messages" => "Berhasil mengambil data",
                'pagination' => [
                    'page' => $page,
                    'size' => $size,
                    'total_data' => $total_menu,
                    'number' => $number,
                    'firstPage' => $firstPage,
                    'lastPage' => $lastPage,
                ],
            )
        );
    }

    public function delete_menu(){
        $menuId = $this->request->getVar('kode');
        $post = $this->mstmenu_model->find($menuId);

        if($post){
            $this->mstmenu_model->delete($menuId);
            return $this->respond(array("status" => true, "messages" => "Data berhasil dihapus"));
        }else{
            return $this->respond(array("status" => false, "messages" => "Menu tidak ditemukan"));
        }
    }
}
