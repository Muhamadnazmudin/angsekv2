<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Upload extends CI_Controller
{
   public function __construct()
{
    parent::__construct();

    $this->load->model('Upload_model');
    $this->load->helper(array(
        'url',
        'file',
        'download'
    ));
    $this->load->library('session');

    $role = $this->session->userdata('role_id');

    // Role 1 = Admin
    // Role 2 = Operator
    // Role 3 = Jurusan
    if (!in_array($role, array(1, 2))) {
        show_error(
            'Anda tidak memiliki akses ke halaman ini.',
            403,
            'Akses Ditolak'
        );
    }
}
    public function index()
    {
        $keyword = trim($this->input->get('q', true));
        $tahun   = (int) $this->input->get('tahun');

        if (!in_array($tahun, array(2025, 2026))) {
            $tahun = 2025;
        }

        $points = $this->Upload_model->get_points($keyword);

        foreach ($points as &$point) {
            $point->files_2025 = $this->Upload_model->get_files_by_point(
                $point->id,
                2025
            );

            $point->files_2026 = $this->Upload_model->get_files_by_point(
                $point->id,
                2026
            );
        }

        $data['title']   = 'Upload Berkas Inspektorat';
        $data['points']  = $points;
        $data['keyword'] = $keyword;
        $data['tahun']   = $tahun;
        $data['stats']   = $this->Upload_model->get_stats();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('upload/index', $data);
        $this->load->view('templates/footer');
    }

    public function upload_file($point_id)
{
    $point = $this->Upload_model->get_point($point_id);

    if (!$point) {
        $this->session->set_flashdata(
            'error',
            'Point dokumen tidak ditemukan.'
        );

        redirect('upload');
    }

    $tahun = (int) $this->input->post('tahun');

    if (!in_array($tahun, array(2025, 2026))) {

        $this->session->set_flashdata(
            'error',
            'Tahun dokumen tidak valid.'
        );

        redirect('upload');
    }

    if (!isset($_FILES['berkas'])) {

        $this->session->set_flashdata(
            'error',
            'Tidak ada file yang diterima server.'
        );

        redirect('upload');
    }

    if ($_FILES['berkas']['error'] !== UPLOAD_ERR_OK) {

        $upload_errors = array(
            UPLOAD_ERR_INI_SIZE   => 'Ukuran file melebihi batas upload server.',
            UPLOAD_ERR_FORM_SIZE  => 'Ukuran file melebihi batas form.',
            UPLOAD_ERR_PARTIAL    => 'File hanya terupload sebagian.',
            UPLOAD_ERR_NO_FILE    => 'Tidak ada file yang dipilih.',
            UPLOAD_ERR_NO_TMP_DIR => 'Folder temporary server tidak tersedia.',
            UPLOAD_ERR_CANT_WRITE => 'Server tidak dapat menulis file.',
            UPLOAD_ERR_EXTENSION  => 'Upload dihentikan oleh ekstensi PHP.'
        );

        $error_code = $_FILES['berkas']['error'];

        $message = isset($upload_errors[$error_code])
            ? $upload_errors[$error_code]
            : 'Terjadi kesalahan saat upload file.';

        $this->session->set_flashdata(
            'error',
            $message
        );

        redirect('upload');
    }

    $upload_path = FCPATH . 'uploads/inspektorat/' . $tahun . '/';

    if (!is_dir($upload_path)) {

        if (!mkdir($upload_path, 0755, true)) {

            $this->session->set_flashdata(
                'error',
                'Folder upload tidak dapat dibuat.'
            );

            redirect('upload');
        }
    }

    $config = array(
        'upload_path'      => $upload_path,
        'allowed_types'    => 'pdf|doc|docx|xls|xlsx|ppt|pptx|jpg|jpeg|png|gif|zip|rar',
        'max_size'         => 51200,
        'encrypt_name'     => true,
        'remove_spaces'    => true,
        'detect_mime'      => true,
        'mod_mime_fix'     => true
    );

    $this->load->library('upload');
    $this->upload->initialize($config);

    if (!$this->upload->do_upload('berkas')) {

        $error = strip_tags(
            $this->upload->display_errors('', '')
        );

        if ($error === '') {
            $error = 'File gagal diupload.';
        }

        $this->session->set_flashdata(
            'error',
            $error
        );

        redirect('upload');
    }

    $upload = $this->upload->data();

    $data = array(
        'point_id'       => $point_id,
        'tahun'          => $tahun,
        'nama_file'      => $upload['file_name'],
        'nama_file_asli' => $upload['orig_name'],
        'ekstensi'       => strtolower(
            ltrim($upload['file_ext'], '.')
        ),
        'tipe_file'      => $upload['file_type'],
        'ukuran_file'    => (int) $upload['file_size'] * 1024,
        'lokasi_file'    => 'uploads/inspektorat/'
                            . $tahun . '/'
                            . $upload['file_name'],
        'keterangan'     => $this->input->post(
            'keterangan',
            true
        ),
        'uploaded_by'    => $this->session->userdata('user_id'),
        'uploaded_at'    => date('Y-m-d H:i:s')
    );

    if (!$this->Upload_model->insert_file($data)) {

        if (file_exists($upload_path . $upload['file_name'])) {
            unlink($upload_path . $upload['file_name']);
        }

        $this->session->set_flashdata(
            'error',
            'File berhasil diupload tetapi gagal disimpan ke database.'
        );

        redirect('upload');
    }

    $this->session->set_flashdata(
        'success',
        'Berkas berhasil diupload: '
        . $upload['orig_name']
    );

    redirect('upload');
}
    public function download($id)
    {
        $file = $this->Upload_model->get_file($id);

        if (!$file) {
            show_404();
        }

        $path = FCPATH . $file->lokasi_file;

        if (!file_exists($path)) {
            show_error('File tidak ditemukan di server.');
        }

        force_download(
            $file->nama_file_asli,
            file_get_contents($path)
        );
    }

    public function delete_file($id)
    {
        $file = $this->Upload_model->get_file($id);

        if (!$file) {
            show_404();
        }

        $path = FCPATH . $file->lokasi_file;

        if (file_exists($path)) {
            unlink($path);
        }

        $this->Upload_model->delete_file($id);

        $this->session->set_flashdata(
            'success',
            'Berkas berhasil dihapus.'
        );

        redirect('upload');
    }

    public function tambah_point()
    {
        $data['title'] = 'Tambah Point Dokumen';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('upload/form', $data);
        $this->load->view('templates/footer');
    }

    public function simpan_point()
    {
        $nomor = (int) $this->input->post('nomor');

        $nama = trim(
            $this->input->post('nama_point', true)
        );

        if ($nomor <= 0 || $nama === '') {

            $this->session->set_flashdata(
                'error',
                'Nomor dan nama point wajib diisi.'
            );

            redirect('upload/tambah_point');
        }

        $data = array(
            'nomor'       => $nomor,
            'nama_point'  => $nama,
            'keterangan'  => $this->input->post('keterangan', true),
            'aktif'       => 1,
            'created_at'  => date('Y-m-d H:i:s')
        );

        if (!$this->Upload_model->insert_point($data)) {

            $this->session->set_flashdata(
                'error',
                'Point gagal ditambahkan.'
            );

            redirect('upload/tambah_point');
        }

        $this->session->set_flashdata(
            'success',
            'Point berhasil ditambahkan.'
        );

        redirect('upload');
    }

    public function edit_point($id)
    {
        $point = $this->Upload_model->get_point($id);

        if (!$point) {
            show_404();
        }

        $data['title'] = 'Edit Point Dokumen';
        $data['point'] = $point;

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('upload/form', $data);
        $this->load->view('templates/footer');
    }

    public function update_point($id)
    {
        $point = $this->Upload_model->get_point($id);

        if (!$point) {
            show_404();
        }

        $nomor = (int) $this->input->post('nomor');

        $nama = trim(
            $this->input->post('nama_point', true)
        );

        if ($nomor <= 0 || $nama === '') {

            $this->session->set_flashdata(
                'error',
                'Nomor dan nama point wajib diisi.'
            );

            redirect('upload/edit_point/' . $id);
        }

        $data = array(
            'nomor'       => $nomor,
            'nama_point'  => $nama,
            'keterangan'  => $this->input->post('keterangan', true),
            'updated_at'  => date('Y-m-d H:i:s')
        );

        $this->Upload_model->update_point($id, $data);

        $this->session->set_flashdata(
            'success',
            'Point berhasil diperbarui.'
        );

        redirect('upload');
    }

    public function delete_point($id)
    {
        $point = $this->Upload_model->get_point($id);

        if (!$point) {
            show_404();
        }

        $this->Upload_model->delete_point($id);

        $this->session->set_flashdata(
            'success',
            'Point dan seluruh berkas di dalamnya berhasil dihapus.'
        );

        redirect('upload');
    }
}