<?php

namespace App\Exports;

use App\Jabatan;
use App\Pegawai;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PegawaiAllExport implements FromView, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        $pegawai = Pegawai::where('is_aktif', 1)->whereNotIn('skpd_id', [17, 18, 35])->get()->map(function ($item) {


            //cek atasan apakah PLT atau Bukan
            //$check = $item->jabatan->atasan == null ? Jabatan::where('sekda', 1)->first() : $item->jabatan->atasan;
            if ($item->jabatan === null) {
                $atasan = null;
            } else {
                if ($item->jabatan->atasan == null) {
                    $check = Jabatan::where('sekda', 1)->first();
                } else {
                    $check = $item->jabatan->atasan;
                }

                if ($check->pegawai == null) {
                    //Jika Pegawai kosong, Check Lagi Apakah ada PLT atau Tidak
                    if ($check->pegawaiPlt == null) {
                        $atasan = $check;
                    } else {
                        // Cek Lagi Apakah yang memPLT atasan adalah bawahan langsung, menghindari aktifitas menilai diri sendiri
                        if ($item->id == $check->pegawaiPlt->id) {
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
            }

            $item->namaatasan = $atasan;
            $item->kelas = ($item->jabatan == null ? null : $item->jabatan->kelas) == null ? null : $item->jabatan->kelas->nama;
            return $item;
        });
        $data = $pegawai->sortBy([
            fn($a, $b) => strcmp($a->skpd->nama, $b->skpd->nama), // Urutkan nama SKPD
            fn($a, $b) => $b->kelas <=> $a->kelas, // Lalu urutkan kelas jabatan
        ]);

        $data->values()->all(); // Reset kunci array
        return view('superadmin.export.pegawai', compact('data'));
    }
}
