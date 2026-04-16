<?php

namespace App\Http\Controllers;

use App\Lampiran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LampiranController extends Controller
{
    public function user()
    {
        return Auth::user();
    }

    public function index()
    {
        $data = Lampiran::where('pegawai_id', $this->user()->pegawai->id)
            ->orderBy('tanggal', 'DESC')
            ->paginate(10);

        return view('pegawai.lampiran.index', compact('data'));
    }

    public function create()
    {
        return view('pegawai.lampiran.create');
    }

    public function store(Request $req)
    {
        $req->validate([
            'tanggal' => 'required|date',
            'url_google_drive' => 'required|url',
        ], [
            'tanggal.required' => 'Tanggal tidak boleh kosong',
            'url_google_drive.required' => 'URL Google Drive tidak boleh kosong',
            'url_google_drive.url' => 'URL Google Drive harus berupa link yang valid',
        ]);

        // Validate that the date is a Friday (5 = Friday in PHP's day format)
        $tanggal = Carbon::parse($req->tanggal);
        if ($tanggal->dayOfWeek !== Carbon::FRIDAY) {
            toastr()->error('Hanya dapat memilih hari Jumat');
            return back()->withInput();
        }

        // Check for duplicate date for this employee
        $exists = Lampiran::where('pegawai_id', $this->user()->pegawai->id)
            ->where('tanggal', $req->tanggal)
            ->exists();

        if ($exists) {
            toastr()->error('Data lampiran untuk tanggal ini sudah ada');
            return back()->withInput();
        }

        $attr = $req->all();
        $attr['pegawai_id'] = $this->user()->pegawai->id;
        $attr['tanggal'] = Carbon::parse($req->tanggal)->format('Y-m-d');

        Lampiran::create($attr);

        toastr()->success('Lampiran berhasil disimpan');
        return redirect('/pegawai/lampiran/wfh');
    }

    public function edit($id)
    {
        $data = Lampiran::find($id);

        if (!$data) {
            toastr()->error('Data tidak ditemukan');
            return redirect('/pegawai/lampiran/wfh');
        }

        if ($this->user()->pegawai->id != $data->pegawai_id) {
            toastr()->error('Anda tidak memiliki akses untuk mengedit data ini');
            return redirect('/pegawai/lampiran/wfh');
        }

        return view('pegawai.lampiran.edit', compact('data'));
    }

    public function update(Request $req, $id)
    {
        $data = Lampiran::find($id);

        if (!$data) {
            toastr()->error('Data tidak ditemukan');
            return redirect('/pegawai/lampiran/wfh');
        }

        if ($this->user()->pegawai->id != $data->pegawai_id) {
            toastr()->error('Anda tidak memiliki akses untuk mengedit data ini');
            return redirect('/pegawai/lampiran/wfh');
        }

        $req->validate([
            'tanggal' => 'required|date',
            'url_google_drive' => 'required|url',
        ], [
            'tanggal.required' => 'Tanggal tidak boleh kosong',
            'url_google_drive.required' => 'URL Google Drive tidak boleh kosong',
            'url_google_drive.url' => 'URL Google Drive harus berupa link yang valid',
        ]);

        // Validate that the date is a Friday (5 = Friday in PHP's day format)
        $tanggal = Carbon::parse($req->tanggal);
        if ($tanggal->dayOfWeek !== Carbon::FRIDAY) {
            toastr()->error('Hanya dapat memilih hari Jumat');
            return back()->withInput();
        }

        // Check for duplicate date for this employee (excluding current record)
        $exists = Lampiran::where('pegawai_id', $this->user()->pegawai->id)
            ->where('tanggal', $req->tanggal)
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            toastr()->error('Data lampiran untuk tanggal ini sudah ada');
            return back()->withInput();
        }

        $attr = $req->all();
        $attr['tanggal'] = Carbon::parse($req->tanggal)->format('Y-m-d');

        $data->update($attr);

        toastr()->success('Lampiran berhasil diupdate');
        return redirect('/pegawai/lampiran/wfh');
    }

    public function delete($id)
    {
        $data = Lampiran::find($id);

        if (!$data) {
            toastr()->error('Data tidak ditemukan');
            return redirect('/pegawai/lampiran/wfh');
        }

        if ($this->user()->pegawai->id != $data->pegawai_id) {
            toastr()->error('Anda tidak memiliki akses untuk menghapus data ini');
            return redirect('/pegawai/lampiran/wfh');
        }

        $data->delete();

        toastr()->success('Lampiran berhasil dihapus');
        return redirect('/pegawai/lampiran/wfh');
    }
}
