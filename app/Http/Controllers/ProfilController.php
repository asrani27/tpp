<?php

namespace App\Http\Controllers;

use App\Eselon;
use App\Pangkat;
use App\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfilController extends Controller
{

    public function user()
    {
        return Auth::user();
    }
    public function superadmin()
    {
        return view('superadmin.profil');
    }

    public function changeSuperadmin(Request $req)
    {
        if ($req->password != $req->password2) {
            toastr()->error('Password Tidak Sama');
        } else {
            $p = Auth::user();
            $p->password = bcrypt($req->password);
            $p->save();
            toastr()->success('Password Berhasil Di Ubah');
        }
        return back();
    }

    public function changeAdmin(Request $req)
    {
        if ($req->password != $req->password2) {
            toastr()->error('Password Tidak Sama');
        } else {
            $p = Auth::user();
            $p->password = bcrypt($req->password);
            $p->save();
            toastr()->success('Password Berhasil Di Ubah');
        }
        return back();
    }

    public function admin()
    {
        return view('admin.profil');
    }

    public function pegawai()
    {
        $data = Auth::user()->pegawai;
        return view('pegawai.profil', compact('data'));
    }

    public function editPegawai()
    {
        $data = Auth::user()->pegawai;
        $pangkat = Pangkat::get();
        $eselon  = Eselon::get();
        return view('pegawai.edit_profil', compact('data', 'pangkat', 'eselon'));
    }

    public function updatePegawai(Request $req)
    {
        DB::beginTransaction();
        try {
            $p = $this->user()->pegawai;

            $p->nama       = $req->nama;
            $p->pangkat_id = $req->pangkat_id;
            $p->eselon_id  = $req->eselon_id;
            $p->no_rek     = $req->no_rek;
            $p->npwp       = $req->npwp;
            $p->alamat     = $req->alamat;
            $p->jkel       = $req->jkel;
            $p->jurusan    = $req->jurusan;
            $p->tanggal_lahir      = $req->tanggal_lahir;
            $p->jenjang_pendidikan = $req->jenjang_pendidikan;
            $p->telp         = $req->telp;
            $p->gol_darah    = $req->gol_darah;
            $p->save();
            $u = $this->user();
            $u->email = $req->email;
            $u->save();

            DB::commit();
            toastr()->success('Data Berhasil di Update');
        } catch (\Exception $e) {
            DB::rollback();
            return $e;
            toastr()->error('Gagal Update Data');
        }
        return redirect('/pegawai/profil');
    }

    public function updateDataPegawai(Request $req)
    {
        $d = Auth::user()->pegawai;
        $d->no_rek      = $req->no_rek;
        $d->telp        = $req->telp;
        $d->gol_darah   = $req->gol_darah;
        $d->npwp        = $req->npwp;
        $d->jenjang_pendidikan = $req->jenjang_pendidikan;
        $d->jkel         = $req->jkel;
        $d->save();
        toastr()->success('Data Berhasil di Update');
        return back();
    }
    public function walikota()
    {
        return view('walikota.profil');
    }

    public function gantiPassPegawai(Request $req)
    {
        if ($req->password1 != $req->password2) {
            toastr()->info('Password tidak sama');
            return back();
        } else {
            Auth::user()->update([
                'password' => bcrypt($req->password1)
            ]);
            toastr()->success('Password Berhasil DiUbah');
            return back();
        }
    }

    public function gantiPassPegawaiView()
    {
        return view('gantipass');
    }

    public function updatePassPegawai(Request $req)
    {
        if (!Hash::check($req->old_password, Auth::user()->password)) {
            toastr()->error('Password Lama Tidak Sama');
            return back();
        }
        if ($req->password != $req->password_confirmation) {
            toastr()->error('Password Baru Tidak Sesuai');
            return back();
        } else {

            $validator = Validator::make($req->all(), [
                'password' => 'required|min:8|regex:/[0-9]/',
            ]);

            if ($validator->fails()) {
                toastr()->error('Password min 8 karakter serta kombinasi angka dan huruf');
                return back();
            }

            Auth::user()->update([
                'password' => bcrypt($req->password),
                'change_password' => 1,
            ]);

            Auth::logout();
            toastr()->success('Berhasil Di Update, Login Dengan Password Baru');
            return redirect('/');
        }
    }
}
