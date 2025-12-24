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
    public function export_excel()
{
    // matikan error ke output (binary stream)
    ini_set('display_errors', 0);
    error_reporting(0);

    // bersihkan buffer
    while (ob_get_level() > 0) {
        ob_end_clean();
    }

    $excel = new Spreadsheet();
    $sheet = $excel->getActiveSheet();
    $sheet->setTitle('Laporan RKAS');

    // HEADER (TIDAK DIUBAH)
    $headers = [
        'A'=>'Jurusan',
        'B'=>'SNP',
        'C'=>'Komponen',
        'D'=>'Kegiatan',
        'E'=>'Kode',
        'F'=>'Jenis Belanja',
        'G'=>'Uraian',
        'H'=>'Volume',
        'I'=>'Satuan',
        'J'=>'Harga',
        'K'=>'Total',
        'L'=>'Jan','M'=>'Feb','N'=>'Mar','O'=>'Apr','P'=>'Mei','Q'=>'Jun',
        'R'=>'Jul','S'=>'Agu','T'=>'Sep','U'=>'Okt','V'=>'Nov','W'=>'Des'
    ];

    foreach ($headers as $col => $text) {
        $sheet->setCellValue($col.'1', $text);
        $sheet->getStyle($col.'1')->getFont()->setBold(true);
    }

    // FILTER (SAMA PERSIS DENGAN INDEX)
    $role_id  = $this->session->userdata('role_id');
    $user_jurusan_id = $this->session->userdata('jurusan_id');

    $jurusan_id  = $this->input->get('filter_jurusan');
    $kodering_id = $this->input->get('filter_kodering');
    $kategori_id = $this->input->get('filter_kategori');

    if ($role_id == 3) {
        $jurusan_id = $user_jurusan_id;
    }

    // QUERY (TIDAK DIUBAH)
    $this->db->select("
        item_anggaran.*,
        jurusan.nama AS jurusan_nama,
        ref_snp.snp,
        ref_snp.komponen,
        ref_snp.uraian_kegiatan AS kegiatan_nama,
        kodering.kode AS kode_rka,
        kategori_kodering.nama AS jenis_belanja
    ");
    $this->db->from("item_anggaran");
    $this->db->join("jurusan", "jurusan.id=item_anggaran.jurusan_id", "left");
    $this->db->join("ref_snp", "ref_snp.id=item_anggaran.ref_snp_id", "left");
    $this->db->join("kodering", "kodering.id=item_anggaran.kodering_id", "left");
    $this->db->join("kategori_kodering", "kategori_kodering.id=kodering.kategori_id", "left");

    if (!empty($jurusan_id))  $this->db->where("item_anggaran.jurusan_id",$jurusan_id);
    if (!empty($kodering_id)) $this->db->where("kodering.id",$kodering_id);
    if (!empty($kategori_id)) $this->db->where("kategori_kodering.id",$kategori_id);

    $rows = $this->db->get()->result();

    // ISI DATA (TIDAK DIUBAH)
    $r = 2;
    foreach ($rows as $d) {
        $sheet->setCellValue("A$r", $d->jurusan_nama);
        $sheet->setCellValue("B$r", $d->snp);
        $sheet->setCellValue("C$r", $d->komponen);
        $sheet->setCellValue("D$r", $d->kegiatan_nama);
        $sheet->setCellValue("E$r", $d->kode_rka);
        $sheet->setCellValue("F$r", $d->jenis_belanja);
        $sheet->setCellValue("G$r", $d->uraian);
        $sheet->setCellValue("H$r", $d->volume);
        $sheet->setCellValue("I$r", $d->satuan);
        $sheet->setCellValue("J$r", $d->harga_satuan);
        $sheet->setCellValue("K$r", $d->volume * $d->harga_satuan);

        $months = ['jan','feb','mar','apr','mei','jun','jul','agu','sep','okt','nov','des'];
        $col = 'L';
        foreach ($months as $m) {
            $sheet->setCellValue($col.$r, $d->$m);
            $col++;
        }
        $r++;
    }

    // OUTPUT DOWNLOAD
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="Laporan_RKAS.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = IOFactory::createWriter($excel, 'Xlsx');
    $writer->save('php://output');
    exit;
}

}
