<?php

namespace App\Http\Controllers;

use App\Role;
use App\Skpd;
use App\User;
use App\Jabatan;
use App\Pegawai;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function skpd_id()
    {
        return Auth::user()->skpd->id;
    }

    public function pegawai()
    { 
        $data = Pegawai::where('skpd_id', Auth::user()->skpd->id)->orderBy('urutan','ASC')->paginate(10);
        return view('admin.pegawai.index',compact('data'));
    }

    public function addPegawai()
    {
        return view('admin.pegawai.create');
    }

    public function storePegawai(Request $req)
    {
        $messages = [
            'numeric' => 'Inputan Harus Angka',
            'min'     => 'Harus 18 Digit',
            'unique'  => 'NIP sudah Ada',
        ];

        $rules = [
            'nip' =>  'unique:pegawai|min:18|numeric',
            'nama' => 'required'
        ];
        $req->validate($rules, $messages);
        
        $req->flash();
        
        $urutan          = Skpd::find($this->skpd_id())->pegawai->sortBy('urutan')->last()->urutan + 1;
        $attr            = $req->all();
        $attr['urutan']  = $urutan;
        $attr['skpd_id'] = $this->skpd_id();
        $attr['verified'] = 1;

        Pegawai::create($attr);

        toastr()->success('Pegawai Berhasil Di Simpan');
        return redirect('/admin/pegawai');
        
    }

    public function editPegawai($id)
    {
        $data = Pegawai::find($id);
        return view('admin.pegawai.edit',compact('data'));
    }
    
    public function updatePegawai(Request $req, $id)
    {        
        $messages = [
            'numeric' => 'Inputan Harus Angka',
            'min'     => 'Harus 18 Digit',
            'unique'  => 'NIP sudah Ada',
        ];

        $rules = [
            'nip' =>  'min:18|unique:pegawai,nip,'.$id,
            'nama' => 'required'
        ];
        $req->validate($rules, $messages);
        
        $req->flash();

        $attr = $req->all();

        Pegawai::find($id)->update($attr);

        toastr()->success('Pegawai Berhasil Di Update');
        return redirect('/admin/pegawai');
    }

    public function deletePegawai($id)
    {
        try{
            $s = Pegawai::find($id);
            if($s->user != null)
            {
                $s->user->delete();
            }
            $s->delete();
            toastr()->success('Pegawai Berhasil Di Hapus');
        }catch(\Exception $e){
            toastr()->error('Pegawai Tidak Bisa Di Hapus Karena terkait Dengan Data Lain');
        }
        return back();
    }

    public function createUser($id)
    {
        $rolePegawai = Role::where('name','pegawai')->first();
        $data = Pegawai::find($id);
        $checkUsername = User::where('username', $data->nip)->first();
        if($checkUsername == null){
            $attr['name'] = $data->nama;
            $attr['username'] = $data->nip;
            $attr['password'] = bcrypt(Carbon::parse($data->tanggal_lahir)->format('dmY'));
            
            //create User di table user
            $u = User::create($attr);

            //Update user_id di table skpd
            $data->user_id = $u->id;
            $data->save();

            //Create Role
            $u->roles()->attach($rolePegawai);

            toastr()->success('Username : '.$data->nip .'<br> Password : '.Carbon::parse($data->tanggal_lahir)->format('dmY'));
        }else{
            toastr()->error('Tidak bisa dibuat karena NIP sudah Ada');
        }
        return back();
    }

    public function resetPass($id)
    {
        $data = Pegawai::find($id)->user;
        $tgl_lahir = Pegawai::find($id)->tanggal_lahir;
        
        $u = $data;
        $u->password = bcrypt(Carbon::parse($tgl_lahir)->format('dmY'));
        $u->save();

        toastr()->success('Password : '.Carbon::parse($tgl_lahir)->format('dmY'));
        return back();
        
    }

    public function jabatan()
    {
        $skpd_id = Auth::user()->skpd->id;
        $edit = false;
        return view('admin.jabatan.index',compact('data','skpd_id','edit'));
    }

    public function editJabatan($id)
    {
        $skpd_id = Auth::user()->skpd->id;
        $edit = true;
        $jabatan = Jabatan::find($id);
        return view('admin.jabatan.index',compact('id','edit', 'jabatan','skpd_id'));
    }

    public function storeJabatan(Request $req)
    {
        $skpd_id = Auth::user()->skpd->id;

        $attr  = $req->all();
        $attr['skpd_id']    = $skpd_id;

        if($req->jabatan_id == null){
            $attr['tingkat']    = 1;
        }else{
            $attr['tingkat']    = Jabatan::find($req->jabatan_id)->tingkat + 1;
        }
        Jabatan::create($attr);
        toastr()->success('Jabatan Berhasil Di Simpan');

        return redirect('/admin/jabatan');
    }
    
    public function updateJabatan(Request $req, $id)
    {
        if($req->jabatan_id == null){            
            $jabatan = Jabatan::find($id);
            $jabatan->nama = $req->nama;
            $jabatan->kelas_id = $req->kelas_id;
            $jabatan->save();
            toastr()->success('Jabatan Berhasil Di Update');
            return redirect('/admin/jabatan');
        }else{
            $tingkat1 = Jabatan::find($req->jabatan_id)->tingkat;
            $tingkat2 = Jabatan::find($id)->tingkat;
            
            if($tingkat1 == $tingkat2){
                toastr()->error('Jabatan Tidak bisa di pindah karena setara');
                return back();
            }elseif($tingkat1 > $tingkat2){
                toastr()->error('Jabatan Tidak bisa di pindah Ke tingkat yang lebih rendah');
                return back();
            }elseif(abs($tingkat1-$tingkat2) >= 2){
                toastr()->error('Jabatan Tidak bisa di pindah Ke tingkat yang lebih Tinggi');
                return back();
            }else{
                
                $jabatan = Jabatan::find($id);
                $jabatan->jabatan_id = $req->jabatan_id;
                $jabatan->nama = $req->nama;
                $jabatan->kelas_id = $req->kelas_id;
                $jabatan->save();
                toastr()->success('Jabatan Berhasil Di Update');
                
                return redirect('/admin/jabatan');
            } 
        }
    }

    public function deleteJabatan($id)
    {
        try{
            Jabatan::find($id)->delete();
            toastr()->success('Jabatan Berhasil Di Hapus');
        }catch(\Exception $e){
            toastr()->error('Jabatan Tidak Bisa Di Hapus Karena Memiliki Bawahan');
        }
        return redirect('/admin/jabatan');
    }

}
