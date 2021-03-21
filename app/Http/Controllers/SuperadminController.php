<?php

namespace App\Http\Controllers;

use App\Role;
use App\Skpd;
use App\User;
use App\Kelas;
use App\Eselon;
use App\Jabatan;
use App\Pangkat;
use App\Pegawai;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SuperadminController extends Controller
{
    public function skpd()
    {
        return view('superadmin.skpd.index');
    }

    public function addSkpd()
    {
        return view('superadmin.skpd.create');
    }
    
    public function editSkpd($skpd_id)
    {
        $data = Skpd::find($skpd_id);
        return view('superadmin.skpd.edit',compact('id','data'));
    }

    public function updateSkpd(Request $req, $id)
    {
        $validator = Validator::make($req->all(), [
            'kode_skpd' =>  'unique:skpd,kode_skpd,'.$id
        ]);

        if ($validator->fails()) {
            toastr()->error('Kode Skpd Sudah Ada');
            return back();
        }

        Skpd::find($id)->update($req->all());
        toastr()->success('Skpd Berhasil Di Update');
        return redirect('/superadmin/skpd');
    }
    
    public function deleteSkpd($skpd_id)
    {
        try{
            $s = Skpd::find($skpd_id);
            $s->user->delete();
            $s->delete();
            toastr()->success('Skpd Berhasil Di Hapus');
        }catch(\Exception $e){
            toastr()->error('Skpd Tidak Bisa Di Hapus Karena terkait Dengan Data Lain');
        }
        return redirect('/superadmin/skpd');
    }

    public function storeSkpd(Request $req)
    {
        $checkKode = Skpd::where('kode_skpd', $req->kode_skpd)->first();
        if($checkKode == null){
            Skpd::create($req->all());
            toastr()->success('SKPD Berhasil Disimpan');
            return redirect('/superadmin/skpd');
        }else{
            toastr()->error('Kode SKPD Sudah Ada');
            return back();
        }
    }

    public function jabatan($skpd_id)
    {
        $edit = false;
        return view('superadmin.jabatan.index',compact('skpd_id','edit'));
    }

    public function pegawaiSkpd($skpd_id)
    {
        return view('superadmin.skpd.pegawai',compact('skpd_id'));
    }

    public function addPegawaiSkpd($skpd_id)
    {
        $nama_skpd = Skpd::find($skpd_id)->nama;
        return view('superadmin.skpd.create_pegawai',compact('skpd_id','nama_skpd'));
    }

    public function storePegawaiSkpd(Request $req, $skpd_id)
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

        $attr = $req->all();
        $attr['skpd_id'] = $skpd_id;
        $attr['verified'] = 1;
        
        Pegawai::create($attr);
        toastr()->success('Pegawai Berhasil Disimpan');

        return redirect('/superadmin/skpd/pegawai/'.$skpd_id);
    }

    public function updatePegawaiSkpd(Request $req, $skpd_id, $id)
    { 
        $messages = [
            'numeric' => 'Inputan Harus Angka',
            'min'     => 'Harus 18 Digit',
            'unique'  => 'NIP sudah Ada',
        ];

        $rules = [
            'nip' =>  'min:18|numeric|unique:pegawai,nip,'.$id,
            'nama' => 'required'
        ];
        $req->validate($rules, $messages);
        
        $req->flash();

        $attr = $req->all();
        
        Pegawai::find($id)->update($attr);
        toastr()->success('Pegawai Berhasil Diupdate');

        return redirect('/superadmin/skpd/pegawai/'.$skpd_id);
    }
    public function deletePegawaiSkpd($skpd_id, $id)
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
    
    public function editPegawaiSkpd($skpd_id, $id)
    {
        $nama_skpd = Skpd::find($skpd_id)->nama;
        $data = Pegawai::find($id);
        return view('superadmin.skpd.edit_pegawai',compact('skpd_id','nama_skpd','data'));

    }

    public function userSkpd()
    {
        $roleAdminSkpd = Role::where('name','admin')->first();
        $data = Skpd::get();
        foreach($data as $key => $item){
            $checkUsername = User::where('username', $item->kode_skpd)->first();
            if($checkUsername == null){
                $attr['name'] = $item->nama;
                $attr['username'] = $item->kode_skpd;
                $attr['password'] = bcrypt('adminskpd');
                
                //create User di table user
                $u = User::create($attr);

                //Update user_id di table skpd
                $item->user_id = $u->id;
                $item->save();

                //Create Role
                $u->roles()->attach($roleAdminSkpd);

                toastr()->success('Username : Kode SKPD , Password : adminskpd');
            }else{

            }
        }
        return back();
    }

    public function userSkpdId($skpd_id)
    {
        $roleAdminSkpd = Role::where('name','admin')->first();
        $data = Skpd::find($skpd_id);
        $checkUsername = User::where('username', $data->kode_skpd)->first();
        if($checkUsername == null){
            $attr['name'] = $data->nama;
            $attr['username'] = $data->kode_skpd;
            $attr['password'] = bcrypt('adminskpd');
            
            //create User di table user
            $u = User::create($attr);

            //Update user_id di table skpd
            $data->user_id = $u->id;
            $data->save();

            //Create Role
            $u->roles()->attach($roleAdminSkpd);

            toastr()->success('Username : Kode SKPD , Password : adminskpd');
        }else{
            toastr()->error('Tidak bisa dibuat karena kode SKPD sudah Ada');
        }
        return back();

    }

    public function resetPassUserSkpdId($skpd_id)
    {
        Skpd::find($skpd_id)->user->update(['password' => bcrypt('adminskpd')]);
        toastr()->success('Password : adminskpd');
        return back();
    }

    public function deleteUserSkpd()
    {
        $data = Skpd::get()->map(function($item){
            return $item->user;
        });
        foreach($data as $item){
            User::find($item->id)->delete();
            toastr()->success('User '.$item->nama.' Telah Di Hapus');
        }
        return back();
    }

    public function editJabatan($skpd_id, $id)
    {
        $edit = true;
        $jabatan = Jabatan::find($id);
        
        return view('superadmin.jabatan.index',compact('id','edit', 'jabatan','skpd_id'));
    }

    public function updateJabatan(Request $req, $skpd_id, $id)
    {
        if($req->jabatan_id == null){            
            $jabatan = Jabatan::find($id);
            $jabatan->nama = $req->nama;
            $jabatan->kelas_id = $req->kelas_id;
            $jabatan->save();
            toastr()->success('Jabatan Berhasil Di Update');
            return redirect('/superadmin/skpd/jabatan/'.$skpd_id);
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
                
                return redirect('/superadmin/skpd/jabatan/'.$skpd_id);
            } 
        }
    }

    public function storeJabatan(Request $req, $skpd_id)
    {
        $attr['nama']       = $req->nama;
        $attr['jabatan_id'] = $req->jabatan_id;
        $attr['skpd_id']    = $skpd_id;
        $attr['kelas_id']    = $req->kelas_id;

        if($req->jabatan_id == null){
            $attr['tingkat']    = 1;
        }else{
            $attr['tingkat']    = Jabatan::find($req->jabatan_id)->tingkat + 1;
        }

        Jabatan::create($attr);
        toastr()->success('Jabatan Berhasil Disimpan');
        return back();
    }

    public function deleteJabatan($skpd_id, $id)
    {
        try{
            Jabatan::find($id)->delete();
            toastr()->success('Jabatan Berhasil Di Hapus');
        }catch(\Exception $e){
            toastr()->error('Jabatan Tidak Bisa Di Hapus Karena Memiliki Bawahan');
        }
        return redirect('/superadmin/skpd/jabatan/'.$skpd_id);
    }

    public function pegawai()
    {
        $data = Pegawai::orderBy('id','DESC')->paginate(10);
        return view('superadmin.pegawai.index',compact('data'));
    }
    public function searchPegawai()
    {
        $search = request()->get('search');
        $data   = Pegawai::where('nip', 'like', '%'.$search.'%')
        ->orWhere('nama', 'like', '%'.$search.'%')
        ->orderBy('id','DESC')
        ->paginate(10);
        request()->flash();
        return view('superadmin.pegawai.index',compact('data'));

    }
    public function addPegawai()
    {
        return view('superadmin.pegawai.create');
    }
    
    public function editPegawai($id)
    {
        $data = Pegawai::find($id);
        return view('superadmin.pegawai.edit',compact('data'));
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

        $urutan          = Skpd::find($req->skpd_id)->pegawai->sortBy('urutan')->last()->urutan + 1;

        $attr = $req->all();
        $attr['verified'] = 1;
        $attr['urutan'] = $urutan;
        
        Pegawai::create($attr);
        toastr()->success('Pegawai Berhasil Disimpan');

        return redirect('/superadmin/pegawai');
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
        toastr()->success('Pegawai Berhasil Diupdate');

        return redirect('/superadmin/pegawai');
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

    public function userPegawaiId($id)
    {
        $data = Pegawai::find($id);
        $role = Role::where('name','pegawai')->first();
        $checkUsername = User::where('username', $data->nip)->first();
        if($checkUsername == null){
            $u = User::create([
                'name'=> $data->nama,
                'username'=> $data->nip,
                'password' => bcrypt(Carbon::parse($data->tanggal_lahir)->format('dmY')),
            ]);
            $data->user_id = $u->id;
            $data->save();
            $u->roles()->attach($role);
            toastr()->success('Berhasil, Username : NIP anda, Password : tanggal Lahir Anda Contoh : 09121990');

        }else{
            toastr()->error('NIP/Username Sudah Ada');
        }
        return back();
    }

    public function kelas()
    {
        return view('superadmin.kelas.index');
    }

    public function addKelas()
    {
        return view('superadmin.kelas.create');
    }
    
    public function storeKelas(Request $req)
    {
        $messages = [
            'numeric' => 'Inputan Harus Angka',
            'unique' => 'Kelas Sudah Ada',
        ];

        $rules = [
            'nama' =>  'unique:kelas',
            'nilai' => 'numeric'
        ];

        $attr = $req->all();

        $validator = Validator::make($attr, $rules, $messages);
        $req->flash();

        if ($validator->fails()) {
            foreach($messages as $item)
            {
                toastr()->error($item);
            }
            return back();
        }
        Kelas::create($req->all());
        toastr()->success('Nama Kelas Berhasil Disimpan');
        
        return redirect('/superadmin/kelas');
    }
    
    public function editKelas($id)
    {
        $data = Kelas::find($id);
        return view('superadmin.kelas.edit',compact('data'));
    }
    
    public function updateKelas(Request $req, $id)
    {
        $messages = [
            'numeric' => 'Inputan Harus Angka',
            'unique' => 'Kelas Sudah Ada',
        ];

        $rules = [
            'nama' =>  'unique:kelas,nama,'.$id,
            'nilai' => 'numeric'
        ];

        $attr = $req->all();

        $validator = Validator::make($attr, $rules, $messages);

        if ($validator->fails()) {
            foreach($messages as $item)
            {
                toastr()->error($item);
            }
            return back();
        }

        Kelas::find($id)->update($req->all());
        toastr()->success('Kelas Berhasil Di Update');
        return redirect('/superadmin/kelas');
    }
    
    public function deleteKelas($id)
    {
        try{
            Kelas::find($id)->delete();
            toastr()->success('Kelas Berhasil Di Hapus');
        }catch(\Exception $e){
            toastr()->error('Kelas Tidak Bisa Di Hapus Karena Memiliki Data yang terkait');
        }
        return back();
    }
    
    public function pangkat()
    {
        return view('superadmin.pangkat.index');
    }

    public function addPangkat()
    {
        return view('superadmin.pangkat.create');
    }

    public function editPangkat($id)
    {
        $data = Pangkat::find($id);
        return view('superadmin.pangkat.edit',compact('data'));
    }

    public function storePangkat(Request $req)
    {
        $messages = [
            'unique'  => 'Nama sudah Ada',
        ];

        $rules = [
            'nama' =>  'required|unique:pangkat,nama',
            'golongan' => 'required|unique:pangkat,golongan',
        ];
        $req->validate($rules, $messages);
        
        $req->flash();

        Pangkat::create($req->all());
        toastr()->success('Pangkat Berhasil Di Simpan');
        return redirect('/superadmin/pangkat');
    }

    public function updatePangkat(Request $req, $id)
    {
        $messages = [
            'unique'  => 'Nama sudah Ada',
        ];

        $rules = [
            'nama' =>  'required|unique:pangkat,nama,'.$id,
            'golongan' => 'required|unique:pangkat,golongan,'.$id,
        ];
        $req->validate($rules, $messages);
        
        $req->flash();

        Pangkat::find($id)->update($req->all());
        toastr()->success('Pangkat Berhasil Di Update');
        return redirect('/superadmin/pangkat');
    }

    public function deletePangkat($id)
    {
        try{
            Pangkat::find($id)->delete();
            toastr()->success('Pangkat Berhasil Di Hapus');
        }catch(\Exception $e){
            toastr()->error('Pangkat Tidak Bisa Di Hapus Karena Memiliki Data yang terkait');
        }
        return back();
    }

    public function eselon()
    {
        return view('superadmin.eselon.index');
    }

    
    public function addEselon()
    {
        return view('superadmin.eselon.create');
    }

    public function editEselon($id)
    {
        $data = Eselon::find($id);
        return view('superadmin.eselon.edit',compact('data'));
    }

    public function storeEselon(Request $req)
    {
        $messages = [
            'unique'  => 'Nama sudah Ada',
        ];

        $rules = [
            'nama' =>  'required|unique:eselon,nama',
        ];
        $req->validate($rules, $messages);
        
        $req->flash();

        Eselon::create($req->all());
        toastr()->success('Eselon Berhasil Di Simpan');
        return redirect('/superadmin/eselon');
    }

    public function updateEselon(Request $req, $id)
    {
        $messages = [
            'unique'  => 'Nama sudah Ada',
        ];

        $rules = [
            'nama' =>  'required|unique:eselon,nama,'.$id,
        ];
        $req->validate($rules, $messages);
        
        $req->flash();

        Eselon::find($id)->update($req->all());
        toastr()->success('Eselon Berhasil Di Update');
        return redirect('/superadmin/eselon');
    }

    public function deleteEselon($id)
    {
        try{
            if(Eselon::find($id) == null){
                toastr()->error('Tidak ada Data untuk Di Hapus');
            }else{
                Eselon::find($id)->delete();
                toastr()->success('Eselon Berhasil Di Hapus');
            }
        }catch(\Exception $e){
            toastr()->error('Eselon Tidak Bisa Di Hapus Karena Memiliki Data yang terkait');
        }
        return back();
    }

    public function mutasi()
    {
        return view('superadmin.mutasi.index');
    }
}
