<?php

namespace App\Controllers;

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use App\Controllers\BaseController;
use App\Models\User;

class Auth extends BaseController
{
    public function loginQR()
    {
        $model = new User();
        $qrcode = $this->request->getVar('qrcode');
        $data = explode('|', $qrcode); // |user|user| |username|password|
        $username = $data[1];
        $password = $data[2];
        $dataUser = $model->where(['username' => $username,])->first();
        if ($dataUser) {
            if (password_verify($password, $dataUser['password'])) {
                session()->set([
                    'username'  => $dataUser['username'],
                    'password'  => $dataUser['password'],
                    'id'        => $dataUser['id'],
                    'name'      => $dataUser['name'],
                    'address'   => $dataUser['address'],
                    'role'      => $dataUser['role'],
                    'tl'        => $dataUser['tl'],
                    'status_account' => $dataUser['status_account'],
                    'nomor_identitas' => $dataUser['nomor_identitas'],
                    'WesLogin'  => TRUE,
                ]);
                return redirect()->to(base_url('pasien'));
            } else {
                session()->setFlashdata('error', 'QR Code anda salah, silahkan cetak ulang/hubungi admin!');
                return redirect()->to('login');
            }
        } else {
            session()->setFlashdata('error', 'QR Code anda salah, silahkan cetak ulang/hubungi admin!');
            return redirect()->to('login');
        }
    }

    public function login()
    {
        $model = new User();
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');
        $dataUser = $model->where(['username' => $username,])->first();
        if ($dataUser) {
            if (password_verify($password, $dataUser['password'])) {
                session()->set([
                    'username'  => $dataUser['username'],
                    'password'  => $dataUser['password'],
                    'id'        => $dataUser['id'],
                    'name'      => $dataUser['name'],
                    'address'   => $dataUser['address'],
                    'role'      => $dataUser['role'],
                    'nomor_identitas' => $dataUser['nomor_identitas'],
                    'tl'        => $dataUser['tl'],
                    'status_account' => $dataUser['status_account'],
                    'WesLogin'  => TRUE, 
                ]);
                if($dataUser['role'] === 'pasien') {
                    return redirect()->to(base_url('pasien'));
                } elseif($dataUser['role'] === 'admin') {
                    return redirect()->to(base_url('dashboard'));
                }
            } else {
                session()->setFlashdata('error', 'Password Salah!');
                return redirect()->to('masuk');
            }
        } else {
            session()->setFlashdata('error', 'Username tidak ada di database!');
            return redirect()->to('masuk');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }

    function register()
    {
        if (!$this->validate([
            'username' => [
                'rules' => 'required|min_length[4]|max_length[15]|is_unique[user.username]',
                'errors' => [
                    'required' => 'username harus diisi',
                    'min_length' => 'username minimal 4 Karakter',
                    'max_length' => 'username maksimal 15 Karakter',
                    'is_unique' => 'username sudah digunakan'
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[4]|max_length[50]',
                'errors' => [
                    'required' => 'Password harus diisi',
                    'min_length' => 'Password minimal 4 Karakter',
                    'max_length' => 'Password maksimal 50 Karakter',
                ]
            ],
            'password_conf' => [
                'rules' => 'matches[password]',
                'errors' => [
                    'matches' => 'Konfirmasi password tidak sesuai dengan password di atas',
                ]
            ],
            'name' => [
                'rules' => 'required|min_length[4]|max_length[50]',
                'errors' => [
                    'required' => '{field} harus diisi',
                    'min_length' => '{field} minimal 4 Karakter',
                    'max_length' => '{field} maksimal 50 Karakter',
                ]
            ],
            'nomor_identitas' => [
                'rules' => 'required|min_length[16]|max_length[16]|is_unique[user.nomor_identitas]',
                'errors' => [
                    'is_unique' => 'Nomor Identitas sudah digunakan',
                    'required' => 'Nomor Identitas harus diisi',
                    'min_length' => 'Nomor Identitas minimal 16 Karakter',
                    'max_length' => 'Nomor Identitas maksimal 16 Karakter',
                ]
            ],
            'address' => [
                'rules' => 'required|min_length[4]|max_length[50]',
                'errors' => [
                    'required' => 'Alamat harus diisi',
                    'min_length' => 'Alamat minimal 4 Karakter',
                    'max_length' => 'Alamat maksimal 50 Karakter',
                ]
            ],
            'gender' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Jenis Kelamin harus diisi',
                ]
            ],
            'foto_ktp' => [
                'rules' => 'mime_in[foto_ktp,image/jpg,image/jpeg,image/png,image/webp]',
                'errors' => [
                    'mime_in'  => 'Maaf file yang anda upload memiliki format yang tidak diizinkan! silahkan upload dengan format JPG, JPEG, dan PNG.',
                ]
            ],
        ])) {
            session()->setFlashdata('error', $this->validator->listErrors());
            return redirect()->back()->withInput();
        }
        $model = new User();
        $img    = $this->request->getFile('foto_ktp');
        $randName = $img->getRandomName();
        if ($img->isValid() && ! $img->hasMoved()) {
            $img->move('foto_ktp',$randName);
            $data = [
                'username'  => $this->request->getVar('username'),
                'password'  => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
                'name'      => $this->request->getVar('name'),
                'nomor_identitas' => $this->request->getVar('nomor_identitas'),
                'address'   => $this->request->getVar('address'),
                'gender'    => $this->request->getVar('gender'),
                'status_account' => 'unverified',
                'role'      => 'pasien',
                'foto_ktp' => $randName,
            ];
            if ($model->save($data)) {
                session()->setFlashdata('success', 'Anda telah berhasil daftar silahkan login.');
                return redirect()->to('login');
            } else {
                session()->setFlashdata('error', 'Terjadi Error!');
                return redirect()->to('register');
            }
        } else {
            $data = [
                'username'  => $this->request->getVar('username'),
                'password'  => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
                'name'      => $this->request->getVar('name'),
                'nomor_identitas' => $this->request->getVar('nomor_identitas'),
                'address'   => $this->request->getVar('address'),
                'gender'    => $this->request->getVar('gender'),
                'status_account' => 'unverified',
                'role'      => 'pasien',
            ];
            if ($model->save($data)) {
                session()->setFlashdata('success', 'Anda telah berhasil daftar silahkan login.');
                return redirect()->to('login');
            } else {
                session()->setFlashdata('error', 'Terjadi Error!');
                return redirect()->to('register');
            }
        }
    }
}
