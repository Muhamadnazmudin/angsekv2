<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;


class Laporan_rkas extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Jurusan_model');
        $this->load->model('Kodering_model');
        $this->load->database();
    }

    public function index()
    {
        $data['title'] = "Laporan RKAS";

        $role_id  = $this->session->userdata('role_id');
        $user_jurusan_id = $this->session->userdata('jurusan_id');

        // FILTER
        $jurusan_id  = $this->input->get('filter_jurusan');
        $kodering_id = $this->input->get('filter_kodering');
        $kategori_id = $this->input->get('filter_kategori');

        // Role jurusan → paksa filter
        if ($role_id == 3) {
            $jurusan_id = $user_jurusan_id;
        }

        // Dropdown
        $data['jurusan']  = $this->db->order_by('nama')->get('jurusan')->result();
        $data['kategori'] = $this->db->order_by('nama')->get('kategori_kodering')->result();
        $data['kodering'] = $this->db->order_by('nama')->get('kodering')->result();

        // QUERY RKAS BARU
        $this->db->select("
            item_anggaran.*,
            jurusan.nama AS jurusan_nama,

            ref_snp.snp,
            ref_snp.komponen,
            ref_snp.uraian_kegiatan AS kegiatan_nama,

            kodering.kode AS kode_rka,
            kodering.nama AS nama_rka,
            kategori_kodering.nama AS jenis_belanja
        ");

        $this->db->from("item_anggaran");
        $this->db->join("jurusan", "jurusan.id=item_anggaran.jurusan_id", "left");
        $this->db->join("ref_snp", "ref_snp.id=item_anggaran.ref_snp_id", "left");
        $this->db->join("kodering", "kodering.id=item_anggaran.kodering_id", "left");
        $this->db->join("kategori_kodering", "kategori_kodering.id=kodering.kategori_id", "left");

        // Filter
        if (!empty($jurusan_id))
            $this->db->where("item_anggaran.jurusan_id", $jurusan_id);

        if (!empty($kodering_id))
            $this->db->where("kodering.id", $kodering_id);

        if (!empty($kategori_id))
            $this->db->where("kategori_kodering.id", $kategori_id);

        $this->db->order_by("jurusan.nama");
        $this->db->order_by("ref_snp.uraian_kegiatan");
        $this->db->order_by("kodering.kode");

        $data['rkas'] = $this->db->get()->result();

        // LOAD VIEW
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('laporan/rkas_index', $data);
        $this->load->view('templates/footer');
    }

    // ===========================
    // EXPORT EXCEL BARU
    // ===========================
    //export excel tanpa volume perbulan
//     public function export_excel()
// {
//     // matikan error ke output (binary stream)
//     ini_set('display_errors', 0);
//     error_reporting(0);

//     // bersihkan buffer
//     while (ob_get_level() > 0) {
//         ob_end_clean();
//     }

//     $excel = new Spreadsheet();
//     $sheet = $excel->getActiveSheet();
//     $sheet->setTitle('Laporan RKAS');

//     // HEADER (TIDAK DIUBAH)
//     $headers = [
//     'A'=>'Jurusan',
//     'B'=>'SNP',
//     'C'=>'Komponen',
//     'D'=>'Kegiatan',
//     'E'=>'Kode',
//     'F'=>'Nama Kodering',
//     'G'=>'Jenis Belanja',
//     'H'=>'Uraian',
//     'I'=>'Volume',
//     'J'=>'Satuan',
//     'K'=>'Harga',
//     'L'=>'Total',
//     'M'=>'Jan','N'=>'Feb','O'=>'Mar','P'=>'Apr','Q'=>'Mei','R'=>'Jun',
//     'S'=>'Jul','T'=>'Agu','U'=>'Sep','V'=>'Okt','W'=>'Nov','X'=>'Des'
// ];


//     foreach ($headers as $col => $text) {
//         $sheet->setCellValue($col.'1', $text);
//         $sheet->getStyle($col.'1')->getFont()->setBold(true);
//     }

//     // FILTER (SAMA PERSIS DENGAN INDEX)
//     $role_id  = $this->session->userdata('role_id');
//     $user_jurusan_id = $this->session->userdata('jurusan_id');

//     $jurusan_id  = $this->input->get('filter_jurusan');
//     $kodering_id = $this->input->get('filter_kodering');
//     $kategori_id = $this->input->get('filter_kategori');

//     if ($role_id == 3) {
//         $jurusan_id = $user_jurusan_id;
//     }

//     // QUERY (TIDAK DIUBAH)
//     $this->db->select("
//         item_anggaran.*,
//         jurusan.nama AS jurusan_nama,
//         ref_snp.snp,
//         ref_snp.komponen,
//         ref_snp.uraian_kegiatan AS kegiatan_nama,
//         kodering.kode AS kode_rka,
//         kodering.nama AS nama_kodering,
//         kategori_kodering.nama AS jenis_belanja
//     ");
//     $this->db->from("item_anggaran");
//     $this->db->join("jurusan", "jurusan.id=item_anggaran.jurusan_id", "left");
//     $this->db->join("ref_snp", "ref_snp.id=item_anggaran.ref_snp_id", "left");
//     $this->db->join("kodering", "kodering.id=item_anggaran.kodering_id", "left");
//     $this->db->join("kategori_kodering", "kategori_kodering.id=kodering.kategori_id", "left");

//     if (!empty($jurusan_id))  $this->db->where("item_anggaran.jurusan_id",$jurusan_id);
//     if (!empty($kodering_id)) $this->db->where("kodering.id",$kodering_id);
//     if (!empty($kategori_id)) $this->db->where("kategori_kodering.id",$kategori_id);

//     $rows = $this->db->get()->result();

//     // ISI DATA (TIDAK DIUBAH)
//     $r = 2;
//     foreach ($rows as $d) {
//         $sheet->setCellValue("A$r", $d->jurusan_nama);
// $sheet->setCellValue("B$r", $d->snp);
// $sheet->setCellValue("C$r", $d->komponen);
// $sheet->setCellValue("D$r", $d->kegiatan_nama);
// $sheet->setCellValue("E$r", $d->kode_rka);
// $sheet->setCellValue("F$r", $d->nama_kodering);
// $sheet->setCellValue("G$r", $d->jenis_belanja);
// $sheet->setCellValue("H$r", $d->uraian);
// $sheet->setCellValue("I$r", $d->volume);
// $sheet->setCellValue("J$r", $d->satuan);
// $sheet->setCellValue("K$r", $d->harga_satuan);
// $sheet->setCellValue("L$r", $d->volume * $d->harga_satuan);


//        $months = ['jan','feb','mar','apr','mei','jun','jul','agu','sep','okt','nov','des'];
// $col = 'M';

// foreach ($months as $m) {
//     $value = ($d->$m == 0) ? '' : $d->$m; // ⬅️ ini kuncinya
//     $sheet->setCellValue($col.$r, $value);
//     $col++;
// }


//         $r++;
//     }

//     // OUTPUT DOWNLOAD
//     header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//     header('Content-Disposition: attachment; filename="Laporan_RKAS.xlsx"');
//     header('Cache-Control: max-age=0');

//     $writer = IOFactory::createWriter($excel, 'Xlsx');
//     $writer->save('php://output');
//     exit;
// }

//export excel dengan volume perbulan
public function export_excel()
{
    // matikan error ke output (binary stream)
    ini_set('display_errors', 0);
    error_reporting(0);

    while (ob_get_level() > 0) {
        ob_end_clean();
    }

    $excel = new Spreadsheet();
    $sheet = $excel->getActiveSheet();
    $sheet->setTitle('Laporan RKAS');

    // ================= HEADER =================
    $headers = [
        'A'=>'Jurusan',
        'B'=>'SNP',
        'C'=>'Komponen',
        'D'=>'Kegiatan',
        'E'=>'Kode',
        'F'=>'Nama Kodering',
        'G'=>'Jenis Belanja',
        'H'=>'Uraian',
        'I'=>'Volume',
        'J'=>'Satuan',
        'K'=>'Harga',
        'L'=>'Total',

        'M'=>'Jan', 'N'=>'Jan-Renc',
        'O'=>'Feb', 'P'=>'Feb-Renc',
        'Q'=>'Mar', 'R'=>'Mar-Renc',
        'S'=>'Apr', 'T'=>'Apr-Renc',
        'U'=>'Mei', 'V'=>'Mei-Renc',
        'W'=>'Jun', 'X'=>'Jun-Renc',
        'Y'=>'Jul', 'Z'=>'Jul-Renc',
        'AA'=>'Agu', 'AB'=>'Agu-Renc',
        'AC'=>'Sep', 'AD'=>'Sep-Renc',
        'AE'=>'Okt', 'AF'=>'Okt-Renc',
        'AG'=>'Nov', 'AH'=>'Nov-Renc',
        'AI'=>'Des', 'AJ'=>'Des-Renc',
    ];

    foreach ($headers as $col => $text) {
        $sheet->setCellValue($col.'1', $text);
        $sheet->getStyle($col.'1')->getFont()->setBold(true);
    }

    // ================= FILTER =================
    $role_id  = $this->session->userdata('role_id');
    $user_jurusan_id = $this->session->userdata('jurusan_id');

    $jurusan_id  = $this->input->get('filter_jurusan');
    $kodering_id = $this->input->get('filter_kodering');
    $kategori_id = $this->input->get('filter_kategori');

    if ($role_id == 3) {
        $jurusan_id = $user_jurusan_id;
    }

    // ================= QUERY =================
    $this->db->select("
        item_anggaran.*,
        jurusan.nama AS jurusan_nama,
        ref_snp.snp,
        ref_snp.komponen,
        ref_snp.uraian_kegiatan AS kegiatan_nama,
        kodering.kode AS kode_rka,
        kodering.nama AS nama_kodering,
        kategori_kodering.nama AS jenis_belanja
    ");
    $this->db->from("item_anggaran");
    $this->db->join("jurusan", "jurusan.id=item_anggaran.jurusan_id", "left");
    $this->db->join("ref_snp", "ref_snp.id=item_anggaran.ref_snp_id", "left");
    $this->db->join("kodering", "kodering.id=item_anggaran.kodering_id", "left");
    $this->db->join("kategori_kodering", "kategori_kodering.id=kodering.kategori_id", "left");

    if (!empty($jurusan_id))  $this->db->where("item_anggaran.jurusan_id", $jurusan_id);
    if (!empty($kodering_id)) $this->db->where("kodering.id", $kodering_id);
    if (!empty($kategori_id)) $this->db->where("kategori_kodering.id", $kategori_id);

    $rows = $this->db->get()->result();

    // ================= ISI DATA =================
    $r = 2;
    foreach ($rows as $d) {

        $sheet->setCellValue("A$r", $d->jurusan_nama);
        $sheet->setCellValue("B$r", $d->snp);
        $sheet->setCellValue("C$r", $d->komponen);
        $sheet->setCellValue("D$r", $d->kegiatan_nama);
        $sheet->setCellValue("E$r", $d->kode_rka);
        $sheet->setCellValue("F$r", $d->nama_kodering);
        $sheet->setCellValue("G$r", $d->jenis_belanja);
        $sheet->setCellValue("H$r", $d->uraian);
        $sheet->setCellValue("I$r", $d->volume);
        $sheet->setCellValue("J$r", $d->satuan);
        $sheet->setCellValue("K$r", $d->harga_satuan);
        $sheet->setCellValue("L$r", $d->volume * $d->harga_satuan);

        // ===== BULAN + RENCANA (VOLUME) =====
        $months = ['jan','feb','mar','apr','mei','jun','jul','agu','sep','okt','nov','des'];
        $col = 'M';

        foreach ($months as $m) {

            // nilai rupiah bulan
            $nilai = ($d->$m == 0) ? '' : $d->$m;

            // hitung volume per bulan
            if ($d->$m > 0 && $d->harga_satuan > 0) {
                $vol_bulan = $d->$m / $d->harga_satuan;
            } else {
                $vol_bulan = '';
            }

            $sheet->setCellValue($col.$r, $nilai);
            $col++;

            $sheet->setCellValue($col.$r, $vol_bulan);
            $col++;
        }

        $r++;
    }

    // ================= OUTPUT =================
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="Laporan_RKAS.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = IOFactory::createWriter($excel, 'Xlsx');
    $writer->save('php://output');
    exit;
}

}
