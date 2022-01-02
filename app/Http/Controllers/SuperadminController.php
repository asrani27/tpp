<?php

namespace App\Http\Controllers;

use App\Role;
use App\Skpd;
use App\User;
use App\Kelas;
use App\Eselon;
use App\Sanksi;
use App\Jabatan;
use App\Pangkat;
use App\Pegawai;
use App\Aktivitas;
use App\Parameter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Imports\PegawaiImport;
use Illuminate\Cache\NullStore;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

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
        return view('superadmin.skpd.edit', compact('id', 'data'));
    }

    public function updateSkpd(Request $req, $id)
    {
        $validator = Validator::make($req->all(), [
            'kode_skpd' =>  'unique:skpd,kode_skpd,' . $id
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
        try {
            $s = Skpd::find($skpd_id);
            if ($s->user != null) {
                $s->user->delete();
            }
            Skpd::find($skpd_id)->delete();
            toastr()->success('Skpd Berhasil Di Hapus');
        } catch (\Exception $e) {
            toastr()->error('Skpd Tidak Bisa Di Hapus Karena terkait Dengan Data Lain');
        }
        return redirect('/superadmin/skpd');
    }

    public function storeSkpd(Request $req)
    {
        $checkKode = Skpd::where('kode_skpd', $req->kode_skpd)->first();
        if ($checkKode == null) {
            Skpd::create($req->all());
            toastr()->success('SKPD Berhasil Disimpan');
            return redirect('/superadmin/skpd');
        } else {
            toastr()->error('Kode SKPD Sudah Ada');
            return back();
        }
    }

    public function jabatan($skpd_id)
    {
        $edit = false;
        return view('superadmin.jabatan.index', compact('skpd_id', 'edit'));
    }

    public function pegawaiSkpd($skpd_id)
    {
        $data = Pegawai::with('jabatan')->where('skpd_id', $skpd_id)->paginate(10);
        return view('superadmin.skpd.pegawai', compact('skpd_id', 'data'));
    }

    public function addPegawaiSkpd($skpd_id)
    {
        $nama_skpd = Skpd::find($skpd_id)->nama;
        $jabatan   = Jabatan::with('pegawai')->where('skpd_id', $skpd_id)->get()->where('pegawai', null);
        return view('superadmin.skpd.create_pegawai', compact('skpd_id', 'nama_skpd', 'jabatan'));
    }

    public function addImport($skpd_id)
    {
        return view('superadmin.skpd.import', compact('skpd_id'));
    }

    public function importPegawai(Request $req, $skpd_id)
    {
        $data = Excel::toCollection(new PegawaiImport, $req->file('file'))->first();
        foreach ($data as $key => $item) {
            $check = Pegawai::where('nip', $item[2])->first();
            if ($check == null) {
                $attr['nama'] = $item[1];
                $attr['nip'] = $item[2];
                $attr['tanggal_lahir'] = Carbon::createFromFormat('dmY', $item[3])->format('Y-m-d');
                $attr['urutan'] = $item[0];
                $attr['skpd_id'] = $skpd_id;
                Pegawai::create($attr);
            }
        }

        toastr()->success('Data Pegawai Berhasil Di Import');
        return back();
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

        return redirect('/superadmin/skpd/pegawai/' . $skpd_id);
    }

    public function updatePegawaiSkpd(Request $req, $skpd_id, $id)
    {
        $messages = [
            'numeric' => 'Inputan Harus Angka',
            'min'     => 'Harus 18 Digit',
            'unique'  => 'NIP sudah Ada',
        ];

        $rules = [
            'nip' =>  'min:18|numeric|unique:pegawai,nip,' . $id,
            'nama' => 'required'
        ];
        $req->validate($rules, $messages);

        $req->flash();

        $attr = $req->all();

        $pegawai = Pegawai::find($id);
        if ($pegawai->user == null) {
            toastr()->error('Harap Di create user terlebih dahulu');
            return back();
        } else {
            DB::beginTransaction();
            try {
                $pegawai->user->update([
                    'username' => $req->nip,
                ]);
                $pegawai->update($attr);
                DB::commit();
                toastr()->success('Pegawai Berhasil di Update');
                return back();
            } catch (\Exception $e) {
                DB::rollback();
                $req->flash();
                toastr()->error('Pegawai Gagal Diupdate');
                return back();
            }
        }

        //return redirect('/superadmin/skpd/pegawai/'.$skpd_id);
    }
    public function deletePegawaiSkpd($skpd_id, $id)
    {
        try {
            $s = Pegawai::find($id);
            if ($s->user != null) {
                $s->user->delete();
            }
            $s->delete();
            toastr()->success('Pegawai Berhasil Di Hapus');
        } catch (\Exception $e) {
            toastr()->error('Pegawai Tidak Bisa Di Hapus Karena terkait Dengan Data Lain');
        }
        return back();
    }

    public function editPegawaiSkpd($skpd_id, $id)
    {
        $nama_skpd = Skpd::find($skpd_id)->nama;
        $data      = Pegawai::find($id);
        $jabatan   = Jabatan::with('pegawai')->where('skpd_id', $skpd_id)->get()->where('pegawai', null);

        return view('superadmin.skpd.edit_pegawai', compact('skpd_id', 'nama_skpd', 'data', 'jabatan'));
    }

    public function userSkpd()
    {
        $roleAdminSkpd = Role::where('name', 'admin')->first();
        $data = Skpd::get();
        foreach ($data as $key => $item) {
            $checkUsername = User::where('username', $item->kode_skpd)->first();
            if ($checkUsername == null) {
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
            } else {
            }
        }
        return back();
    }

    public function userSkpdId($skpd_id)
    {
        $roleAdminSkpd = Role::where('name', 'admin')->first();
        $data = Skpd::find($skpd_id);
        $checkUsername = User::where('username', $data->kode_skpd)->first();
        if ($checkUsername == null) {
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
        } else {
            toastr()->error('Tidak bisa dibuat karena kode SKPD sudah Ada');
        }
        return back();
    }

    public function userPegawaiSkpdId($skpd_id)
    {
        $data = Pegawai::with('user')->where('skpd_id', $skpd_id)->get();
        $rolePegawai = Role::where('name', 'pegawai')->first();

        foreach ($data as $key => $item) {
            if ($item->user == null) {
                $attr['name'] = $item->nama;
                $attr['username'] = $item->nip;
                $attr['password'] = bcrypt(Carbon::parse($item->tanggal_lahir)->format('dmY'));
                $u = User::create($attr);

                //Update user_id di table skpd
                $item->user_id = $u->id;
                $item->save();

                //Create Role
                $u->roles()->attach($rolePegawai);
            }
        }
        toastr()->success('Username : NIP, Password : tanggal Lahir Contoh(01012000)');
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
        $data = Skpd::get()->map(function ($item) {
            return $item->user;
        });
        foreach ($data as $item) {
            User::find($item->id)->delete();
            toastr()->success('User ' . $item->nama . ' Telah Di Hapus');
        }
        return back();
    }

    public function editJabatan($skpd_id, $id)
    {
        $edit = true;
        $jabatan = Jabatan::find($id);

        return view('superadmin.jabatan.index', compact('id', 'edit', 'jabatan', 'skpd_id'));
    }

    public function updateJabatan(Request $req, $skpd_id, $id)
    {
        if ($req->jabatan_id == null) {
            $jabatan = Jabatan::find($id);
            $jabatan->nama = $req->nama;
            $jabatan->kelas_id = $req->kelas_id;
            $jabatan->save();
            toastr()->success('Jabatan Berhasil Di Update');
            return redirect('/superadmin/skpd/jabatan/' . $skpd_id);
        } else {
            $tingkat1 = Jabatan::find($req->jabatan_id)->tingkat;
            $tingkat2 = Jabatan::find($id)->tingkat;

            if ($tingkat1 == $tingkat2) {
                toastr()->error('Jabatan Tidak bisa di pindah karena setara');
                return back();
            } elseif ($tingkat1 > $tingkat2) {
                toastr()->error('Jabatan Tidak bisa di pindah Ke tingkat yang lebih rendah');
                return back();
            } elseif (abs($tingkat1 - $tingkat2) >= 2) {
                toastr()->error('Jabatan Tidak bisa di pindah Ke tingkat yang lebih Tinggi');
                return back();
            } else {

                $jabatan = Jabatan::find($id);
                $jabatan->jabatan_id = $req->jabatan_id;
                $jabatan->nama = $req->nama;
                $jabatan->kelas_id = $req->kelas_id;
                $jabatan->save();
                toastr()->success('Jabatan Berhasil Di Update');

                return redirect('/superadmin/skpd/jabatan/' . $skpd_id);
            }
        }
    }

    public function storeJabatan(Request $req, $skpd_id)
    {
        $attr['nama']       = $req->nama;
        $attr['jabatan_id'] = $req->jabatan_id;
        $attr['skpd_id']    = $skpd_id;
        $attr['kelas_id']    = $req->kelas_id;

        if ($req->jabatan_id == null) {
            $attr['tingkat']    = 1;
        } else {
            $attr['tingkat']    = Jabatan::find($req->jabatan_id)->tingkat + 1;
        }

        Jabatan::create($attr);
        toastr()->success('Jabatan Berhasil Disimpan');
        return back();
    }

    public function deleteJabatan($skpd_id, $id)
    {
        try {
            Jabatan::find($id)->delete();
            toastr()->success('Jabatan Berhasil Di Hapus');
        } catch (\Exception $e) {
            toastr()->error('Jabatan Tidak Bisa Di Hapus Karena Memiliki Bawahan');
        }
        return redirect('/superadmin/skpd/jabatan/' . $skpd_id);
    }

    public function pegawai()
    {
        $data = Pegawai::orderBy('id', 'DESC')->paginate(10);
        return view('superadmin.pegawai.index', compact('data'));
    }
    public function searchPegawai()
    {
        $search = request()->get('search');
        $data   = Pegawai::where('nip', 'like', '%' . $search . '%')
            ->orWhere('nama', 'like', '%' . $search . '%')
            ->orderBy('id', 'DESC')
            ->paginate(10);
        request()->flash();
        return view('superadmin.pegawai.index', compact('data'));
    }
    public function addPegawai()
    {
        return view('superadmin.pegawai.create');
    }

    public function editPegawai($id)
    {
        $data = Pegawai::find($id);

        return view('superadmin.pegawai.edit', compact('data'));
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

        $urutan          = Skpd::find($req->skpd_id)->pegawai->sortBy('urutan')->last() == null ? 1 : Skpd::find($req->skpd_id)->pegawai->sortBy('urutan')->last()->urutan + 1;

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
            'nip' =>  'min:18|unique:pegawai,nip,' . $id,
            'nama' => 'required'
        ];
        $req->validate($rules, $messages);

        $req->flash();

        $attr = $req->all();
        $pegawai = Pegawai::find($id);
        if ($pegawai->user == null) {
            toastr()->error('Harap Di create user terlebih dahulu');
            return back();
        } else {
            DB::beginTransaction();
            try {
                $pegawai->user->update([
                    'username' => $req->nip,
                ]);
                $pegawai->update($attr);
                DB::commit();
                toastr()->success('Pegawai Berhasil di Update');
                return back();
            } catch (\Exception $e) {
                DB::rollback();
                $req->flash();
                toastr()->error('Pegawai Gagal Diupdate');
                return back();
            }
        }

        //return redirect('/superadmin/pegawai');
    }

    public function deletePegawai($id)
    {
        try {
            $s = Pegawai::find($id);
            if ($s->user != null) {
                $s->user->delete();
            }
            Pegawai::find($id)->delete();
            toastr()->success('Pegawai Berhasil Di Hapus');
        } catch (\Exception $e) {
            return $e;
            toastr()->error('Pegawai Tidak Bisa Di Hapus Karena terkait Dengan Data Lain');
        }
        return back();
    }

    public function userPegawaiId($id)
    {
        $data = Pegawai::find($id);
        $role = Role::where('name', 'pegawai')->first();
        $checkUsername = User::where('username', $data->nip)->first();
        if ($checkUsername == null) {
            $u = User::create([
                'name' => $data->nama,
                'username' => $data->nip,
                'password' => bcrypt(Carbon::parse($data->tanggal_lahir)->format('dmY')),
            ]);
            $data->user_id = $u->id;
            $data->save();
            $u->roles()->attach($role);
            toastr()->success('Berhasil, Username : NIP anda, Password : tanggal Lahir Anda Contoh : 09121990');
        } else {
            toastr()->error('NIP/Username Sudah Ada');
        }
        return back();
    }

    public function resetPassPegawaiId($id)
    {
        $data = Pegawai::find($id);
        $data->user->update([
            'password' => bcrypt(Carbon::parse($data->tanggal_lahir)->format('dmY'))
        ]);
        toastr()->success('Password ' . Carbon::parse($data->tanggal_lahir)->format('dmY'));
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
            foreach ($messages as $item) {
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
        return view('superadmin.kelas.edit', compact('data'));
    }

    public function updateKelas(Request $req, $id)
    {
        $messages = [
            'numeric' => 'Inputan Harus Angka',
            'unique' => 'Kelas Sudah Ada',
        ];

        $rules = [
            'nama' =>  'unique:kelas,nama,' . $id,
            'nilai' => 'numeric'
        ];

        $attr = $req->all();

        $validator = Validator::make($attr, $rules, $messages);

        if ($validator->fails()) {
            foreach ($messages as $item) {
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
        try {
            Kelas::find($id)->delete();
            toastr()->success('Kelas Berhasil Di Hapus');
        } catch (\Exception $e) {
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
        return view('superadmin.pangkat.edit', compact('data'));
    }

    public function storePangkat(Request $req)
    {
        $messages = [
            'unique'  => 'Nama sudah Ada',
        ];

        $rules = [
            'nama' =>  'required|unique:pangkat,nama',
            'golongan' => 'required|unique:pangkat,golongan',
            'pph' => 'required',
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
            'nama' =>  'required|unique:pangkat,nama,' . $id,
            'golongan' => 'required|unique:pangkat,golongan,' . $id,
            'pph' => 'required',
        ];
        $req->validate($rules, $messages);

        $req->flash();

        Pangkat::find($id)->update($req->all());
        toastr()->success('Pangkat Berhasil Di Update');
        return redirect('/superadmin/pangkat');
    }

    public function deletePangkat($id)
    {
        try {
            Pangkat::find($id)->delete();
            toastr()->success('Pangkat Berhasil Di Hapus');
        } catch (\Exception $e) {
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
        return view('superadmin.eselon.edit', compact('data'));
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
            'nama' =>  'required|unique:eselon,nama,' . $id,
        ];
        $req->validate($rules, $messages);

        $req->flash();

        Eselon::find($id)->update($req->all());
        toastr()->success('Eselon Berhasil Di Update');
        return redirect('/superadmin/eselon');
    }

    public function deleteEselon($id)
    {
        try {
            if (Eselon::find($id) == null) {
                toastr()->error('Tidak ada Data untuk Di Hapus');
            } else {
                Eselon::find($id)->delete();
                toastr()->success('Eselon Berhasil Di Hapus');
            }
        } catch (\Exception $e) {
            toastr()->error('Eselon Tidak Bisa Di Hapus Karena Memiliki Data yang terkait');
        }
        return back();
    }

    public function mutasi()
    {
        return view('superadmin.mutasi.index');
    }

    public function parameter()
    {
        $toplevel = Jabatan::where('sekda', 1)->first();
        $parameter = Parameter::get();
        return view('superadmin.parameter.index', compact('toplevel', 'parameter'));
    }

    public function editParameter($id)
    {
        $data = Parameter::find($id);
        return view('superadmin.parameter.edit', compact('data'));
    }

    public function updateParameter(Request $req, $id)
    {
        Parameter::find($id)->update([
            'value' => $req->value,
        ]);
        toastr()->success('Parameter Berhasil Di Update');
        return redirect('/superadmin/parameter');
    }

    public function topLevel()
    {
        $data = Jabatan::paginate(10);
        return view('superadmin.parameter.jabatan', compact('data'));
    }

    public function sekda($id)
    {
        $new = Jabatan::find($id);
        $j = jabatan::where('sekda', '!=', null)->first();
        if ($j == null) {
            $new->update([
                'sekda' => 1
            ]);
        } else {
            $j->update([
                'sekda' => null
            ]);

            $new->update([
                'sekda' => 1
            ]);
        }
        toastr()->success('Jabatan Top Level Berhasil Di Update');
        return redirect('/superadmin/parameter');
    }

    public function searchSekda()
    {
        $search = request()->get('search');
        $data   = Jabatan::where('nama', 'LIKE', '%' . $search . '%')->paginate(10);
        return view('superadmin.parameter.jabatan', compact('data'));
    }

    public function rekapASN()
    {
        $total = Pegawai::get()->count();
        $l = Pegawai::where('jkel', 'L')->get()->count();
        $p = Pegawai::where('jkel', 'P')->get()->count();

        //berdasarkan eselon
        $eselon = Eselon::get()->map(function ($item) {
            $item->total = $item->pegawai == null ? 0 : $item->pegawai->count();
            return $item;
        });

        //berdasarkan Pangkat
        $pangkat = Pangkat::get()->map(function ($item) {
            $item->total = $item->pegawai == null ? 0 : $item->pegawai->count();
            return $item;
        });

        //berdasarkan Golongan Darah
        $darah_a = Pegawai::where('gol_darah', 'A')->count();
        $darah_b = Pegawai::where('gol_darah', 'B')->count();
        $darah_ab = Pegawai::where('gol_darah', 'AB')->count();
        $darah_o = Pegawai::where('gol_darah', 'O')->count();

        //berdasarkan Tingkat Pendidikan
        $sma = Pegawai::where('jenjang_pendidikan', 'SMA')->count();
        $d3 = Pegawai::where('jenjang_pendidikan', 'D3')->count();
        $s1 = Pegawai::where('jenjang_pendidikan', 'S1')->count();
        $s2 = Pegawai::where('jenjang_pendidikan', 'S2')->count();
        $s3 = Pegawai::where('jenjang_pendidikan', 'S3')->count();

        //berdasarkan Kelas Jabatan
        // $kelas = Kelas::get()->map(function ($item) {
        //     $item->total = $item->jabatan == null ? 0 : $item->jabatan->map(function ($item2) {
        //         $item2->pegawai = $item2->pegawai;
        //         return $item2;
        //     })->where('pegawai', '!=', null)->count();
        //     return $item;
        // });

        return view('superadmin.rekapitulasi.pns', compact('total', 'l', 'p', 'eselon', 'pangkat', 'darah_a', 'darah_b', 'darah_ab', 'darah_o', 'sma', 'd3', 's1', 's2', 's3'));
    }

    public function rekapData($param)
    {
        $total = Pegawai::get()->count();
        if ($param == 'laki') {
            $data = Pegawai::where('jkel', 'L')->get();
            return view('superadmin.rekapitulasi.jkel', compact('total', 'data'));
        } elseif ($param == 'perempuan') {
            $data = Pegawai::where('jkel', 'P')->get();
            return view('superadmin.rekapitulasi.jkel', compact('total', 'data'));
        } elseif ($param == 'jkel') {
            $data = Pegawai::where('jkel', null)->get();
            return view('superadmin.rekapitulasi.jkel', compact('total', 'data'));
        } elseif ($param == 'darah_a') {
            $data = Pegawai::where('gol_darah', 'A')->get();
            return view('superadmin.rekapitulasi.darah', compact('total', 'data'));
        } elseif ($param == 'darah_b') {
            $data = Pegawai::where('gol_darah', 'B')->get();
            return view('superadmin.rekapitulasi.darah', compact('total', 'data'));
        } elseif ($param == 'darah_ab') {
            $data = Pegawai::where('gol_darah', 'AB')->get();
            return view('superadmin.rekapitulasi.darah', compact('total', 'data'));
        } elseif ($param == 'darah_o') {
            $data = Pegawai::where('gol_darah', 'O')->get();
            return view('superadmin.rekapitulasi.darah', compact('total', 'data'));
        } elseif ($param == 'sma') {
            $data = Pegawai::where('jenjang_pendidikan', 'SMA')->get();
            return view('superadmin.rekapitulasi.jenjang', compact('total', 'data'));
        } elseif ($param == 'd3') {
            $data = Pegawai::where('jenjang_pendidikan', 'D3')->get();
            return view('superadmin.rekapitulasi.jenjang', compact('total', 'data'));
        } elseif ($param == 's1') {
            $data = Pegawai::where('jenjang_pendidikan', 'S1')->get();
            return view('superadmin.rekapitulasi.jenjang', compact('total', 'data'));
        } elseif ($param == 's2') {
            $data = Pegawai::where('jenjang_pendidikan', 'S2')->get();
            return view('superadmin.rekapitulasi.jenjang', compact('total', 'data'));
        } elseif ($param == 's3') {
            $data = Pegawai::where('jenjang_pendidikan', 'S3')->get();
            return view('superadmin.rekapitulasi.jenjang', compact('total', 'data'));
        }
    }

    public function rekapDataEselon($param)
    {
        $total = Pegawai::get()->count();
        $data = Eselon::find($param)->pegawai;
        return view('superadmin.rekapitulasi.eselon', compact('total', 'data'));
    }

    public function rekapDataGolongan($param)
    {
        $total = Pegawai::get()->count();
        $data = Pangkat::find($param)->pegawai;
        return view('superadmin.rekapitulasi.golongan', compact('total', 'data'));
    }
    public function rekapASNjkel()
    {
        $data = Pegawai::orderBy('jkel', 'ASC')->paginate(10);
        $l = Pegawai::where('jkel', 'L')->get()->count();
        $p = Pegawai::where('jkel', 'P')->get()->count();
        $jeniscari = 'jkel';
        return view('superadmin.rekapitulasi.pns', compact('data', 'jeniscari', 'l', 'p'));
    }
    public function rekapASNkelas()
    {
        $data = Pegawai::with('jabatan')->paginate(10);
        $kelas = Kelas::with('jabatan')->get();

        $jeniscari = 'kelas';
        return view('superadmin.rekapitulasi.pns', compact('data', 'jeniscari', 'kelas'));
    }

    public function searchRekapASNkelas()
    {
        $kelas_id = request()->get('kelas_id');
        $jabatan  = Jabatan::where('kelas_id', $kelas_id)->get();
        $map      = $jabatan->map(function ($item) {
            return $item->pegawai;
        })->whereNotNull();

        $data = $this->paginate($map);

        request()->flash();
        $kelas = Kelas::get();

        $jeniscari = 'kelas';
        return view('superadmin.rekapitulasi.pns', compact('data', 'jeniscari', 'kelas'));
    }

    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    public function rekapASNpendidikan()
    {
        $data = Pegawai::with('jabatan')->paginate(10);
        $pendidikan = collect(['SMA', 'D3', 'S1', 'S2', 'S3']);

        $jeniscari = 'pendidikan';
        return view('superadmin.rekapitulasi.pns', compact('data', 'jeniscari', 'pendidikan'));
    }

    public function searchRekapASNpendidikan()
    {
        $jenjang = request()->get('jenjang');
        $data = Pegawai::where('jenjang_pendidikan', $jenjang)->paginate(10);

        request()->flash();
        $pendidikan = collect(['SMA', 'D3', 'S1', 'S2', 'S3']);

        $jeniscari = 'pendidikan';
        return view('superadmin.rekapitulasi.pns', compact('data', 'jeniscari', 'pendidikan'));
    }

    public function aktivitas()
    {
        $data = Aktivitas::paginate(10);
        $validasi = 1;
        return view('superadmin.aktivitas.index', compact('data', 'validasi'));
    }

    public function aktivitasSetuju()
    {
        $data = Aktivitas::where('validasi', 1)->paginate(10);
        $validasi = 1;
        return view('superadmin.aktivitas.index', compact('data', 'validasi'));
    }

    public function aktivitasTolak()
    {
        $data = Aktivitas::where('validasi', 2)->paginate(10);
        $validasi = 2;
        return view('superadmin.aktivitas.index', compact('data', 'validasi'));
    }

    public function aktivitasSearch()
    {
        $search = request()->get('search');
        $validasi = request()->get('validasi');

        $data = Aktivitas::whereHas('pegawai', function ($q) use ($search) {
            $q->where('nip', 'like', '%' . $search . '%')->orWhere('nama', 'like', '%' . $search . '%');
        })->where('validasi', $validasi)->paginate(10);

        $data->appends(['search' => $search, 'validasi' => $validasi])->links();
        request()->flash();
        return view('superadmin.aktivitas.index', compact('data', 'validasi'));
    }

    public function aktivitasSetujui($id)
    {
        Aktivitas::find($id)->update([
            'validasi' => 1,
        ]);
        toastr()->success('Berhasil Di Ubah');
        return back();
    }

    public function aktivitasProses()
    {
        $data = Aktivitas::where('validasi', 0)->paginate(10);
        $validasi = 0;
        return view('superadmin.aktivitas.index', compact('data', 'validasi'));
    }

    public function aktivitasSistem()
    {
        $tanggal   = Carbon::today()->subDays(6)->format('Y-m-d');
        $aktivitas = Aktivitas::where('validasi', 0)->where('tanggal', '<=', $tanggal)->get()->take(100);

        $aktivitas->map(function ($item) {
            if ($item->pegawai->jabatan == null) {
            } else {
                $item->nip      = $item->pegawai->nip;
                $item->nama     = $item->pegawai->nama;
                $item->jabatan  = $item->pegawai->jabatan->nama;
                $item->skpd     = $item->pegawai->skpd->nama;

                $check = $item->pegawai->jabatan->atasan == null ? Jabatan::where('sekda', 1)->first() : $item->pegawai->jabatan->atasan;
                if ($check->pegawai == null) {
                    //Jika Pegawai kosong, Check Lagi Apakah ada PLT atau Tidak
                    if ($check->pegawaiPlt == null) {
                        $atasan = $check;
                    } else {
                        // Cek Lagi Apakah yang memPLT atasan adalah bawahan langsung, menghindari aktifitas menilai diri sendiri
                        if ($item->pegawai->id == $check->pegawaiPlt->id) {
                            //cek lagi, jika sekretaris memPLT Kadis, maka pejabat penilai adalah SEKDA
                            if ($check->atasan == null) {
                                $atasan = Jabatan::where('sekda', 1)->first();
                            } else {
                                $atasan = $check->atasan;
                            }
                        } else {
                            $atasan = $check;
                        }
                    }
                } else {
                    //Jika Pegawai Ada berarti atasannya adalan jabatan definitif
                    $atasan = $check;
                }
                $item->nip_penilai = $atasan->pegawai == null ? $atasan->pegawaiPlt == null ? null : $atasan->pegawaiPlt->nip : $atasan->pegawai->nip;
                $item->nama_penilai = $atasan->pegawai  == null ? $atasan->pegawaiPlt == null ? null : $atasan->pegawaiPlt->nama : $atasan->pegawai->nama;
                $item->skpd_penilai = $atasan->pegawai == null ? $atasan->pegawaiPlt == null ? null : $atasan->skpd->nama : $atasan->skpd->nama;
                $item->jabatan_penilai = $atasan->nama;
                return $item;
            }
        });

        DB::beginTransaction();
        try {
            foreach ($aktivitas as $item) {
                $u = Aktivitas::find($item->id);
                $u->validasi = 1;
                $u->validator = 999999;
                $u->save();

                $s = new Sanksi;
                $s->tanggal_nilai    = Carbon::parse($item->tanggal)->addDays(6)->format('Y-m-d');
                $s->tanggal_aktivitas = $item->tanggal;
                $s->nip_penilai     = $item->nip_penilai;
                $s->nama_penilai    = $item->nama_penilai;
                $s->jabatan_penilai = $item->jabatan_penilai;
                $s->skpd_penilai    = $item->skpd_penilai;
                $s->aktivitas_id    = $item->id;
                $s->aktivitas       = $item->deskripsi;
                $s->nip             = $item->nip;
                $s->nama            = $item->nama;
                $s->jabatan         = $item->jabatan;
                $s->skpd            = $item->skpd;
                $s->save();
            }

            DB::commit();
            toastr()->success('Berhasil Di Proses');
            return back();
        } catch (\Exception $e) {

            DB::rollback();
            dd($e);
            toastr()->error(' Gagal Diproses');
            return back();
        }
    }
}
