<?php

namespace App\Http\Controllers;

use App\Role;
use App\Skpd;
use App\User;
use App\Jabatan;
use App\Pegawai;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function skpd_id()
    {
        return Auth::user()->skpd->id;
    }

    public function pegawai()
    {
        $data = Pegawai::with('jabatan', 'user')->where('skpd_id', $this->skpd_id())->orderBy('urutan', 'ASC')->paginate(10);

        return view('admin.pegawai.index', compact('data'));
    }

    public function searchPegawai()
    {
        $search = request()->get('search');
        $data   = Pegawai::with('jabatan', 'user')
            ->where('skpd_id', $this->skpd_id())
            ->where('nama', 'LIKE', '%' . $search . '%')
            ->orWhere(function ($query) use ($search) {
                $query->where('skpd_id', $this->skpd_id())->where('nip', 'LIKE', '%' . $search . '%');
            })

            ->orderBy('urutan', 'ASC')->paginate(10);

        $data->appends(['search' => $search])->links();
        request()->flash();
        return view('admin.pegawai.index', compact('data'))->withInput(request()->all());
    }

    public function addPegawai()
    {
        $jabatan = Jabatan::where('skpd_id', Auth::user()->skpd->id)->get()->map(function ($item) {
            $item->pegawai = $item->pegawai;
            return $item;
        })->where('pegawai', null);

        $data['nip'] = '';
        $data['nm_lengkap'] = '';

        return view('admin.pegawai.create', compact('jabatan', 'data'));
    }

    public function storePegawai(Request $req)
    {
        // $messages = [
        //     'numeric' => 'Inputan Harus Angka',
        //     'min'     => 'Harus 18 Digit',
        //     'unique'  => 'NIP sudah Ada',
        // ];

        // $rules = [
        // ];

        $validator = Validator::make($req->all(), [
            'nip' =>  'unique:pegawai|min:18|numeric',
            'nama' => 'required'
        ]);

        if ($validator->fails()) {
            $req->flash();
            toastr()->error('NIP sudah ada');
            return back();
        }
        //$req->validate($rules, $messages);

        $req->flash();

        if (count(Skpd::find($this->skpd_id())->pegawai) == 0) {
            $urutan      = 1;
        } else {
            $urutan      = Skpd::find($this->skpd_id())->pegawai->sortBy('urutan')->last()->urutan + 1;
        }

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

        $jabatan = Jabatan::where('skpd_id', Auth::user()->skpd->id)->get()->map(function ($item) {
            $item->pegawai = $item->pegawai;
            return $item;
        })->where('pegawai', null);
        return view('admin.pegawai.edit', compact('data', 'jabatan'));
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
    }

    public function deletePegawai($id)
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

    public function createUser($id)
    {
        $rolePegawai = Role::where('name', 'pegawai')->first();
        $data = Pegawai::find($id);
        $checkUsername = User::where('username', $data->nip)->first();
        if ($checkUsername == null) {
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

            toastr()->success('Username : ' . $data->nip . '<br> Password : ' . Carbon::parse($data->tanggal_lahir)->format('dmY'));
        } else {
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

        toastr()->success('Password : ' . Carbon::parse($tgl_lahir)->format('dmY'));
        return back();
    }

    public function jabatan()
    {
        $skpd_id = Auth::user()->skpd->id;
        $edit = false;

        return view('admin.jabatan.index', compact('skpd_id', 'edit'));
    }

    public function editJabatan($id)
    {
        $skpd_id = Auth::user()->skpd->id;
        $edit = true;
        $jabatan = Jabatan::find($id);
        return view('admin.jabatan.index', compact('id', 'edit', 'jabatan', 'skpd_id'));
    }

    public function storeJabatan(Request $req)
    {
        $skpd_id = Auth::user()->skpd->id;

        $attr  = $req->all();
        $attr['skpd_id']    = $skpd_id;

        if ($req->jabatan_id == null) {
            $attr['tingkat']    = 1;
        } else {
            $attr['tingkat']    = Jabatan::find($req->jabatan_id)->tingkat + 1;
        }
        Jabatan::create($attr);
        toastr()->success('Jabatan Berhasil Di Simpan');

        return redirect('/admin/jabatan');
    }

    public function updateJabatan(Request $req, $id)
    {
        if ($req->jabatan_id == null) {
            $jabatan = Jabatan::find($id);
            $jabatan->nama = $req->nama;
            $jabatan->kelas_id = $req->kelas_id;
            $jabatan->save();
            toastr()->success('Jabatan Berhasil Di Update');
            return redirect('/admin/jabatan');
        } else {
            $tingkat1 = Jabatan::find($req->jabatan_id)->tingkat;
            $tingkat2 = Jabatan::find($id)->tingkat;

            // if ($tingkat1 == $tingkat2) {
            //     toastr()->error('Jabatan Tidak bisa di pindah karena setara');
            //     return back();
            // } elseif ($tingkat1 > $tingkat2) {
            //     toastr()->error('Jabatan Tidak bisa di pindah Ke tingkat yang lebih rendah');
            //     return back();
            // } elseif (abs($tingkat1 - $tingkat2) >= 2) {
            //     toastr()->error('Jabatan Tidak bisa di pindah Ke tingkat yang lebih Tinggi');
            //     return back();
            // } else {

            $jabatan = Jabatan::find($id);
            $jabatan->jabatan_id = $req->jabatan_id;
            $jabatan->nama = $req->nama;
            $jabatan->kelas_id = $req->kelas_id;
            $jabatan->save();
            toastr()->success('Jabatan Berhasil Di Update');

            return redirect('/admin/jabatan');
            // }
        }
    }

    public function deleteJabatan($id)
    {
        try {
            Jabatan::find($id)->delete();
            toastr()->success('Jabatan Berhasil Di Hapus');
        } catch (\Exception $e) {
            toastr()->error('Jabatan Tidak Bisa Di Hapus Karena Memiliki Bawahan');
        }
        return redirect('/admin/jabatan');
    }

    public function editPersen()
    {

        $data = Auth::user()->skpd->jabatan;

        return view('admin.edit_persen', compact('data'));
    }

    public function updatePersen(Request $req)
    {
        DB::beginTransaction();
        try {
            $count = count($req->jabatan_id);
            for ($i = 0; $i < $count; $i++) {
                Jabatan::findOrfail($req->jabatan_id[$i])->update([
                    'jenis_jabatan' => $req->jenis_jabatan[$i],
                    'persentase_tpp' => $req->persentase_tpp[$i],
                    'persen_beban_kerja' => $req->persen_beban_kerja[$i],
                    'persen_prestasi_kerja' => $req->persen_prestasi_kerja[$i],
                    'tambahan_persen_tpp' => $req->tambahan_persen_tpp[$i],
                ]);
            }

            DB::commit();
            toastr()->success('Data Berhasil di Update');
        } catch (\Exception $e) {
            DB::rollback();

            toastr()->error('Gagal Update Data');
        }
        return back();
    }

    public function org()
    {
        $skpd_id = Auth::user()->skpd->id;
        $data = Jabatan::with('pegawai')->where('skpd_id', $skpd_id)->get();

        $map = $data->map(function ($item) {
            $item->pegawai = $item->pegawai == null ? '-' : $item->pegawai->nama;

            $item->format = [['v' => (string)$item->id, 'f' => '<img src="https://p.kindpng.com/picc/s/78-786207_user-avatar-png-user-avatar-icon-png-transparent.png" width="35px"><br/><b>' . $item->nama . '</b><br/>' . $item->pegawai], $item->jabatan_id == null ? '' : (string)$item->jabatan_id, ''];
            return $item->format;
        });

        $json = response()->json($map);

        return view('admin.jabatan.org', compact('json'));
    }

    public function org2()
    {
        $skpd_id = Auth::user()->skpd->id;
        $jabatan = Jabatan::where('skpd_id', $skpd_id)->where('jabatan_id', null)->first();

        return view('admin.jabatan.org2', compact('jabatan'));
    }

    public function tpp()
    {
        $bulan = request()->get('bulan');
        $tahun = request()->get('tahun');
        toastr()->info('Dalam Tahap pengembangan');
        return back();
    }

    public function checktobkd(Request $req)
    {
        try {
            $nip = request()->nip . '/api20211';
            $client = new Client(['base_uri' => 'http://103.178.83.6:1028/ci4-bkd/pegawai/']);
            $response = $client->request('GET', 'apipegawai/' . $nip);
            $resp = json_decode($response->getBody())->isidata[0];

            $data['nip'] = $resp->nip;
            $data['nm_lengkap'] = $resp->nm_lengkap;

            $jabatan = Jabatan::where('skpd_id', Auth::user()->skpd->id)->get()->map(function ($item) {
                $item->pegawai = $item->pegawai;
                return $item;
            })->where('pegawai', null);
            request()->flash();
            //toastr()->success('Data Pegawai berhasil Di Temukan');
            return view('admin.pegawai.create', compact('jabatan', 'data'));
        } catch (\Exception $e) {

            toastr()->error('Data Pegawai Tidak Di Temukan, mungkin NIP anda salah');
            return back();
        }
    }
}
