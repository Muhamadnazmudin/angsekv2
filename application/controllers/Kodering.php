<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kodering extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Kodering_model');
        $this->load->library('session');
    }

    public function index() {
        $data['title']    = 'Kodering';
        $data['kodering'] = $this->Kodering_model->get_all();
        $data['kategori'] = $this->Kodering_model->get_kategori();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('kodering/index', $data);
        $this->load->view('templates/footer');
    }

    public function add() {
    $kode = $this->input->post('kode');
    $nama = $this->input->post('nama');
    $kategori_id = $this->input->post('kategori_id');
    $deskripsi = $this->input->post('deskripsi');

    // Cek duplikat lebih dulu
    $cek = $this->db->get_where('kodering', ['kode' => $kode])->row();

    if ($cek) {
        $this->session->set_flashdata('error', 
            "Kode <b>$kode</b> sudah ada! Tidak bisa menambah data duplikat."
        );
        redirect('kodering');
        return;
    }

    // Insert jika aman
    $this->Kodering_model->insert([
        'kode' => $kode,
        'nama' => $nama,
        'kategori_id' => $kategori_id,
        'deskripsi'   => $deskripsi
    ]);

    $this->session->set_flashdata('success', "Data kodering berhasil ditambahkan!");
    redirect('kodering');
}


    public function edit($id) {

        $data['title']    = 'Edit Kodering';
        $data['kodering'] = $this->Kodering_model->get_by_id($id);
        $data['kategori'] = $this->Kodering_model->get_kategori();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('kodering/edit', $data);
        $this->load->view('templates/footer');
    }

    public function update($id) {
    $kode = $this->input->post('kode');
    $nama = $this->input->post('nama');
    $kategori_id = $this->input->post('kategori_id');
    $deskripsi = $this->input->post('deskripsi');

    // CEK jika kode berubah → baru cek duplikat
    $existing = $this->Kodering_model->get_by_id($id);

    if ($existing->kode != $kode) {
        $cek = $this->db->get_where('kodering', ['kode' => $kode])->row();
        if ($cek) {
            $this->session->set_flashdata('error', 
                "Kode <b>$kode</b> sudah dipakai! Tidak boleh duplikat."
            );
            redirect('kodering/edit/'.$id);
            return;
        }
    }

    // UPDATE aman
    $this->Kodering_model->update($id, [
        'kode'        => $kode,
        'nama'        => $nama,
        'kategori_id' => $kategori_id,
        'deskripsi'   => $deskripsi
    ]);

    $this->session->set_flashdata('success', "Data kodering berhasil diperbarui!");
    redirect('kodering');
}

    public function delete($id) {
        $this->Kodering_model->delete($id);
        redirect('kodering');
    }
    public function import()
{
    if (!empty($_FILES['file_excel']['name'])) {

        $this->load->library('Spreadsheet_Lib');
        $file = $_FILES['file_excel']['tmp_name'];

        // LOAD DENGAN PHPSPREADSHEET
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($file);
        $spreadsheet = $reader->load($file);
        $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        $numRow   = 1;
        $inserted = 0;
        $skipped  = 0;

        foreach ($sheet as $row) {

            if ($numRow == 1) {
                $numRow++;
                continue; // skip header
            }

            $kode        = trim($row['A']);
            $nama        = trim($row['B']);
            $kategori_id = trim($row['C']);

            if ($kode == "" || $nama == "") {
                $skipped++;
                continue;
            }

            $cek_kat = $this->db->get_where('kategori_kodering', ['id' => $kategori_id])->row();
            if (!$cek_kat) {
                $skipped++;
                continue;
            }

            $cek_duplikat = $this->db->get_where('kodering', ['kode' => $kode])->row();
            if ($cek_duplikat) {
                $skipped++;
                continue;
            }

            $this->Kodering_model->insert([
                'kode'        => $kode,
                'nama'        => $nama,
                'kategori_id' => $kategori_id,
                'deskripsi'   => ''
            ]);

            $inserted++;
        }

        $this->session->set_flashdata('success',
            "Import selesai: <br>
             • $inserted data berhasil masuk <br>
             • $skipped data dilewati (duplikat / invalid)"
        );
    } else {
        $this->session->set_flashdata('error', 'File Excel belum dipilih!');
    }

    redirect('kodering');
}

public function download_template()
{
    // Pastikan tidak ada output sama sekali
    ini_set('display_errors', 0);
    error_reporting(0);

    $this->load->library('Spreadsheet_Lib');

    // ===============================
    // BUAT EXCEL
    // ===============================
    $excel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $excel->getActiveSheet();
    $sheet->setTitle('Template Kodering');

    $sheet->setCellValue('A1', 'Kode');
    $sheet->setCellValue('B1', 'Nama Kodering');
    $sheet->setCellValue('C1', 'Kategori ID');

    // ===============================
    // BERSIHKAN SEMUA OUTPUT BUFFER
    // ===============================
    while (ob_get_level() > 0) {
        ob_end_clean();
    }

    // ===============================
    // HEADER DOWNLOAD (WAJIB URUT)
    // ===============================
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="Template_Kodering.xlsx"');
    header('Cache-Control: max-age=0');
    header('Pragma: public');
    header('Expires: 0');

    // ===============================
    // OUTPUT FILE
    // ===============================
    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($excel, 'Xlsx');
    $writer->save('php://output');

    exit;
}

}
