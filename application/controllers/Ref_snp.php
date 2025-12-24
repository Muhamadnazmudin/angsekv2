<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;


class Ref_snp extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Ref_snp_model');
    }
    public function index()
{
    $this->load->library('pagination');

    // Konfigurasi paginasi
    $config['base_url'] = site_url('ref_snp/index');
    $config['total_rows'] = $this->Ref_snp_model->count_all();
    $config['per_page'] = 10; // jumlah data per halaman
    $config['uri_segment'] = 3;

    // Styling Bootstrap (SB Admin 2)
    $config['full_tag_open'] = '<nav><ul class="pagination justify-content-center">';
    $config['full_tag_close'] = '</ul></nav>';

    $config['first_link'] = 'First';
    $config['first_tag_open'] = '<li class="page-item"><span class="page-link">';
    $config['first_tag_close'] = '</span></li>';

    $config['last_link'] = 'Last';
    $config['last_tag_open'] = '<li class="page-item"><span class="page-link">';
    $config['last_tag_close'] = '</span></li>';

    $config['next_link'] = '&raquo;';
    $config['next_tag_open'] = '<li class="page-item"><span class="page-link">';
    $config['next_tag_close'] = '</span></li>';

    $config['prev_link'] = '&laquo;';
    $config['prev_tag_open'] = '<li class="page-item"><span class="page-link">';
    $config['prev_tag_close'] = '</span></li>';

    $config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
    $config['cur_tag_close'] = '</span></li>';

    $config['num_tag_open'] = '<li class="page-item"><span class="page-link">';
    $config['num_tag_close'] = '</span></li>';

    $this->pagination->initialize($config);

    // Ambil offset dari URL
    $page = $this->uri->segment(3);
    $offset = $page ? $page : 0;

    // Ambil data
    $data['title'] = "Referensi SNP";
    $data['snp'] = $this->Ref_snp_model->get_paginated($config['per_page'], $offset);

    // Link pagination
    $data['pagination'] = $this->pagination->create_links();

    // Load view
    $this->load->view('templates/sidebar');
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar');
        $this->load->view('referensi_snp/index', $data);
        $this->load->view('templates/footer');
}
    public function add() {
        $data = [
            'kode' => $this->input->post('kode'),
            'snp' => $this->input->post('snp'),
            'komponen' => $this->input->post('komponen'),
            'uraian_kegiatan' => $this->input->post('uraian_kegiatan')
        ];

        $this->Ref_snp_model->insert($data);
        redirect('ref_snp');
    }

    public function edit($id) {
        $data['title'] = "Edit Referensi SNP";
        $data['snp'] = $this->Ref_snp_model->get_by_id($id);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('referensi_snp/edit', $data);
        $this->load->view('templates/footer');
    }

    public function update($id) {
        $data = [
            'kode' => $this->input->post('kode'),
            'snp' => $this->input->post('snp'),
            'komponen' => $this->input->post('komponen'),
            'uraian_kegiatan' => $this->input->post('uraian_kegiatan')
        ];

        $this->Ref_snp_model->update($id, $data);
        redirect('ref_snp');
    }

    public function delete($id) {
        $this->Ref_snp_model->delete($id);
        redirect('ref_snp');
    }
    public function export_excel()
{
    ini_set('display_errors', 0);
    error_reporting(0);

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Referensi SNP');

    // Header
    $sheet->setCellValue('A1', 'Kode');
    $sheet->setCellValue('B1', 'SNP');
    $sheet->setCellValue('C1', 'Komponen');
    $sheet->setCellValue('D1', 'Uraian Kegiatan');

    // Data
    $data = $this->Ref_snp_model->get_all();
    $row = 2;

    foreach ($data as $d) {
        $sheet->setCellValueExplicit('A'.$row, $d->kode, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('B'.$row, $d->snp);
        $sheet->setCellValue('C'.$row, $d->komponen);
        $sheet->setCellValue('D'.$row, $d->uraian_kegiatan);
        $row++;
    }

    foreach (range('A','D') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Bersihkan output buffer
    while (ob_get_level() > 0) {
        ob_end_clean();
    }

    $filename = "Referensi_SNP_" . date('YmdHis') . ".xlsx";

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    header('Cache-Control: max-age=0');
    header('Pragma: public');
    header('Expires: 0');

    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save('php://output');
    exit;
}

public function import_excel()
{
    if (empty($_FILES['file_excel']['tmp_name'])) {
        $this->session->set_flashdata('error', 'File Excel tidak ditemukan.');
        redirect('ref_snp');
    }

    $file = $_FILES['file_excel']['tmp_name'];

    try {
        $spreadsheet = IOFactory::load($file);
    } catch (\Throwable $e) {
        $this->session->set_flashdata('error', 'Gagal membaca file Excel.');
        redirect('ref_snp');
    }

    $sheet = $spreadsheet->getActiveSheet();
    $highestRow = $sheet->getHighestRow();

    $data_insert = [];
    $kode_in_file = [];
    $skip_in_file = [];

    for ($row = 2; $row <= $highestRow; $row++) {

        $kode     = trim((string) $sheet->getCell('A'.$row)->getValue());
        $snp      = trim((string) $sheet->getCell('B'.$row)->getValue());
        $komponen = trim((string) $sheet->getCell('C'.$row)->getValue());
        $uraian   = trim((string) $sheet->getCell('D'.$row)->getValue());

        if ($kode === '') continue;

        // Cegah Excel ubah ke numeric
        if (is_numeric($kode)) {
            $kode = (string) $kode;
        }

        if (in_array($kode, $kode_in_file)) {
            $skip_in_file[] = $kode;
            continue;
        }

        $kode_in_file[] = $kode;

        $data_insert[] = [
            'kode' => $kode,
            'snp' => $snp,
            'komponen' => $komponen,
            'uraian_kegiatan' => $uraian
        ];
    }

    // Cek duplikat DB
    $existing = $this->Ref_snp_model->get_existing_codes($kode_in_file);

    $valid_data = [];
    $skip_existing = [];

    foreach ($data_insert as $row) {
        if (in_array($row['kode'], $existing)) {
            $skip_existing[] = $row['kode'];
        } else {
            $valid_data[] = $row;
        }
    }

    if (!empty($valid_data)) {
        $this->Ref_snp_model->insert_batch($valid_data);
    }

    // Pesan hasil
    $msg = "";

    $msg .= !empty($valid_data)
        ? count($valid_data)." data berhasil diimport.<br>"
        : "Tidak ada data baru yang diimport.<br>";

    if (!empty($skip_in_file)) {
        $msg .= "Lewati duplikat di file: ".implode(', ', array_unique($skip_in_file))."<br>";
    }

    if (!empty($skip_existing)) {
        $msg .= "Lewati yang sudah ada di database: ".implode(', ', array_unique($skip_existing));
    }

    $this->session->set_flashdata('success', $msg);
    redirect('ref_snp');
}



}
