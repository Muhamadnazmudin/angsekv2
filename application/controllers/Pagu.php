<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pagu extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Pagu_model');
        $this->load->library('session');

        // Cegah jurusan
        if ($this->session->userdata('role_id') == 3) {
            redirect('dashboard');
        }
    }

    public function index()
    {
        $data['pagu'] = $this->Pagu_model->get_all();

        $this->load->view('templates/header');
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('pagu/index', $data);
        $this->load->view('templates/footer');
    }

    public function tambah()
    {
        if ($this->input->post()) {

            $tahun   = $this->input->post('tahun');
            $nominal = str_replace('.', '', $this->input->post('nominal'));

            if ($this->Pagu_model->get_by_tahun($tahun)) {
                $this->session->set_flashdata('error', 'Tahun anggaran sudah ada');
                redirect('pagu');
            }

            $this->Pagu_model->insert([
                'tahun' => $tahun,
                'nominal' => $nominal
            ]);

            $this->session->set_flashdata('success', 'Pagu anggaran berhasil ditambahkan');
            redirect('pagu');
        }
    }

    public function edit($id)
    {
        if ($this->input->post()) {

            $nominal = str_replace('.', '', $this->input->post('nominal'));

            $this->Pagu_model->update($id, [
                'nominal' => $nominal
            ]);

            $this->session->set_flashdata('success', 'Pagu anggaran berhasil diperbarui');
            redirect('pagu');
        }
    }

    public function hapus($id)
    {
        $this->Pagu_model->delete($id);
        $this->session->set_flashdata('success', 'Pagu anggaran berhasil dihapus');
        redirect('pagu');
    }
}
