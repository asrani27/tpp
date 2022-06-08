<?php

namespace App\Console\Commands;

use App\Kelas;
use App\Jabatan;
use App\Pangkat;
use App\RekapTpp;
use App\Aktivitas;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class Perhitungan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'perhitungan {--jenis=} {--bulan=} {--tahun=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $jenis = $this->option('jenis');
        $bulan = $this->option('bulan');
        $tahun = $this->option('tahun');

        if ($jenis == 'puskesmas') {
            $data = RekapTpp::where('puskesmas_id', '!=', null)->where('puskesmas_id', '!=', 8)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
            foreach ($data as $item) {
                $persen = Jabatan::find($item->jabatan_id);
                if ($persen == null) {
                    $basic_tpp = 0;
                    $pagu = 0;
                    $disiplin = 0;
                    $produktivitas = 0;
                    $kondisi_kerja = 0;
                    $tambahan_beban_kerja = 0;
                    $kelangkaan_profesi = 0;
                    $pagu_asn = 0;
                } else {
                    $basic_tpp = Kelas::where('nama', $item->kelas)->first()->nilai;
                    $pagu      = round($basic_tpp * ($persen->persen_beban_kerja + $persen->persen_prestasi_kerja + $persen->persen_tambahan_beban_kerja) / 100);
                    $disiplin  = $pagu * 40 / 100;
                    $produktivitas  = $pagu * 60 / 100;
                    $kondisi_kerja  = $basic_tpp * Jabatan::find($item->jabatan_id)->persen_kondisi_kerja / 100;
                    $tambahan_beban_kerja  = $basic_tpp * Jabatan::find($item->jabatan_id)->persen_tambahan_beban_kerja / 100;
                    $kelangkaan_profesi  = $basic_tpp * Jabatan::find($item->jabatan_id)->persen_kelangkaan_profesi / 100;
                    $pagu_asn  = $disiplin + $produktivitas + $kondisi_kerja + $tambahan_beban_kerja + $kelangkaan_profesi;
                }
                $item->update([
                    'perhitungan_basic_tpp' => $basic_tpp,
                    'perhitungan_pagu' => $pagu,
                    'perhitungan_disiplin' => $disiplin,
                    'perhitungan_produktivitas' => $produktivitas,
                    'perhitungan_kondisi_kerja' => $kondisi_kerja,
                    'perhitungan_tambahan_beban_kerja' => $tambahan_beban_kerja,
                    'perhitungan_kelangkaan_profesi' => $kelangkaan_profesi,
                    'perhitungan_pagu_tpp_asn' => $pagu_asn,
                ]);
            }

            $data2 = RekapTpp::where('puskesmas_id', '!=', null)->where('puskesmas_id', '!=', 8)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
            foreach ($data2 as $item) {
                $presensi = DB::connection('presensi')->table('ringkasan')->where('nip', $item->nip)->where('bulan', $bulan)->where('tahun', $tahun)->first();
                $pembayaran_ct = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 7)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 420;
                $pembayaran_tl = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 5)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 420;
                $pembayaran_co = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 9)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
                $pembayaran_di = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 4)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 420;

                $aktivitas = Aktivitas::where('pegawai_id', $item->pegawai_id)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('validasi', 1)->get();
                $menit_aktivitas = $aktivitas->sum('menit') + $pembayaran_ct + $pembayaran_tl + $pembayaran_co + $pembayaran_di;
                $jabatan = Jabatan::find($item->jabatan_id);
                if ($presensi == null) {
                    $absensi = 0;
                } else {
                    if ($presensi->persen_kehadiran < 0) {
                        $absensi = 0;
                    } else {
                        $absensi = $presensi->persen_kehadiran;
                    }
                }

                if ($jabatan == null) {
                    $bk_disiplin = 0;
                    $bk_produktivitas = 0;
                    $pk_disiplin = 0;
                    $pk_produktivitas = 0;
                    $kondisi_kerja = 0;
                } else {
                    $disiplin_bk = round((($item->perhitungan_basic_tpp * $jabatan->persen_beban_kerja / 100) * ((40 / 100) * $absensi / 100)));
                    $bk_disiplin = $disiplin_bk < 0 ? 0 : $disiplin_bk;
                    $bk_produktivitas = round($menit_aktivitas >= 6750 ? ($item->perhitungan_basic_tpp * $jabatan->persen_beban_kerja / 100) * 0.6 : 0);
                    $disiplin_pk = round((($item->perhitungan_basic_tpp * $jabatan->persen_prestasi_kerja / 100) * ((40 / 100) * $absensi / 100)));
                    $pk_disiplin = $disiplin_pk < 0 ? 0 : $disiplin_pk;
                    $pk_produktivitas = round($menit_aktivitas >= 6750 ? ($item->perhitungan_basic_tpp * $jabatan->persen_prestasi_kerja / 100) * 0.6 : 0);
                    $kondisi_kerja = round($item->perhitungan_basic_tpp * $jabatan->persen_kondisi_kerja / 100);
                }
                $item->update([
                    'pembayaran_absensi' => $absensi,
                    'pembayaran_aktivitas' => $menit_aktivitas,
                    'pembayaran_bk_disiplin' => $bk_disiplin,
                    'pembayaran_bk_produktivitas' => $bk_produktivitas,
                    'pembayaran_beban_kerja' => ($bk_disiplin + $bk_produktivitas) * 87 / 100,
                    'pembayaran_pk_disiplin' => $pk_disiplin,
                    'pembayaran_pk_produktivitas' => $pk_produktivitas,
                    'pembayaran_prestasi_kerja' => ($pk_disiplin + $pk_produktivitas) * 87 / 100,
                    'pembayaran_kondisi_kerja' => ($absensi == 0 ? 0 : $kondisi_kerja) * 87 / 100,
                    'pembayaran_cutitahunan' => $pembayaran_ct,
                    'pembayaran_tugasluar' => $pembayaran_tl,
                    'pembayaran_covid' => $pembayaran_co,
                    'pembayaran_diklat' => $pembayaran_di,
                    'pembayaran_at' => $aktivitas->sum('menit')
                ]);

                $pph21 = Pangkat::find($item->pangkat_id)->pph;
                $item->update([
                    'pembayaran' => $item->pembayaran_beban_kerja + $item->pembayaran_prestasi_kerja + $item->pembayaran_kondisi_kerja + $item->perhitungan_tambahan_beban_kerja + $item->perhitungan_kelangkaan_profesi,
                ]);

                $potongan_pph21 = round($item->pembayaran * ($pph21 / 100));

                $item->update([
                    'potongan_pph21' => $potongan_pph21,
                    'tpp_diterima' => $item->pembayaran - $potongan_pph21 - $item->potongan_bpjs_1persen,
                ]);
            }
            return 'sukses';
        } else {
            return 'selain puskesmas';
        }
    }
}
