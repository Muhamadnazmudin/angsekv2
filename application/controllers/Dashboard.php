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

    // =====================================================
    //          PERHITUNGAN SNP KHUSUS JURUSAN
    // =====================================================
    $snp_list = [
        "Standar Isi" => ["isi"],
        "Standar Proses" => ["proses"],
        "Standar Pendidik dan Tenaga Kependidikan" => ["pendidik","ptk"],
        "Standar Sarana dan Prasarana" => ["sarana","prasarana"],
        "Standar Pengelolaan" => ["pengelolaan"],
        "Standar Pembiayaan" => ["biaya","pembiayaan"],
        "Standar Penilaian" => ["nilai","penilaian"]
    ];

    $hasil_snp = [];

    foreach ($snp_list as $nama_snp => $keywords) {

        // ambil ref_snp.id berdasarkan keyword
        $this->db->select('id');
        $this->db->from('ref_snp');
        $this->db->group_start();
        foreach ($keywords as $k) $this->db->or_like('snp', $k);
        $this->db->group_end();
        $ids = $this->db->get()->result_array();
        $ref_ids = empty($ids) ? [0] : array_column($ids, 'id');

        // Tahap 1 khusus jurusan
        $this->db->select('SUM(jan+feb+mar+apr+mei+jun) AS t1');
        $this->db->where_in('ref_snp_id', $ref_ids);
        $this->db->where('jurusan_id', $jurusan_id);
        $t1 = $this->db->get('item_anggaran')->row()->t1;

        // Tahap 2 khusus jurusan
        $this->db->select('SUM(jul+agu+sep+okt+nov+des) AS t2');
        $this->db->where_in('ref_snp_id', $ref_ids);
        $this->db->where('jurusan_id', $jurusan_id);
        $t2 = $this->db->get('item_anggaran')->row()->t2;

        $hasil_snp[] = (object)[
            'snp' => $nama_snp,
            'tahap1' => $t1 ?: 0,
            'tahap2' => $t2 ?: 0
        ];
    }

    $data['perencanaan_snp'] = $hasil_snp;

    // Hitung total besar
    $sum_total = 0;
    foreach ($hasil_snp as $p) {
        $sum_total += ($p->tahap1 + $p->tahap2);
    }
    $data['snp_grand_total'] = $sum_total;

}
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
// PAGU ANGGARAN & BELUM DIANGGARKAN
// ===========================================================
$pagu = $this->db
    ->order_by('tahun', 'DESC')
    ->limit(1)
    ->get('pagu_anggaran')
    ->row();

$data['pagu_anggaran'] = $pagu ? (int)$pagu->nominal : 0;
$data['belum_dianggarkan'] = $data['pagu_anggaran'] - $data['total_rencana'];
$pagu_anggaran = $data['pagu_anggaran'];


// ===========================================================
// DATA DIAGRAM JENIS BELANJA (FINAL - JOIN kategori_kodering)
// ===========================================================

// BARANG → Bahan Habis Pakai (id = 1)
$data['diagram']['barang'] = (int) $this->db
    ->select_sum('ia.total')
    ->from('item_anggaran ia')
    ->join('kodering k', 'k.id = ia.kodering_id')
    ->join('kategori_kodering kk', 'kk.id = k.kategori_id')
    ->where('kk.id', 1)
    ->get()->row()->total;


// JASA → Jasa (id = 3)
$data['diagram']['jasa'] = (int) $this->db
    ->select_sum('ia.total')
    ->from('item_anggaran ia')
    ->join('kodering k', 'k.id = ia.kodering_id')
    ->join('kategori_kodering kk', 'kk.id = k.kategori_id')
    ->where('kk.id', 3)
    ->get()->row()->total;


// MODAL ALAT DAN MESIN (id = 2)
$data['diagram']['modal_alat_mesin'] = (int) $this->db
    ->select_sum('ia.total')
    ->from('item_anggaran ia')
    ->join('kodering k', 'k.id = ia.kodering_id')
    ->join('kategori_kodering kk', 'kk.id = k.kategori_id')
    ->where('kk.id', 2)
    ->get()->row()->total;


// MODAL ASSET TETAP (id = 4)
$data['diagram']['modal_aset_lainnya'] = (int) $this->db
    ->select_sum('ia.total')
    ->from('item_anggaran ia')
    ->join('kodering k', 'k.id = ia.kodering_id')
    ->join('kategori_kodering kk', 'kk.id = k.kategori_id')
    ->where('kk.id', 4)
    ->get()->row()->total;


// ===========================================================
// PEMELIHARAAN → berdasarkan ref_snp.komponen
// ===========================================================
$data['diagram']['pemeliharaan'] = (int) $this->db
    ->select_sum('ia.total')
    ->from('item_anggaran ia')
    ->join('ref_snp rs', 'rs.id = ia.ref_snp_id')
    ->like('rs.komponen', 'pemeliharaan')
    ->get()
    ->row()
    ->total;



// PERJALANAN DINAS → berdasarkan nama kodering
$data['diagram']['perjalanan_dinas'] = (int) $this->db
    ->select_sum('ia.total')
    ->from('item_anggaran ia')
    ->join('kodering k', 'k.id = ia.kodering_id')
    ->like('k.nama', 'Perjalanan')
    ->get()->row()->total;


// HONORARIUM → dipaksa 0 (sesuai kebijakan)
$data['diagram']['honorarium'] = 0;

// ===========================================================
// PROPORSI PENYEDIAAN BUKU (SIMPLE)
// ===========================================================

// TOTAL BUKU (gabungan kodering buku + kegiatan pengadaan buku)
$total_buku = $this->db
    ->select_sum('ia.total')
    ->from('item_anggaran ia')
    ->join('kodering k', 'k.id = ia.kodering_id')
    ->join('kegiatan kg', 'kg.id = ia.kegiatan_id', 'left')
    ->group_start()
        ->like('k.nama', 'Belanja Modal Buku')
        ->or_like('kg.nama', 'Pengadaan Buku')
    ->group_end()
    ->get()->row()->total;

$total_buku = (int) $total_buku;

// KOMPONEN LAINNYA
$komponen_lainnya = max(0, $data['total_rencana'] - $total_buku);

$data['diagram_buku_simple'] = [
    'buku' => $total_buku,
    'lainnya' => $komponen_lainnya
];

$data['total_buku'] = $total_buku;
$data['persen_buku'] = ($data['total_rencana'] > 0)
    ? round(($total_buku / $data['total_rencana']) * 100, 2)
    : 0;



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
