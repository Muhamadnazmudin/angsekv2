<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Anggaran_model');
        $this->load->model('Jurusan_model');
        $this->load->database();
    }

    public function index()
    {
        $role = $this->session->userdata('role_id');
        $jurusan_id = $this->session->userdata('jurusan_id');


        // ============================================================
        // ====================== JIKA ROLE JURUSAN ====================
        // ============================================================
        if ($role == 3) {

            $data['total_anggaran'] = $this->Anggaran_model->get_total_anggaran($jurusan_id);
            $data['tahap1'] = $this->Anggaran_model->get_tahap1($jurusan_id);
            $data['tahap2'] = $this->Anggaran_model->get_tahap2($jurusan_id);

            $data['tw1'] = $this->Anggaran_model->get_triwulan(1, $jurusan_id);
            $data['tw2'] = $this->Anggaran_model->get_triwulan(2, $jurusan_id);
            $data['tw3'] = $this->Anggaran_model->get_triwulan(3, $jurusan_id);
            $data['tw4'] = $this->Anggaran_model->get_triwulan(4, $jurusan_id);

            $data['bulan'] = $this->Anggaran_model->get_total_per_bulan($jurusan_id);

            $data['total_rencana'] =
                $data['bulan']->jan + $data['bulan']->feb + $data['bulan']->mar +
                $data['bulan']->apr + $data['bulan']->mei + $data['bulan']->jun +
                $data['bulan']->jul + $data['bulan']->agu + $data['bulan']->sep +
                $data['bulan']->okt + $data['bulan']->nov + $data['bulan']->des;

            $data['rekap_jurusan'] = $this->Anggaran_model->get_rekap_jurusan($jurusan_id);

            // Role jurusan tidak tampilkan SNP
            $data['perencanaan_snp'] = [];
            $data['total_jurusan'] = 1;
        }


        // ============================================================
        // ================= ADMIN & OPERATOR ===========================
        // ============================================================
        else {

            $data['total_jurusan'] = $this->db->count_all('jurusan');
            $data['total_anggaran'] = $this->Anggaran_model->get_total_anggaran();

            $data['tahap1'] = $this->Anggaran_model->get_tahap1();
            $data['tahap2'] = $this->Anggaran_model->get_tahap2();

            $data['tw1'] = $this->Anggaran_model->get_triwulan(1);
            $data['tw2'] = $this->Anggaran_model->get_triwulan(2);
            $data['tw3'] = $this->Anggaran_model->get_triwulan(3);
            $data['tw4'] = $this->Anggaran_model->get_triwulan(4);

            $data['bulan'] = $this->Anggaran_model->get_total_per_bulan();

            $data['total_rencana'] =
                $data['bulan']->jan + $data['bulan']->feb + $data['bulan']->mar +
                $data['bulan']->apr + $data['bulan']->mei + $data['bulan']->jun +
                $data['bulan']->jul + $data['bulan']->agu + $data['bulan']->sep +
                $data['bulan']->okt + $data['bulan']->nov + $data['bulan']->des;

            $data['rekap_jurusan'] = $this->Anggaran_model->get_rekap_jurusan();


            // ===========================================================
            // 1. LIST 7 SNP NASIONAL (FIX)
            // ===========================================================
            $snp_list = [
                "Standar Isi" => ["isi"],
                "Standar Proses" => ["proses"],
                "Standar Pendidik dan Tenaga Kependidikan" => ["pendidik", "ptk"],
                "Standar Sarana dan Prasarana" => ["sarana", "prasara"],
                "Standar Pengelolaan" => ["pengelolaan"],
                "Standar Pembiayaan" => ["biaya", "pembiayaan"],
                "Standar Penilaian" => ["nilai", "penilaian"]
            ];


            // ===========================================================
            // 2. PASTIKAN TABEL HANYA ADA 7 BARIS
            // ===========================================================
            foreach ($snp_list as $nama_snp => $keys) {
                $cek = $this->db->get_where('perencanaan_snp', ['snp' => $nama_snp])->row();
                if (!$cek) {
                    $this->db->insert('perencanaan_snp', [
                        'snp' => $nama_snp,
                        'tahap1' => 0,
                        'tahap2' => 0
                    ]);
                }
            }


            // ===========================================================
            // 3. HITUNG NILAI ITEM_ANGGARAN → PER SNP (LIKE)
            // ===========================================================
            foreach ($snp_list as $nama_snp => $keywords) {

                // Cari semua ref_snp.id yang cocok berdasarkan keyword
                $this->db->select('id');
                $this->db->from('ref_snp');

                $this->db->group_start();
                foreach ($keywords as $k) {
                    $this->db->or_like('snp', $k);
                }
                $this->db->group_end();

                $ids = $this->db->get()->result_array();
                $ref_ids = empty($ids) ? [0] : array_column($ids, 'id');


                // Tahap 1 (Jan-Juni)
                $this->db->select('SUM(jan+feb+mar+apr+mei+jun) AS t1');
                $this->db->where_in('ref_snp_id', $ref_ids);
                $t1 = $this->db->get('item_anggaran')->row()->t1;

                // Tahap 2 (Jul-Des)
                $this->db->select('SUM(jul+agu+sep+okt+nov+des) AS t2');
                $this->db->where_in('ref_snp_id', $ref_ids);
                $t2 = $this->db->get('item_anggaran')->row()->t2;

                // Update tabel perencanaan_snp
                $this->db->where('snp', $nama_snp)->update('perencanaan_snp', [
                    'tahap1' => $t1 ? $t1 : 0,
                    'tahap2' => $t2 ? $t2 : 0
                ]);
            }


            // ===========================================================
            // 4. AMBIL DATA UNTUK DASHBOARD (HANYA 7 BARIS)
            // ===========================================================
            $order = "'Standar Isi',
                      'Standar Proses',
                      'Standar Pendidik dan Tenaga Kependidikan',
                      'Standar Sarana dan Prasarana',
                      'Standar Pengelolaan',
                      'Standar Pembiayaan',
                      'Standar Penilaian'";

            $data['perencanaan_snp'] = $this->db
                ->order_by("FIELD(snp, $order)", NULL, false)
                ->get('perencanaan_snp')
                ->result();
        }

        $sum_total = 0;
foreach ($data['perencanaan_snp'] as $p) {
    $sum_total += ($p->tahap1 + $p->tahap2);
}
$data['snp_grand_total'] = $sum_total;


        // ===========================================================
        //                LOAD TEMPLATE DASHBOARD
        // ===========================================================
        $this->load->view('templates/header');
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('dashboard', $data);
        $this->load->view('templates/footer');
    }
}
