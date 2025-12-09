<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Anggaran extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['Anggaran_model','Jurusan_model','Kegiatan_model','Kodering_model']);
        $this->load->library('session');
        $this->load->model('Ref_snp_model');
    }

    public function index() {
    $data['title'] = "Data Anggaran";

    $role_id    = $this->session->userdata('role_id');
    $jurusan_id = $this->session->userdata('jurusan_id');

    if ($role_id == 3) {
        $data['anggaran'] = $this->Anggaran_model->get_all($jurusan_id);
    } else {
        $data['anggaran'] = $this->Anggaran_model->get_all();
    }

    // ambil ref_snp untuk dropdown kegiatan
    $data['ref_snp'] = $this->db->order_by('kode','ASC')->get('ref_snp')->result();

    // relasi lain tetap
    $data['jurusan']  = $this->Jurusan_model->get_all();
    $data['kodering'] = $this->Kodering_model->get_all();

    $this->load->view('templates/header', $data);
    $this->load->view('templates/sidebar');
    $this->load->view('templates/topbar');
    $this->load->view('anggaran/index', $data);
    $this->load->view('templates/footer');
}



    public function tambah() {

    // Ambil nilai dasar
    $volume       = (float) $this->input->post('volume');
    $harga_satuan = (float) $this->input->post('harga_satuan');

    // Hitung total (server-side, anti manipulasi)
    $total = $volume * $harga_satuan;

    // Ambil semua alokasi bulan
    $months = ['jan','feb','mar','apr','mei','jun','jul','agu','sep','okt','nov','des'];
    $alokasi_total = 0;

    foreach ($months as $m) {
        $alokasi_total += (float) $this->input->post($m);
    }

    // VALIDASI: Alokasi tidak boleh melebihi total
    if ($alokasi_total > $total) {
        $this->session->set_flashdata('error',
            "Total alokasi (".number_format($alokasi_total).") melebihi nilai Total (".number_format($total).") !"
        );
        redirect('anggaran'); // balik ke halaman anggaran
        return;
    }

    // Jika valid → simpan
    $data = [
        'jurusan_id'  => ($this->session->userdata('role_id') == 3)
                   ? $this->session->userdata('jurusan_id')
                   : $this->input->post('jurusan_id'),
        'ref_snp_id' => $this->input->post('ref_snp_id'),
        'kodering_id' => $this->input->post('kodering_id'),
        'uraian'       => $this->input->post('uraian'),
        'volume'       => $volume,
        'satuan'       => $this->input->post('satuan'),
        'harga_satuan' => $harga_satuan,
        'catatan'      => $this->input->post('catatan'),
    ];

    foreach ($months as $m) {
        $data[$m] = (float) $this->input->post($m);
    }

    $this->Anggaran_model->insert($data);

    $this->session->set_flashdata('success', 'Data anggaran berhasil ditambahkan.');
    redirect('anggaran');
}
public function edit($id)
{
    $data['title'] = 'Edit Anggaran';
    $data['anggaran'] = $this->Anggaran_model->get_by_id($id);

    // Jika data tidak ditemukan
    if (!$data['anggaran']) {
        show_404();
    }

    // Cek akses jurusan
    if ($this->session->userdata('role_id') == 3) {
        if ($data['anggaran']->jurusan_id != $this->session->userdata('jurusan_id')) {
            show_error("Anda tidak punya akses ke data ini!", 403);
            return;
        }
    }

    // Data relasi
    $data['jurusan']  = $this->Jurusan_model->get_all();
    $data['ref_snp'] = $this->db->order_by('kode','ASC')->get('ref_snp')->result();
    $data['kodering'] = $this->Kodering_model->get_all();

    // Load view
    $this->load->view('templates/header', $data);
    $this->load->view('templates/sidebar');
    $this->load->view('templates/topbar');
    $this->load->view('anggaran/edit', $data);
    $this->load->view('templates/footer');
}


public function update($id)
{
    $row = $this->Anggaran_model->get_by_id($id);
if ($this->session->userdata('role_id') == 3) {
    if ($row->jurusan_id != $this->session->userdata('jurusan_id')) {
        show_error("Anda tidak boleh mengedit data jurusan lain.", 403);
        return;
    }
}

    // Ambil nilai utama
    $volume       = (float) $this->input->post('volume');
    $harga_satuan = (float) $this->input->post('harga_satuan');

    // Total dihitung server-side
    $total = $volume * $harga_satuan;

    // Ambil semua alokasi
    $months = ['jan','feb','mar','apr','mei','jun','jul','agu','sep','okt','nov','des'];
    $alokasi_total = 0;

    foreach ($months as $m) {
        $alokasi_total += (float) $this->input->post($m);
    }

    // ❗ VALIDASI: alokasi tidak boleh melebihi total
    if ($alokasi_total > $total) {
        $this->session->set_flashdata(
            'error',
            "Gagal memperbarui: Total Alokasi (".number_format($alokasi_total).") melebihi Total Anggaran (".number_format($total).") !"
        );

        redirect('anggaran/edit/'.$id);
        return;
    }

    // ---------
    // Jika valid → lanjut update
    // ---------

    $data = [
        'jurusan_id'  => ($this->session->userdata('role_id') == 3)
                   ? $this->session->userdata('jurusan_id')
                   : $this->input->post('jurusan_id'),

        'ref_snp_id' => $this->input->post('ref_snp_id'),
        'kodering_id' => $this->input->post('kodering_id'),

        'uraian'       => $this->input->post('uraian'),
        'volume'       => $volume,
        'satuan'       => $this->input->post('satuan'),
        'harga_satuan' => $harga_satuan,

        'catatan'      => $this->input->post('catatan'),
        'updated_at'   => date('Y-m-d H:i:s')
    ];

    // Tambahkan alokasi bulan
    foreach ($months as $m) {
        $data[$m] = (float) $this->input->post($m);
    }

    $this->Anggaran_model->update($id, $data);

    $this->session->set_flashdata('success', 'Data anggaran berhasil diperbarui.');
    redirect('anggaran');
}

    public function delete($id) {

    $row = $this->Anggaran_model->get_by_id($id);

    if ($this->session->userdata('role_id') == 3) {
        if ($row->jurusan_id != $this->session->userdata('jurusan_id')) {
            show_error("Anda tidak boleh menghapus data jurusan lain!", 403);
            return;
        }
    }

    $this->Anggaran_model->delete($id);
    redirect('anggaran');
}

public function import()
{
    $this->load->library('excel_lib');

    if (empty($_FILES['file_excel']['name'])) {
        $this->session->set_flashdata('error', 'File belum dipilih.');
        redirect('anggaran');
        return;
    }

    $file = $_FILES['file_excel']['tmp_name'];
    $obj = PHPExcel_IOFactory::load($file);
    $sheet = $obj->getActiveSheet();
    $highest = $sheet->getHighestRow();

    $insert = [];
    $skipped = 0;

    for ($i = 2; $i <= $highest; $i++)
    {
        // Ambil nilai excel
        $jurusan_nama   = trim($sheet->getCell("A$i")->getCalculatedValue());
        $snp_nama       = trim($sheet->getCell("B$i")->getCalculatedValue());
        $komponen_nama  = trim($sheet->getCell("C$i")->getCalculatedValue());
        $kegiatan_nama  = trim($sheet->getCell("D$i")->getCalculatedValue());
        $kodering_nama  = trim($sheet->getCell("E$i")->getCalculatedValue());

        // Wajib ada
        if ($jurusan_nama == "" || $kegiatan_nama == "" || $kodering_nama == "") {
            $skipped++;
            continue;
        }

        // =============== VALIDASI DATABASE ===============

        // Jurusan
        $jurusan = $this->db->where('nama', $jurusan_nama)->get('jurusan')->row();
        if (!$jurusan) { $skipped++; continue; }

        // ref_snp (Wajib cocok 3 kolom: snp + komponen + uraian)
        $ref = $this->db
            ->where('uraian_kegiatan', $kegiatan_nama)
            ->where('snp', $snp_nama)
            ->where('komponen', $komponen_nama)
            ->get('ref_snp')->row();

        if (!$ref) { 
            $skipped++; 
            continue; 
        }

        // Kodering
        $kodering = $this->db->where('nama', $kodering_nama)->get('kodering')->row();
        if (!$kodering) { $skipped++; continue; }

        // ========================================

        $uraian = trim($sheet->getCell("G$i")->getCalculatedValue());
        if ($uraian == "") { $skipped++; continue; }

        $volume = floatval($sheet->getCell("H$i")->getCalculatedValue());
        $satuan = trim($sheet->getCell("I$i")->getCalculatedValue());
        $harga  = floatval($sheet->getCell("J$i")->getCalculatedValue());
        $total  = $volume * $harga;

        // Bulanan
        $bulan = [];
        foreach (['L','M','N','O','P','Q','R','S','T','U','V','W'] as $col) {
            $bulan[] = floatval($sheet->getCell("$col$i")->getCalculatedValue());
        }

        $alokasi = array_sum($bulan);
        if ($alokasi > $total) { $skipped++; continue; }

        // Insert Ready
        $insert[] = [
            'jurusan_id'   => $jurusan->id,
            'ref_snp_id'   => $ref->id,              // <===== yang paling penting!
            'kodering_id'  => $kodering->id,
            'uraian'       => $uraian,
            'volume'       => $volume,
            'satuan'       => $satuan,
            'harga_satuan' => $harga,

            'jan' => $bulan[0],'feb' => $bulan[1],'mar' => $bulan[2],'apr' => $bulan[3],
            'mei' => $bulan[4],'jun' => $bulan[5],'jul' => $bulan[6],'agu' => $bulan[7],
            'sep' => $bulan[8],'okt' => $bulan[9],'nov' => $bulan[10],'des' => $bulan[11],

            'catatan' => trim($sheet->getCell("X$i")->getCalculatedValue()),
        ];
    }

    if (!empty($insert)) {
        $this->db->insert_batch('item_anggaran', $insert);
    }

    $this->session->set_flashdata('success',
        "Import selesai.<br>" .
        count($insert) . " data berhasil masuk.<br>" .
        $skipped . " data dilewati."
    );

    redirect('anggaran');
}


public function download_template()
{
    // pastikan tidak ada output sebelumnya
    if (ob_get_length()) ob_end_clean();

    // load library
    $this->load->library('excel_lib');
    $excel = new PHPExcel();

    // ================= SHEET 1: DataAnggaran =================
    $sheet = $excel->setActiveSheetIndex(0);
    $sheet->setTitle('DataAnggaran');

    // Header (dengan SNP & Komponen)
    $headers = [
        'A'=>'Jurusan',
        'B'=>'SNP',
        'C'=>'Komponen',
        'D'=>'Kegiatan (ref_snp)',
        'E'=>'Kodering',
        'F'=>'Jenis_Belanja',
        'G'=>'Uraian',
        'H'=>'Volume',
        'I'=>'Satuan',
        'J'=>'Harga_Satuan',
        'K'=>'Total',
        'L'=>'Jan','M'=>'Feb','N'=>'Mar','O'=>'Apr','P'=>'Mei','Q'=>'Jun',
        'R'=>'Jul','S'=>'Agu','T'=>'Sep','U'=>'Okt','V'=>'Nov','W'=>'Des',
        'X'=>'Catatan'
    ];
    foreach ($headers as $col => $text) {
        $sheet->setCellValue($col.'1', $text);
        $sheet->getStyle($col.'1')->getFont()->setBold(true);
    }
    $sheet->freezePane('A2');

    // ================= SHEET 2: Lists =================
    $lists = $excel->createSheet(1);
    $lists->setTitle('Lists');

    // Jurusan (A)
    $lists->setCellValue('A1','Jurusan');
    $r = 2;
    $jurusan = $this->db->order_by('nama','ASC')->get('jurusan')->result();
    foreach ($jurusan as $j) {
        $lists->setCellValue("A$r", $j->nama);
        $r++;
    }
    $jurusan_last = max($r-1, 2);

    // ref_snp: uraian (B), snp (C), komponen (D)
    $lists->setCellValue('B1','Kegiatan');
    $lists->setCellValue('C1','SNP');
    $lists->setCellValue('D1','Komponen');
    $r = 2;
    $refs = $this->db->order_by('uraian_kegiatan','ASC')->get('ref_snp')->result();
    foreach ($refs as $rf) {
        $lists->setCellValue("B$r", $rf->uraian_kegiatan);
        $lists->setCellValue("C$r", $rf->snp);
        $lists->setCellValue("D$r", $rf->komponen);
        $r++;
    }
    $ref_last = max($r-1, 2);

    // Kodering (E) & mapping jenis belanja (F)
    $lists->setCellValue('E1','Kodering');
    $lists->setCellValue('F1','Jenis_Belanja');
    $r = 2;
    $kodering = $this->db->order_by('nama','ASC')->get('kodering')->result();
    foreach ($kodering as $kd) {
        $kategori = $this->db->where('id', $kd->kategori_id)->get('kategori_kodering')->row();
        $lists->setCellValue("E$r", $kd->nama);
        $lists->setCellValue("F$r", $kategori ? $kategori->nama : '');
        $r++;
    }
    $kodering_last = max($r-1, 2);

    // Named ranges (safe)
    if ($jurusan_last >= 2) {
        $excel->addNamedRange(new PHPExcel_NamedRange('LIST_JURUSAN', $lists, '$A$2:$A$'.$jurusan_last));
    }
    if ($ref_last >= 2) {
        $excel->addNamedRange(new PHPExcel_NamedRange('LIST_REFSNP', $lists, '$B$2:$B$'.$ref_last));
    }
    if ($kodering_last >= 2) {
        $excel->addNamedRange(new PHPExcel_NamedRange('LIST_KODERING', $lists, '$E$2:$E$'.$kodering_last));
    }

    // ================= DROPDOWN & FORMULAS =================
    // gunakan jumlah baris moderat agar ringan
    $maxRow = 300;
    for ($i = 2; $i <= $maxRow; $i++) {

        // Jurusan (A)
        $dv = $sheet->getCell("A$i")->getDataValidation();
        $dv->setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
           ->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
           ->setAllowBlank(true)
           ->setShowInputMessage(true)
           ->setShowErrorMessage(true)
           ->setShowDropDown(true)
           ->setFormula1('=LIST_JURUSAN');

        // Kegiatan (D) dari ref_snp
        $dv = $sheet->getCell("D$i")->getDataValidation();
        $dv->setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
           ->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
           ->setAllowBlank(true)
           ->setShowInputMessage(true)
           ->setShowErrorMessage(true)
           ->setShowDropDown(true)
           ->setFormula1('=LIST_REFSNP');

        // Kodering (E)
        $dv = $sheet->getCell("E$i")->getDataValidation();
        $dv->setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
           ->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
           ->setAllowBlank(true)
           ->setShowInputMessage(true)
           ->setShowErrorMessage(true)
           ->setShowDropDown(true)
           ->setFormula1('=LIST_KODERING');

        // SNP otomatis (B) dan Komponen otomatis (C) berdasarkan Kegiatan (D)
        // VLOOKUP range Lists!$B$2:$D$ref_last, kolom 2 = SNP, 3 = Komponen
        $sheet->setCellValue("B$i", "=IFERROR(VLOOKUP(D$i, Lists!\$B\$2:\$D\$$ref_last, 2, FALSE),\"\")");
        $sheet->setCellValue("C$i", "=IFERROR(VLOOKUP(D$i, Lists!\$B\$2:\$D\$$ref_last, 3, FALSE),\"\")");

        // Jenis Belanja otomatis (F) dari Kodering (Lists!E:F)
        $sheet->setCellValue("F$i", "=IFERROR(VLOOKUP(E$i, Lists!\$E\$2:\$F\$$kodering_last, 2, FALSE),\"\")");

        // Total otomatis (K) = H * J
        $sheet->setCellValue("K$i", "=IFERROR(H$i * J$i, 0)");
    }

    // ================= OUTPUT (headers aman & flush) =================
    // kirim header melalui PHP (bukan CI output) untuk binary stream
    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    header('Content-Disposition: attachment; filename="Template_Import_Anggaran.xlsx"');
    header("Cache-Control: max-age=0");
    // disable zlib output compression jika aktif (kadang menyebabkan masalah)
    if (function_exists('apache_setenv')) @apache_setenv('no-gzip', '1');
    @ini_set('zlib.output_compression', 'Off');

    $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

    // save to output
    $writer->save('php://output');

    // done
    exit;
}


public function get_jenis_belanja($kategori_id)
{
    $row = $this->db
        ->select('nama')
        ->where('id', $kategori_id)
        ->get('kategori_kodering')
        ->row();

    echo json_encode([
        'jenis_belanja' => $row ? $row->nama : ""
    ]);
}
public function delete_all_jurusan()
{
    $jurusan_id = $this->session->userdata('jurusan_id');

    if (!$jurusan_id) {
        $this->session->set_flashdata('error', 'Jurusan tidak ditemukan.');
        redirect('anggaran');
        return;
    }

    // Hapus semua anggaran milik jurusan ini
    $this->db->where('jurusan_id', $jurusan_id);
    $this->db->delete('item_anggaran');

    $this->session->set_flashdata('success', 'Semua anggaran jurusan Anda berhasil dihapus.');
    redirect('anggaran');
}


}
