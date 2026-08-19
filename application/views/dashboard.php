<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$role = (int) $this->session->userdata('role_id');
$isAdmin = ($role != 3);

/* =========================================================
   HELPER
========================================================= */

if (!function_exists('dashboard_rupiah')) {
    function dashboard_rupiah($value)
    {
        return 'Rp ' . number_format(
            (float) $value,
            0,
            ',',
            '.'
        );
    }
}

if (!function_exists('dashboard_number')) {
    function dashboard_number($value)
    {
        return number_format(
            (float) $value,
            0,
            ',',
            '.'
        );
    }
}

if (!function_exists('dashboard_percent')) {
    function dashboard_percent($value, $total)
    {
        if ((float) $total <= 0) {
            return 0;
        }

        return round(
            ((float) $value / (float) $total) * 100,
            1
        );
    }
}


/* =========================================================
   NILAI UTAMA
========================================================= */

$pagu_anggaran       = (float) ($pagu_anggaran ?? 0);
$total_rencana       = (float) ($total_rencana ?? 0);
$belum_dianggarkan   = (float) ($belum_dianggarkan ?? 0);

$tahap1 = (float) ($tahap1 ?? 0);
$tahap2 = (float) ($tahap2 ?? 0);

$tw1 = (float) ($tw1 ?? 0);
$tw2 = (float) ($tw2 ?? 0);
$tw3 = (float) ($tw3 ?? 0);
$tw4 = (float) ($tw4 ?? 0);

$persen_rencana = dashboard_percent(
    $total_rencana,
    $pagu_anggaran
);

$persen_belum = dashboard_percent(
    $belum_dianggarkan,
    $pagu_anggaran
);

$total_tahap = $tahap1 + $tahap2;


/* =========================================================
   BULAN
========================================================= */

$jan = (float) ($bulan->jan ?? 0);
$feb = (float) ($bulan->feb ?? 0);
$mar = (float) ($bulan->mar ?? 0);
$apr = (float) ($bulan->apr ?? 0);
$mei = (float) ($bulan->mei ?? 0);
$jun = (float) ($bulan->jun ?? 0);
$jul = (float) ($bulan->jul ?? 0);
$agu = (float) ($bulan->agu ?? 0);
$sep = (float) ($bulan->sep ?? 0);
$okt = (float) ($bulan->okt ?? 0);
$nov = (float) ($bulan->nov ?? 0);
$des = (float) ($bulan->des ?? 0);

$total_bulanan =
    $jan + $feb + $mar + $apr +
    $mei + $jun + $jul + $agu +
    $sep + $okt + $nov + $des;


/* =========================================================
   CHART DATA
========================================================= */

$diagram = isset($diagram) && is_array($diagram)
    ? $diagram
    : array();

$diagram_buku_simple =
    isset($diagram_buku_simple) && is_array($diagram_buku_simple)
        ? $diagram_buku_simple
        : array(
            'buku'    => 0,
            'lainnya' => 0
        );

$persen_buku = (float) ($persen_buku ?? 0);

?>

<div class="container-fluid dashboard-page">

<style>

/* =========================================================
   GLOBAL
========================================================= */

.dashboard-page {
    padding-bottom: 50px;
}

.dashboard-page * {
    box-sizing: border-box;
}


/* =========================================================
   HEADER
========================================================= */

.dashboard-hero {
    position: relative;

    overflow: hidden;

    border-radius: 22px;

    padding: 28px 30px;

    margin-bottom: 24px;

    color: #fff;

    background:
        linear-gradient(
            135deg,
            #6ea8fe 0%,
            #4e73df 52%,
            #224abe 100%
        );

    box-shadow:
        0 10px 30px rgba(78,115,223,.17);
}

.dashboard-hero::before {
    content: "";

    position: absolute;

    width: 260px;
    height: 260px;

    right: -90px;
    top: -150px;

    border-radius: 50%;

    background: rgba(255,255,255,.08);
}

.dashboard-hero::after {
    content: "";

    position: absolute;

    width: 170px;
    height: 170px;

    right: 120px;
    bottom: -125px;

    border-radius: 50%;

    background: rgba(255,255,255,.05);
}

.dashboard-hero-content {
    position: relative;

    z-index: 2;
}

.dashboard-welcome {
    margin: 0 0 5px;

    font-size: .75rem;
    font-weight: 700;

    letter-spacing: .5px;

    text-transform: uppercase;

    color: rgba(255,255,255,.78);
}

.dashboard-title {
    margin: 0;

    font-size: 1.65rem;
    font-weight: 800;

    letter-spacing: -.3px;
}

.dashboard-description {
    margin: 7px 0 0;

    max-width: 700px;

    font-size: .86rem;

    line-height: 1.6;

    color: rgba(255,255,255,.88);
}

.dashboard-date {
    position: relative;

    z-index: 3;

    padding: 9px 14px;

    border-radius: 11px;

    color: #fff;

    background: rgba(255,255,255,.12);

    border: 1px solid rgba(255,255,255,.15);

    font-size: .75rem;
}


/* =========================================================
   SECTION
========================================================= */

.dashboard-section {
    margin-top: 28px;
}

.dashboard-section-header {
    display: flex;

    align-items: center;
    justify-content: space-between;

    gap: 15px;

    margin-bottom: 13px;
}

.dashboard-section-title {
    display: flex;

    align-items: center;

    gap: 9px;

    margin: 0;

    color: #343a40;

    font-size: 1rem;
    font-weight: 800;
}

.dashboard-section-title i {
    color: #4e73df;
}

.dashboard-section-desc {
    margin: 0;

    color: #858796;

    font-size: .72rem;
}


/* =========================================================
   SUMMARY CARD
========================================================= */

.summary-card {
    position: relative;

    height: 100%;

    overflow: hidden;

    border: 0;

    border-radius: 17px;

    background: #fff;

    box-shadow:
        0 4px 18px rgba(0,0,0,.055);

    transition: .2s ease;
}

.summary-card:hover {
    transform: translateY(-2px);

    box-shadow:
        0 8px 25px rgba(0,0,0,.08);
}

.summary-card::before {
    content: "";

    position: absolute;

    left: 0;
    top: 0;
    bottom: 0;

    width: 4px;

    background: #4e73df;
}

.summary-card.dark::before {
    background: #5a5c69;
}

.summary-card.danger::before {
    background: #e74a3b;
}

.summary-card.success::before {
    background: #1cc88a;
}

.summary-card.warning::before {
    background: #f6c23e;
}

.summary-card-body {
    padding: 20px;
}

.summary-top {
    display: flex;

    justify-content: space-between;

    align-items: flex-start;
}

.summary-label {
    color: #858796;

    font-size: .68rem;
    font-weight: 800;

    letter-spacing: .4px;

    text-transform: uppercase;
}

.summary-value {
    margin-top: 5px;

    color: #343a40;

    font-size: 1.18rem;
    font-weight: 800;

    line-height: 1.3;
}

.summary-icon {
    width: 42px;
    height: 42px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 12px;

    color: #4e73df;
    background: #edf2ff;

    font-size: 1rem;
}

.summary-card.danger .summary-icon {
    color: #e74a3b;
    background: #fff0ef;
}

.summary-card.dark .summary-icon {
    color: #5a5c69;
    background: #f1f1f3;
}

.summary-card.success .summary-icon {
    color: #1cc88a;
    background: #e9faf4;
}

.summary-card.warning .summary-icon {
    color: #c99700;
    background: #fff8df;
}

.summary-meta {
    margin-top: 10px;

    color: #9a9cac;

    font-size: .69rem;
}

.summary-progress {
    height: 6px;

    overflow: hidden;

    margin-top: 12px;

    border-radius: 10px;

    background: #eaecf4;
}

.summary-progress-bar {
    height: 100%;

    border-radius: 10px;

    background: #4e73df;
}


/* =========================================================
   TAHAP CARD
========================================================= */

.stage-card {
    height: 100%;

    border: 0;

    border-radius: 17px;

    background: #fff;

    box-shadow:
        0 4px 18px rgba(0,0,0,.05);
}

.stage-body {
    padding: 20px;
}

.stage-head {
    display: flex;

    align-items: center;

    gap: 12px;
}

.stage-icon {
    width: 43px;
    height: 43px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 12px;

    font-size: 1rem;
}

.stage-icon.warning {
    color: #c99700;
    background: #fff8df;
}

.stage-icon.info {
    color: #36b9cc;
    background: #e9f9fc;
}

.stage-title {
    color: #858796;

    font-size: .69rem;
    font-weight: 800;

    text-transform: uppercase;
}

.stage-period {
    margin-top: 2px;

    color: #9a9cac;

    font-size: .68rem;
}

.stage-value {
    margin-top: 17px;

    color: #343a40;

    font-size: 1.25rem;
    font-weight: 800;
}

.stage-share {
    margin-top: 5px;

    color: #858796;

    font-size: .7rem;
}


/* =========================================================
   TRIWULAN
========================================================= */

.quarter-card {
    height: 100%;

    border: 0;

    border-radius: 15px;

    background: #fff;

    box-shadow:
        0 4px 16px rgba(0,0,0,.045);
}

.quarter-body {
    padding: 17px;
}

.quarter-label {
    color: #858796;

    font-size: .68rem;
    font-weight: 800;

    text-transform: uppercase;
}

.quarter-value {
    margin-top: 6px;

    color: #343a40;

    font-size: .98rem;
    font-weight: 800;
}

.quarter-line {
    height: 4px;

    margin-top: 12px;

    border-radius: 10px;

    background: #eaecf4;
}

.quarter-line span {
    display: block;

    height: 100%;

    border-radius: 10px;

    background: #4e73df;
}


/* =========================================================
   MONTHLY TABLE
========================================================= */

.month-card {
    overflow: hidden;

    border: 0;

    border-radius: 17px;

    background: #fff;

    box-shadow:
        0 4px 18px rgba(0,0,0,.05);
}

.month-summary {
    display: flex;

    align-items: center;
    justify-content: space-between;

    padding: 17px 20px;

    border-bottom: 1px solid #edf0f5;
}

.month-summary-label {
    color: #858796;

    font-size: .7rem;
    font-weight: 800;

    text-transform: uppercase;
}

.month-summary-value {
    margin-top: 3px;

    color: #343a40;

    font-size: 1.15rem;
    font-weight: 800;
}

.month-table {
    margin: 0;

    font-size: .73rem;
}

.month-table th {
    padding: 11px 8px;

    color: #6c7080;

    background: #f8f9fc;

    border-top: 0;

    font-size: .67rem;
    font-weight: 800;

    white-space: nowrap;
}

.month-table td {
    padding: 13px 9px;

    color: #5a5c69;

    white-space: nowrap;
}

.month-table .total-column {
    color: #343a40;

    background: #f5f7fb;

    font-weight: 800;
}


/* =========================================================
   DATA TABLE
========================================================= */

.data-card {
    overflow: hidden;

    border: 0;

    border-radius: 17px;

    background: #fff;

    box-shadow:
        0 4px 18px rgba(0,0,0,.05);
}

.data-card-body {
    padding: 0;
}

.dashboard-table {
    margin: 0;

    font-size: .77rem;
}

.dashboard-table thead th {
    padding: 13px 14px;

    color: #6c7080;

    background: #f8f9fc;

    border-top: 0;

    font-size: .68rem;
    font-weight: 800;

    white-space: nowrap;
}

.dashboard-table tbody td {
    padding: 12px 14px;

    vertical-align: middle;

    color: #5a5c69;
}

.dashboard-table tbody tr:hover {
    background: #fafbfe;
}

.dashboard-table tfoot td {
    padding: 13px 14px;

    color: #343a40;

    background: #f5f7fb;

    font-weight: 800;
}

.table-number {
    width: 40px;

    color: #858796 !important;

    text-align: center;
}

.table-name {
    color: #343a40 !important;

    font-weight: 700;
}


/* =========================================================
   CHART
========================================================= */

.chart-card {
    height: 100%;

    border: 0;

    border-radius: 17px;

    background: #fff;

    box-shadow:
        0 4px 18px rgba(0,0,0,.05);
}

.chart-card-header {
    padding: 18px 20px 0;
}

.chart-title {
    margin: 0;

    color: #343a40;

    font-size: .9rem;
    font-weight: 800;
}

.chart-description {
    margin-top: 4px;

    color: #858796;

    font-size: .69rem;
}

.chart-card-body {
    padding: 15px 20px 20px;
}


/* =========================================================
   EMPTY DATA
========================================================= */

.dashboard-empty {
    padding: 35px 20px;

    text-align: center;

    color: #9a9cac;
}

.dashboard-empty i {
    margin-bottom: 10px;

    font-size: 1.7rem;
}

.dashboard-empty p {
    margin: 0;

    font-size: .75rem;
}


/* =========================================================
   DARK MODE
========================================================= */

body.dark-mode .summary-card,
body.dark-mode .stage-card,
body.dark-mode .quarter-card,
body.dark-mode .month-card,
body.dark-mode .data-card,
body.dark-mode .chart-card {
    background: #1f2937;
}

body.dark-mode .dashboard-section-title,
body.dark-mode .summary-value,
body.dark-mode .stage-value,
body.dark-mode .quarter-value,
body.dark-mode .month-summary-value,
body.dark-mode .chart-title,
body.dark-mode .table-name {
    color: #f3f4f6 !important;
}

body.dark-mode .month-table th,
body.dark-mode .dashboard-table thead th {
    color: #d1d5db;

    background: #111827;
}

body.dark-mode .month-table td,
body.dark-mode .dashboard-table tbody td {
    color: #d1d5db;
}

body.dark-mode .month-table .total-column,
body.dark-mode .dashboard-table tfoot td {
    color: #f3f4f6;

    background: #111827;
}

body.dark-mode .dashboard-table tbody tr:hover {
    background: #263244;
}

body.dark-mode .month-summary {
    border-color: #374151;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 991.98px) {

    .dashboard-title {
        font-size: 1.4rem;
    }

    .dashboard-date {
        display: inline-block;

        margin-top: 15px;
    }
}

@media (max-width: 767.98px) {

    .dashboard-page {
        padding-left: 10px;
        padding-right: 10px;
    }

    .dashboard-hero {
        padding: 22px;

        border-radius: 17px;
    }

    .dashboard-title {
        font-size: 1.25rem;
    }

    .dashboard-section {
        margin-top: 23px;
    }

    .dashboard-section-header {
        display: block;
    }

    .dashboard-section-desc {
        margin-top: 4px;
    }

    .month-summary {
        display: block;
    }

    .month-summary-value {
        margin-top: 6px;
    }
}

</style>


<!-- =========================================================
     HEADER
========================================================= -->

<div class="dashboard-hero">

    <div class="dashboard-hero-content">

        <div class="row align-items-center">

            <div class="col-lg-9">

                <div class="dashboard-welcome">
                    Sistem Administrasi Anggaran Sekolah
                </div>

                <h1 class="dashboard-title">
                    Dashboard Angsek
                </h1>

                <p class="dashboard-description">

                    Ringkasan perencanaan anggaran,
                    tahapan pencairan, penggunaan bulanan,
                    jurusan, dan Standar Nasional Pendidikan.

                </p>

            </div>

            <div class="col-lg-3 text-lg-right">

                <div class="dashboard-date">

                    <i class="far fa-calendar-alt mr-1"></i>

                    <?= date('d F Y') ?>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- =========================================================
     RINGKASAN UTAMA
========================================================= -->

<div class="dashboard-section mt-0">

    <div class="dashboard-section-header">

        <div>

            <h2 class="dashboard-section-title">

                <i class="fas fa-chart-line"></i>

                Ringkasan Anggaran

            </h2>

            <p class="dashboard-section-desc">
                Gambaran kondisi perencanaan anggaran saat ini.
            </p>

        </div>

    </div>


    <div class="row">

        <?php if ($isAdmin): ?>

            <!-- PAGU -->

            <div class="col-xl-4 col-md-6 mb-3">

                <div class="summary-card">

                    <div class="summary-card-body">

                        <div class="summary-top">

                            <div>

                                <div class="summary-label">
                                    Pagu Anggaran
                                </div>

                                <div class="summary-value">
                                    <?= dashboard_rupiah($pagu_anggaran) ?>
                                </div>

                            </div>

                            <div class="summary-icon">

                                <i class="fas fa-wallet"></i>

                            </div>

                        </div>

                        <div class="summary-meta">
                            Total pagu yang tersedia
                        </div>

                    </div>

                </div>

            </div>


            <!-- RENCANA -->

            <div class="col-xl-4 col-md-6 mb-3">

                <div class="summary-card success">

                    <div class="summary-card-body">

                        <div class="summary-top">

                            <div>

                                <div class="summary-label">
                                    Total Rencana
                                </div>

                                <div class="summary-value">
                                    <?= dashboard_rupiah($total_rencana) ?>
                                </div>

                            </div>

                            <div class="summary-icon">

                                <i class="fas fa-file-invoice-dollar"></i>

                            </div>

                        </div>

                        <div class="summary-progress">

                            <div class="summary-progress-bar"
                                 style="width: <?= min(100, $persen_rencana) ?>%;">
                            </div>

                        </div>

                        <div class="summary-meta">
                            <?= $persen_rencana ?>% dari pagu anggaran
                        </div>

                    </div>

                </div>

            </div>


            <!-- BELUM DIANGGARKAN -->

            <div class="col-xl-4 col-md-6 mb-3">

                <div class="summary-card danger">

                    <div class="summary-card-body">

                        <div class="summary-top">

                            <div>

                                <div class="summary-label">
                                    Belum Dianggarkan
                                </div>

                                <div class="summary-value">
                                    <?= dashboard_rupiah($belum_dianggarkan) ?>
                                </div>

                            </div>

                            <div class="summary-icon">

                                <i class="fas fa-exclamation-triangle"></i>

                            </div>

                        </div>

                        <div class="summary-meta">
                            <?= $persen_belum ?>% dari total pagu
                        </div>

                    </div>

                </div>

            </div>

        <?php else: ?>

            <!-- JURUSAN -->

            <div class="col-md-6 col-lg-5 mb-3">

                <div class="summary-card dark">

                    <div class="summary-card-body">

                        <div class="summary-top">

                            <div>

                                <div class="summary-label">
                                    Total Rencana Anggaran
                                </div>

                                <div class="summary-value">
                                    <?= dashboard_rupiah($total_rencana) ?>
                                </div>

                                <div class="summary-meta">
                                    Total perencanaan anggaran jurusan
                                </div>

                            </div>

                            <div class="summary-icon">

                                <i class="fas fa-file-invoice-dollar"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        <?php endif; ?>

    </div>

</div>


<!-- =========================================================
     TAHAP ANGGARAN
========================================================= -->

<div class="dashboard-section">

    <div class="dashboard-section-header">

        <div>

            <h2 class="dashboard-section-title">

                <i class="fas fa-layer-group"></i>

                Rencana Berdasarkan Tahap

            </h2>

            <p class="dashboard-section-desc">
                Pembagian rencana anggaran berdasarkan dua tahap.
            </p>

        </div>

        <div class="small text-muted">
            Total:
            <strong>
                <?= dashboard_rupiah($total_tahap) ?>
            </strong>
        </div>

    </div>


    <div class="row">

        <!-- TAHAP 1 -->

        <div class="col-md-6 mb-3">

            <div class="stage-card">

                <div class="stage-body">

                    <div class="stage-head">

                        <div class="stage-icon warning">

                            <i class="fas fa-sun"></i>

                        </div>

                        <div>

                            <div class="stage-title">
                                Tahap 1
                            </div>

                            <div class="stage-period">
                                Januari – Juni
                            </div>

                        </div>

                    </div>

                    <div class="stage-value">
                        <?= dashboard_rupiah($tahap1) ?>
                    </div>

                    <div class="stage-share">
                        <?= dashboard_percent($tahap1, $total_tahap) ?>%
                        dari total tahap
                    </div>

                </div>

            </div>

        </div>


        <!-- TAHAP 2 -->

        <div class="col-md-6 mb-3">

            <div class="stage-card">

                <div class="stage-body">

                    <div class="stage-head">

                        <div class="stage-icon info">

                            <i class="fas fa-cloud-sun"></i>

                        </div>

                        <div>

                            <div class="stage-title">
                                Tahap 2
                            </div>

                            <div class="stage-period">
                                Juli – Desember
                            </div>

                        </div>

                    </div>

                    <div class="stage-value">
                        <?= dashboard_rupiah($tahap2) ?>
                    </div>

                    <div class="stage-share">
                        <?= dashboard_percent($tahap2, $total_tahap) ?>%
                        dari total tahap
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- =========================================================
     TRIWULAN
========================================================= -->

<div class="dashboard-section">

    <div class="dashboard-section-header">

        <div>

            <h2 class="dashboard-section-title">

                <i class="fas fa-calendar-week"></i>

                Rencana Per Triwulan

            </h2>

            <p class="dashboard-section-desc">
                Distribusi rencana anggaran sepanjang tahun.
            </p>

        </div>

    </div>


    <div class="row">

        <?php
        $quarters = array(
            array(
                'label' => 'Triwulan 1',
                'value' => $tw1
            ),
            array(
                'label' => 'Triwulan 2',
                'value' => $tw2
            ),
            array(
                'label' => 'Triwulan 3',
                'value' => $tw3
            ),
            array(
                'label' => 'Triwulan 4',
                'value' => $tw4
            )
        );
        ?>

        <?php foreach ($quarters as $quarter): ?>

            <?php
            $quarter_percent = dashboard_percent(
                $quarter['value'],
                $total_rencana
            );
            ?>

            <div class="col-xl-3 col-md-6 mb-3">

                <div class="quarter-card">

                    <div class="quarter-body">

                        <div class="quarter-label">
                            <?= $quarter['label'] ?>
                        </div>

                        <div class="quarter-value">
                            <?= dashboard_rupiah($quarter['value']) ?>
                        </div>

                        <div class="quarter-line">

                            <span style="
                                width:<?= min(100, $quarter_percent) ?>%;
                            "></span>

                        </div>

                    </div>

                </div>

            </div>

        <?php endforeach; ?>

    </div>

</div>


<!-- =========================================================
     BULANAN
========================================================= -->

<div class="dashboard-section">

    <div class="dashboard-section-header">

        <div>

            <h2 class="dashboard-section-title">

                <i class="fas fa-calendar-alt"></i>

                Rencana Penggunaan Per Bulan

            </h2>

            <p class="dashboard-section-desc">
                Rencana anggaran dari Januari sampai Desember.
            </p>

        </div>

    </div>


    <div class="month-card">

        <div class="month-summary">

            <div>

                <div class="month-summary-label">
                    Total Rencana Bulanan
                </div>

                <div class="month-summary-value">
                    <?= dashboard_rupiah($total_bulanan) ?>
                </div>

            </div>

            <div class="text-right">

                <div class="small text-muted">
                    12 bulan
                </div>

                <div class="small font-weight-bold text-primary">
                    <?= dashboard_percent(
                        $total_bulanan,
                        $total_rencana
                    ) ?>%
                    dari total rencana
                </div>

            </div>

        </div>


        <div class="table-responsive">

            <table class="table month-table text-center">

                <thead>

                    <tr>

                        <th>Jan</th>
                        <th>Feb</th>
                        <th>Mar</th>
                        <th>Apr</th>
                        <th>Mei</th>
                        <th>Jun</th>
                        <th>Jul</th>
                        <th>Agu</th>
                        <th>Sep</th>
                        <th>Okt</th>
                        <th>Nov</th>
                        <th>Des</th>

                        <th class="total-column">
                            Total
                        </th>

                    </tr>

                </thead>

                <tbody>

                    <tr>

                        <td><?= dashboard_number($jan) ?></td>
                        <td><?= dashboard_number($feb) ?></td>
                        <td><?= dashboard_number($mar) ?></td>
                        <td><?= dashboard_number($apr) ?></td>
                        <td><?= dashboard_number($mei) ?></td>
                        <td><?= dashboard_number($jun) ?></td>
                        <td><?= dashboard_number($jul) ?></td>
                        <td><?= dashboard_number($agu) ?></td>
                        <td><?= dashboard_number($sep) ?></td>
                        <td><?= dashboard_number($okt) ?></td>
                        <td><?= dashboard_number($nov) ?></td>
                        <td><?= dashboard_number($des) ?></td>

                        <td class="total-column">
                            <?= dashboard_number($total_bulanan) ?>
                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>


<!-- =========================================================
     REKAP JURUSAN
========================================================= -->

<div class="dashboard-section">

    <div class="dashboard-section-header">

        <div>

            <h2 class="dashboard-section-title">

                <i class="fas fa-school"></i>

                Perencanaan Berdasarkan Jurusan

            </h2>

            <p class="dashboard-section-desc">
                Perbandingan anggaran tahap 1 dan tahap 2 setiap jurusan.
            </p>

        </div>

    </div>


    <div class="data-card">

        <div class="table-responsive">

            <table class="table dashboard-table">

                <thead>

                    <tr>

                        <th class="table-number">
                            No
                        </th>

                        <th>
                            Jurusan
                        </th>

                        <th class="text-right">
                            Tahap 1
                        </th>

                        <th class="text-right">
                            Tahap 2
                        </th>

                        <th class="text-right">
                            Total
                        </th>

                    </tr>

                </thead>

                <tbody>

                    <?php if (!empty($rekap_jurusan)): ?>

                        <?php $no = 1; ?>

                        <?php foreach ($rekap_jurusan as $r): ?>

                            <?php
                            $r_tahap1 = (float) ($r->tahap1 ?? 0);
                            $r_tahap2 = (float) ($r->tahap2 ?? 0);
                            $r_total  = $r_tahap1 + $r_tahap2;
                            ?>

                            <tr>

                                <td class="table-number">
                                    <?= $no++ ?>
                                </td>

                                <td class="table-name">
                                    <?= html_escape($r->jurusan) ?>
                                </td>

                                <td class="text-right">
                                    <?= dashboard_rupiah($r_tahap1) ?>
                                </td>

                                <td class="text-right">
                                    <?= dashboard_rupiah($r_tahap2) ?>
                                </td>

                                <td class="text-right font-weight-bold">
                                    <?= dashboard_rupiah($r_total) ?>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>

                            <td colspan="5">

                                <div class="dashboard-empty">

                                    <i class="fas fa-school"></i>

                                    <p>
                                        Belum ada data perencanaan jurusan.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>


<!-- =========================================================
     SNP
========================================================= -->

<div class="dashboard-section">

    <div class="dashboard-section-header">

        <div>

            <h2 class="dashboard-section-title">

                <i class="fas fa-book-open"></i>

                Perencanaan Berdasarkan SNP

            </h2>

            <p class="dashboard-section-desc">
                Distribusi anggaran berdasarkan Standar Nasional Pendidikan.
            </p>

        </div>

    </div>


    <div class="data-card">

        <div class="table-responsive">

            <table class="table dashboard-table">

                <thead>

                    <tr>

                        <th class="table-number">
                            No
                        </th>

                        <th>
                            Standar Nasional Pendidikan
                        </th>

                        <th class="text-right">
                            Tahap 1
                        </th>

                        <th class="text-right">
                            Tahap 2
                        </th>

                        <th class="text-right">
                            Total
                        </th>

                    </tr>

                </thead>


                <tbody>

                    <?php if (!empty($perencanaan_snp)): ?>

                        <?php $no = 1; ?>

                        <?php foreach ($perencanaan_snp as $s): ?>

                            <?php
                            $s_tahap1 = (float) ($s->tahap1 ?? 0);
                            $s_tahap2 = (float) ($s->tahap2 ?? 0);
                            $s_total  = $s_tahap1 + $s_tahap2;
                            ?>

                            <tr>

                                <td class="table-number">
                                    <?= $no++ ?>
                                </td>

                                <td class="table-name">
                                    <?= html_escape($s->snp) ?>
                                </td>

                                <td class="text-right">
                                    <?= dashboard_rupiah($s_tahap1) ?>
                                </td>

                                <td class="text-right">
                                    <?= dashboard_rupiah($s_tahap2) ?>
                                </td>

                                <td class="text-right font-weight-bold">
                                    <?= dashboard_rupiah($s_total) ?>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>

                            <td colspan="5">

                                <div class="dashboard-empty">

                                    <i class="fas fa-book-open"></i>

                                    <p>
                                        Belum ada data perencanaan SNP.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    <?php endif; ?>

                </tbody>


                <tfoot>

                    <tr>

                        <td colspan="4"
                            class="text-right">

                            TOTAL BESAR

                        </td>

                        <td class="text-right">

                            <?= dashboard_rupiah($snp_grand_total ?? 0) ?>

                        </td>

                    </tr>

                </tfoot>

            </table>

        </div>

    </div>

</div>


<?php if ($isAdmin): ?>


<!-- =========================================================
     GRAFIK
========================================================= -->

<div class="dashboard-section">

    <div class="dashboard-section-header">

        <div>

            <h2 class="dashboard-section-title">

                <i class="fas fa-chart-pie"></i>

                Analisis Anggaran

            </h2>

            <p class="dashboard-section-desc">
                Visualisasi komposisi rencana anggaran.
            </p>

        </div>

    </div>


    <div class="row">

        <!-- JENIS BELANJA -->

        <div class="col-lg-6 mb-4">

            <div class="chart-card">

                <div class="chart-card-header">

                    <h3 class="chart-title">
                        Proporsi Jenis Belanja
                    </h3>

                    <div class="chart-description">
                        Komposisi rencana berdasarkan jenis belanja.
                    </div>

                </div>

                <div class="chart-card-body">

                    <canvas id="donutBelanja"
                            height="220">
                    </canvas>

                </div>

            </div>

        </div>


        <!-- BUKU -->

        <div class="col-lg-6 mb-4">

            <div class="chart-card">

                <div class="chart-card-header">

                    <h3 class="chart-title">
                        Proporsi Penyediaan Buku
                    </h3>

                    <div class="chart-description">
                        Perbandingan rencana penyediaan buku
                        dengan komponen lainnya.
                    </div>

                </div>

                <div class="chart-card-body">

                    <canvas id="donutBukuSimple"
                            height="220">
                    </canvas>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- =========================================================
     CHART JS
========================================================= -->

<script src="<?= base_url('assets/sbadmin2/vendor/chart.js/Chart.min.js') ?>"></script>

<script>

document.addEventListener(
    'DOMContentLoaded',
    function()
    {

        /* =====================================================
           DATA BELANJA
        ===================================================== */

        var nilaiBelanja = [

            <?= (float) ($diagram['honorarium'] ?? 0) ?>,

            <?= (float) ($diagram['barang'] ?? 0) ?>,

            <?= (float) ($diagram['jasa'] ?? 0) ?>,

            <?= (float) ($diagram['pemeliharaan'] ?? 0) ?>,

            <?= (float) ($diagram['perjalanan_dinas'] ?? 0) ?>,

            <?= (float) ($diagram['modal_alat_mesin'] ?? 0) ?>,

            <?= (float) ($diagram['modal_aset_lainnya'] ?? 0) ?>

        ];


        var labelBelanja = [

            'Honorarium',

            'Barang',

            'Jasa',

            'Pemeliharaan',

            'Perjalanan Dinas',

            'Modal Alat & Mesin',

            'Modal Aset Tetap Lainnya'

        ];


        var totalBelanja =
            nilaiBelanja.reduce(
                function(total, value)
                {
                    return total + value;
                },
                0
            );


        var labelDenganPersen =
            labelBelanja.map(
                function(label, index)
                {

                    var persen =
                        totalBelanja > 0
                            ? (
                                nilaiBelanja[index]
                                /
                                totalBelanja
                                *
                                100
                            ).toFixed(1)
                            : 0;

                    return label + ' (' + persen + '%)';

                }
            );


        /* =====================================================
           CHART BELANJA
        ===================================================== */

        var donutBelanja =
            document.getElementById(
                'donutBelanja'
            );


        if (donutBelanja) {

            new Chart(
                donutBelanja,
                {
                    type: 'doughnut',

                    data: {

                        labels: labelDenganPersen,

                        datasets: [{

                            data: nilaiBelanja,

                            backgroundColor: [
                                '#6c757d',
                                '#4caf50',
                                '#ff9800',
                                '#e74a3b',
                                '#f1c40f',
                                '#e91e63',
                                '#4e73df'
                            ],

                            borderWidth: 2

                        }]

                    },

                    options: {

                        responsive: true,

                        maintainAspectRatio: false,

                        cutoutPercentage: 62,

                        legend: {
                            position: 'bottom',

                            labels: {
                                boxWidth: 12,
                                padding: 12,

                                fontSize: 10
                            }
                        },

                        tooltips: {

                            callbacks: {

                                label: function(
                                    tooltipItem,
                                    data
                                )
                                {

                                    var label =
                                        data.labels[
                                            tooltipItem.index
                                        ] || '';

                                    var value =
                                        data.datasets[0]
                                            .data[
                                                tooltipItem.index
                                            ] || 0;

                                    return label
                                        + ': Rp '
                                        + Number(value)
                                            .toLocaleString(
                                                'id-ID'
                                            );

                                }

                            }

                        }

                    }

                }
            );

        }


        /* =====================================================
           CHART BUKU
        ===================================================== */

        var donutBuku =
            document.getElementById(
                'donutBukuSimple'
            );


        if (donutBuku) {

            new Chart(
                donutBuku,
                {
                    type: 'doughnut',

                    data: {

                        labels: [

                            'Penyediaan Buku (<?= $persen_buku ?>%)',

                            'Komponen Anggaran Lainnya'

                        ],

                        datasets: [{

                            data: [

                                <?= (float) ($diagram_buku_simple['buku'] ?? 0) ?>,

                                <?= (float) ($diagram_buku_simple['lainnya'] ?? 0) ?>

                            ],

                            backgroundColor: [

                                '#6f42c1',

                                '#90b9ff'

                            ],

                            borderWidth: 2

                        }]

                    },

                    options: {

                        responsive: true,

                        maintainAspectRatio: false,

                        cutoutPercentage: 62,

                        legend: {
                            position: 'bottom',

                            labels: {
                                boxWidth: 12,
                                padding: 12,

                                fontSize: 10
                            }
                        },

                        tooltips: {

                            callbacks: {

                                label: function(
                                    tooltipItem,
                                    data
                                )
                                {

                                    var label =
                                        data.labels[
                                            tooltipItem.index
                                        ];

                                    var value =
                                        data.datasets[0]
                                            .data[
                                                tooltipItem.index
                                            ];

                                    return label
                                        + ': Rp '
                                        + Number(value)
                                            .toLocaleString(
                                                'id-ID'
                                            );

                                }

                            }

                        }

                    }

                }
            );

        }

    }
);

</script>

<?php endif; ?>