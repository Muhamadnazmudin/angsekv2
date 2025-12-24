<div class="container-fluid">

    <!-- TITLE -->
    <h1 class="h3 mb-4 text-gray-800">Dashboard Angsek</h1>

    <!-- ===================== TOTAL RENCANA ===================== -->
<div class="row mb-4">

<?php if ($this->session->userdata('role_id') != 3): ?>
    <!-- ================= ADMIN / OPERATOR ================= -->

    <div class="col-md-3 mb-3">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                    Pagu Anggaran
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                    Rp <?= number_format($pagu_anggaran,0,",",".") ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card border-left-dark shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                    Total Rencana Anggaran
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                    Rp <?= number_format($total_rencana,0,",",".") ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                    Belum Dianggarkan
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                    Rp <?= number_format($belum_dianggarkan,0,",",".") ?>
                </div>
            </div>
        </div>
    </div>

<?php else: ?>
    <!-- ================= JURUSAN ================= -->

    <div class="col-md-3 mb-3">
        <div class="card border-left-dark shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                    Total Rencana Anggaran
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                    Rp <?= number_format($total_rencana,0,",",".") ?>
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>

</div>



    <!-- ===================== TAHAP ===================== -->
    <h4 class="mb-3 text-dark">Rencana Anggaran Berdasarkan Tahap</h4>

    <div class="row">

        <!-- Tahap 1 -->
        <div class="col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                        Tahap 1 (Jan–Jun)
                    </div>
                    <div class="h4 mb-0 font-weight-bold text-gray-800">
                        Rp <?= number_format($tahap1,0,',','.'); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tahap 2 -->
        <div class="col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                        Tahap 2 (Jul–Des)
                    </div>
                    <div class="h4 mb-0 font-weight-bold text-gray-800">
                        Rp <?= number_format($tahap2,0,',','.'); ?>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <!-- ===================== TRIWULAN ===================== -->
    <h4 class="mb-3 text-dark">Rencana Anggaran Berdasarkan Triwulan</h4>

    <div class="row">

        <!-- TW1 -->
        <div class="col-md-3 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Triwulan 1</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">Rp <?= number_format($tw1,0,',','.'); ?></div>
                </div>
            </div>
        </div>

        <!-- TW2 -->
        <div class="col-md-3 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Triwulan 2</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">Rp <?= number_format($tw2,0,',','.'); ?></div>
                </div>
            </div>
        </div>

        <!-- TW3 -->
        <div class="col-md-3 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Triwulan 3</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">Rp <?= number_format($tw3,0,',','.'); ?></div>
                </div>
            </div>
        </div>

        <!-- TW4 -->
        <div class="col-md-3 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Triwulan 4</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">Rp <?= number_format($tw4,0,',','.'); ?></div>
                </div>
            </div>
        </div>

    </div>


    <!-- ===================== BULANAN ===================== -->
    <h4 class="mb-3 text-dark">Rencana Penggunaan Anggaran Per Bulan</h4>

    <div class="card shadow mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">

                <table class="table table-bordered table-striped mb-0 text-center">
                    <thead class="thead-light">
                        <tr>
                            <th>Jan</th><th>Feb</th><th>Mar</th>
                            <th>Apr</th><th>Mei</th><th>Jun</th>
                            <th>Jul</th><th>Agu</th><th>Sep</th>
                            <th>Okt</th><th>Nov</th><th>Des</th>
                            <th>Total</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td class="text-right"><?= number_format($bulan->jan,0,",",".") ?></td>
                            <td class="text-right"><?= number_format($bulan->feb,0,",",".") ?></td>
                            <td class="text-right"><?= number_format($bulan->mar,0,",",".") ?></td>
                            <td class="text-right"><?= number_format($bulan->apr,0,",",".") ?></td>
                            <td class="text-right"><?= number_format($bulan->mei,0,",",".") ?></td>
                            <td class="text-right"><?= number_format($bulan->jun,0,",",".") ?></td>
                            <td class="text-right"><?= number_format($bulan->jul,0,",",".") ?></td>
                            <td class="text-right"><?= number_format($bulan->agu,0,",",".") ?></td>
                            <td class="text-right"><?= number_format($bulan->sep,0,",",".") ?></td>
                            <td class="text-right"><?= number_format($bulan->okt,0,",",".") ?></td>
                            <td class="text-right"><?= number_format($bulan->nov,0,",",".") ?></td>
                            <td class="text-right"><?= number_format($bulan->des,0,",",".") ?></td>
                            <td class="text-right font-weight-bold">
                                <?= number_format(
                                    $bulan->jan + $bulan->feb + $bulan->mar + $bulan->apr + $bulan->mei +
                                    $bulan->jun + $bulan->jul + $bulan->agu + $bulan->sep + $bulan->okt +
                                    $bulan->nov + $bulan->des, 0, ",", "."
                                ) ?>
                            </td>
                        </tr>
                    </tbody>

                </table>

            </div>
        </div>
    </div>


    <!-- ===================== REKAP JURUSAN ===================== -->
    <h4 class="mt-4 mb-3 text-dark">Perencanaan Anggaran Tiap Jurusan</h4>

    <div class="card shadow mb-4">
        <div class="card-body table-responsive">

            <table class="table table-bordered table-striped">
                <thead class="thead-light">
                    <tr class="text-center">
                        <th width="50">No</th>
                        <th>Jurusan</th>
                        <th>Tahap 1</th>
                        <th>Tahap 2</th>
                        <th>Total</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $no=1; foreach ($rekap_jurusan as $r): ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td><?= $r->jurusan ?></td>
                            <td class="text-right"><?= number_format($r->tahap1,0,",",".") ?></td>
                            <td class="text-right"><?= number_format($r->tahap2,0,",",".") ?></td>
                            <td class="text-right font-weight-bold"><?= number_format($r->tahap1 + $r->tahap2,0,",",".") ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                            
            </table>

        </div>
    </div>

</div>
    <!-- ===================== PERENCANAAN BERDASARKAN SNP ===================== -->
<h4 class="mt-4 mb-3 text-dark">Perencanaan Berdasarkan Standar Nasional Pendidikan (SNP)</h4>

<div class="card shadow mb-4">
    <div class="card-body table-responsive">

        <table class="table table-bordered table-striped mb-0">
            <thead class="thead-light">
                <tr class="text-center">
                    <th width="50">No</th>
                    <th>Standar Nasional Pendidikan</th>
                    <th width="200">Tahap 1</th>
                    <th width="200">Tahap 2</th>
                    <th width="200">Total</th>
                </tr>
            </thead>

            <tbody>
                <?php 
                $no = 1;
                foreach ($perencanaan_snp as $s): 
                    $total_snp = $s->tahap1 + $s->tahap2;
                ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td><?= $s->snp ?></td>
                    <td class="text-right"><?= number_format($s->tahap1,0,',','.') ?></td>
                    <td class="text-right"><?= number_format($s->tahap2,0,',','.') ?></td>
                    <td class="text-right font-weight-bold"><?= number_format($total_snp,0,',','.') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>

            <tfoot>
                <tr class="font-weight-bold" style="background:#f5f5f5;">
                    <td colspan="4" class="text-right">TOTAL BESAR</td>
                    <td class="text-right">
                        <?= number_format($snp_grand_total, 0, ',', '.') ?>
                    </td>
                </tr>
            </tfoot>

        </table>

    </div>
</div>
<?php if ($this->session->userdata('role_id') != 3): ?>
<script src="<?= base_url('assets/sbadmin2/vendor/chart.js/Chart.min.js') ?>"></script>

<div class="row mt-5">

    <!-- ===================== DIAGRAM 1 ===================== -->
    <div class="col-md-6 mb-4">
        <h5 class="text-dark mb-3">
            Proporsi Jenis Belanja dari Total Pagu
        </h5>

        <div class="card shadow h-100">
            <div class="card-body">
                <canvas id="donutBelanja" height="180"></canvas>
            </div>
        </div>
    </div>

    <!-- ===================== DIAGRAM 2 ===================== -->
    <div class="col-md-6 mb-4">
        <h5 class="text-dark mb-1">
            Proporsi Rencana Penyediaan Buku
        </h5><br>

        <!-- <p class="text-muted mb-3">
            Total Rencana Buku:
            <strong>Rp <?= number_format($total_buku,0,',','.') ?></strong>
            (<?= $persen_buku ?>% dari Total Rencana)
        </p> -->

        <div class="card shadow h-100">
            <div class="card-body">
                <canvas id="donutBukuSimple" height="180"></canvas>
            </div>
        </div>
    </div>

</div>
<?php endif; ?>
<script>
// ===================== DIAGRAM JENIS BELANJA =====================
const nilaiBelanja = [
    <?= $diagram['honorarium'] ?>,
    <?= $diagram['barang'] ?>,
    <?= $diagram['jasa'] ?>,
    <?= $diagram['pemeliharaan'] ?>,
    <?= $diagram['perjalanan_dinas'] ?>,
    <?= $diagram['modal_alat_mesin'] ?>,
    <?= $diagram['modal_aset_lainnya'] ?>
];

const labelBelanja = [
    'Honorarium',
    'Barang',
    'Jasa',
    'Pemeliharaan',
    'Perjalanan Dinas',
    'Modal Alat & Mesin',
    'Modal Aset Tetap Lainnya'
];

const totalBelanja = nilaiBelanja.reduce((a, b) => a + b, 0);

// Buat label + persentase
const labelDenganPersen = labelBelanja.map((label, i) => {
    let persen = totalBelanja > 0
        ? ((nilaiBelanja[i] / totalBelanja) * 100).toFixed(2)
        : 0;
    return `${label} (${persen}%)`;
});

const dataBelanja = {
    labels: labelDenganPersen,
    datasets: [{
        data: nilaiBelanja,
        backgroundColor: [
            '#9e9e9e',
            '#81c784',
            '#ffb74d',
            '#e57373',
            '#fff176',
            '#f48fb1',
            '#90caf9'
        ]
    }]
};


new Chart(document.getElementById('donutBelanja'), {
    type: 'doughnut',
    data: dataBelanja,
    options: {
        cutoutPercentage: 65,
        legend: { position: 'bottom' },
        tooltips: {
            callbacks: {
                label: function(tooltipItem, data) {
                    let label = data.labels[tooltipItem.index] || '';
                    let value = data.datasets[0].data[tooltipItem.index] || 0;
                    return label + ': Rp ' + value.toLocaleString('id-ID');
                }
            }
        }
    }
});

// ===================== DIAGRAM PENYEDIAAN BUKU =====================
new Chart(document.getElementById('donutBukuSimple'), {
    type: 'doughnut',
    data: {
        labels: [
            'Penyediaan Buku (<?= $persen_buku ?>%)',
            'Komponen Anggaran Lainnya'
        ],
        datasets: [{
            data: [
                <?= $diagram_buku_simple['buku'] ?>,
                <?= $diagram_buku_simple['lainnya'] ?>
            ],
            backgroundColor: [
                '#6a1b9a',
                '#90b9ff'
            ]
        }]
    },
    options: {
        cutoutPercentage: 65,
        legend: { position: 'right' },
        tooltips: {
            callbacks: {
                label: function(tooltipItem, data) {
                    let label = data.labels[tooltipItem.index];
                    let value = data.datasets[0].data[tooltipItem.index];
                    return label + ': Rp ' + value.toLocaleString('id-ID');
                }
            }
        }
    }
});
</script>


