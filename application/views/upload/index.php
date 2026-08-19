<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Helper
|--------------------------------------------------------------------------
*/
$csrf_name = $this->security->get_csrf_token_name();
$csrf_hash = $this->security->get_csrf_hash();

/*
|--------------------------------------------------------------------------
| Statistik
|--------------------------------------------------------------------------
*/
$stats_2025 = isset($stats[2025]) ? $stats[2025] : array(
    'total_point'  => 46,
    'point_terisi' => 0,
    'total_file'   => 0
);

$stats_2026 = isset($stats[2026]) ? $stats[2026] : array(
    'total_point'  => 46,
    'point_terisi' => 0,
    'total_file'   => 0
);

$total_point = max(
    (int) $stats_2025['total_point'],
    (int) $stats_2026['total_point'],
    46
);

$persen_2025 = $total_point > 0
    ? round(($stats_2025['point_terisi'] / $total_point) * 100)
    : 0;

$persen_2026 = $total_point > 0
    ? round(($stats_2026['point_terisi'] / $total_point) * 100)
    : 0;


/*
|--------------------------------------------------------------------------
| Helper format ukuran
|--------------------------------------------------------------------------
*/
if (!function_exists('upload_format_size')) {

    function upload_format_size($bytes)
    {
        $bytes = (float) $bytes;

        if ($bytes <= 0) {
            return '0 KB';
        }

        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        }

        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        }

        return number_format($bytes / 1024, 1) . ' KB';
    }
}


/*
|--------------------------------------------------------------------------
| Helper icon file
|--------------------------------------------------------------------------
*/
if (!function_exists('upload_file_icon')) {

    function upload_file_icon($extension)
    {
        $extension = strtolower(trim($extension));

        switch ($extension) {

            case 'pdf':
                return array(
                    'icon'  => 'fa-file-pdf',
                    'class' => 'file-pdf'
                );

            case 'xls':
            case 'xlsx':
            case 'csv':
                return array(
                    'icon'  => 'fa-file-excel',
                    'class' => 'file-excel'
                );

            case 'doc':
            case 'docx':
                return array(
                    'icon'  => 'fa-file-word',
                    'class' => 'file-word'
                );

            case 'ppt':
            case 'pptx':
                return array(
                    'icon'  => 'fa-file-powerpoint',
                    'class' => 'file-powerpoint'
                );

            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
            case 'webp':
                return array(
                    'icon'  => 'fa-file-image',
                    'class' => 'file-image'
                );

            case 'zip':
            case 'rar':
            case '7z':
                return array(
                    'icon'  => 'fa-file-archive',
                    'class' => 'file-archive'
                );

            default:
                return array(
                    'icon'  => 'fa-file',
                    'class' => 'file-default'
                );
        }
    }
}
?>

<div class="container-fluid upload-page">

<style>

/* =========================================================
   PAGE
========================================================= */

.upload-page {
    padding-bottom: 50px;
}

.upload-page *,
.upload-page *::before,
.upload-page *::after {
    box-sizing: border-box;
}


/* =========================================================
   HEADER
========================================================= */

.upload-hero {
    position: relative;
    overflow: hidden;
    border-radius: 22px;
    padding: 30px 32px;
    margin-bottom: 24px;

    background:
        linear-gradient(
            135deg,
            #4e73df 0%,
            #3b5fc0 55%,
            #224abe 100%
        );

    color: #fff;

    box-shadow:
        0 10px 30px rgba(78, 115, 223, .18);
}

.upload-hero::before {
    content: "";
    position: absolute;
    width: 230px;
    height: 230px;
    border-radius: 50%;

    right: -80px;
    top: -110px;

    background: rgba(255,255,255,.08);
}

.upload-hero::after {
    content: "";
    position: absolute;
    width: 150px;
    height: 150px;
    border-radius: 50%;

    right: 100px;
    bottom: -110px;

    background: rgba(255,255,255,.05);
}

.upload-hero-content {
    position: relative;
    z-index: 2;
}

.upload-hero-title {
    display: flex;
    align-items: center;
    gap: 14px;

    margin: 0 0 8px;

    font-size: 1.55rem;
    font-weight: 800;
}

.upload-hero-icon {
    width: 48px;
    height: 48px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 14px;

    background: rgba(255,255,255,.15);

    font-size: 1.35rem;
}

.upload-hero-desc {
    margin: 0;

    font-size: .9rem;

    color: rgba(255,255,255,.88);
}

.upload-hero-action {
    position: relative;
    z-index: 3;
}

.btn-hero {
    border: 0;
    border-radius: 11px;

    padding: 10px 17px;

    font-weight: 700;

    color: #4e73df;
    background: #fff;

    box-shadow: 0 5px 15px rgba(0,0,0,.08);
}

.btn-hero:hover {
    color: #224abe;
    background: #fff;
    transform: translateY(-1px);
}


/* =========================================================
   ALERT
========================================================= */

.upload-alert {
    border: 0;
    border-radius: 13px;

    box-shadow: 0 3px 12px rgba(0,0,0,.04);
}


/* =========================================================
   STAT CARD
========================================================= */

.upload-stat {
    height: 100%;

    border: 0;
    border-radius: 17px;

    background: #fff;

    box-shadow:
        0 4px 18px rgba(0,0,0,.055);

    transition: .2s ease;
}

.upload-stat:hover {
    transform: translateY(-2px);

    box-shadow:
        0 8px 25px rgba(0,0,0,.08);
}

.upload-stat-body {
    padding: 20px;
}

.upload-stat-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.upload-stat-label {
    font-size: .72rem;
    font-weight: 800;

    color: #858796;

    text-transform: uppercase;
    letter-spacing: .4px;
}

.upload-stat-number {
    margin-top: 4px;

    font-size: 1.45rem;
    font-weight: 800;

    color: #343a40;
}

.upload-stat-icon {
    width: 45px;
    height: 45px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 13px;

    color: #4e73df;
    background: #edf2ff;

    font-size: 1.15rem;
}

.upload-progress {
    height: 7px;

    margin-top: 14px;

    overflow: hidden;

    border-radius: 10px;

    background: #eaecf4;
}

.upload-progress-bar {
    height: 100%;

    border-radius: 10px;

    background: #4e73df;

    transition: width .4s ease;
}

.upload-stat-meta {
    display: flex;
    justify-content: space-between;

    margin-top: 7px;

    font-size: .74rem;

    color: #858796;
}


/* =========================================================
   FILTER
========================================================= */

.upload-filter {
    border: 0;
    border-radius: 17px;

    background: #fff;

    box-shadow:
        0 4px 18px rgba(0,0,0,.05);
}

.upload-filter .card-body {
    padding: 18px 20px;
}

.upload-label {
    display: block;

    margin-bottom: 6px;

    font-size: .74rem;
    font-weight: 800;

    color: #5a5c69;
}

.upload-input,
.upload-select {
    height: 42px;

    border-radius: 10px;

    border: 1px solid #dfe3ee;

    font-size: .85rem;
}

.upload-input:focus,
.upload-select:focus {
    border-color: #8ca7f3;

    box-shadow:
        0 0 0 .15rem rgba(78,115,223,.12);
}

.btn-filter {
    height: 42px;

    border-radius: 10px;

    font-weight: 700;
}


/* =========================================================
   POINT CARD
========================================================= */

.point-card {
    border: 0;
    border-radius: 18px;

    margin-bottom: 18px;

    background: #fff;

    box-shadow:
        0 4px 18px rgba(0,0,0,.05);

    overflow: hidden;

    transition: .2s ease;
}

.point-card:hover {
    box-shadow:
        0 8px 26px rgba(0,0,0,.075);
}

.point-header {
    display: flex;

    align-items: flex-start;

    gap: 14px;

    padding: 19px 20px 15px;
}

.point-number {
    width: 43px;
    height: 43px;

    flex: 0 0 43px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 12px;

    color: #4e73df;
    background: #edf2ff;

    font-size: .88rem;
    font-weight: 800;
}

.point-content {
    min-width: 0;
    flex: 1;
}

.point-title {
    margin: 0;

    color: #343a40;

    font-size: .94rem;
    font-weight: 750;

    line-height: 1.5;
}

.point-description {
    margin-top: 5px;

    color: #858796;

    font-size: .76rem;
    line-height: 1.5;
}

.point-actions {
    display: flex;
    gap: 5px;
}

.point-action {
    width: 34px;
    height: 34px;

    padding: 0;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    border-radius: 9px;
}


/* =========================================================
   YEAR BOX
========================================================= */

.point-body {
    padding: 0 20px 20px;
}

.year-box {
    height: 100%;

    border: 1px solid #e7eaf2;

    border-radius: 14px;

    background: #fbfcff;

    overflow: hidden;
}

.year-header {
    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 10px;

    padding: 13px 14px;

    border-bottom: 1px solid #e7eaf2;

    background: #f8f9fc;
}

.year-label {
    display: flex;
    align-items: center;

    gap: 8px;

    color: #343a40;

    font-size: .82rem;
    font-weight: 800;
}

.year-icon {
    width: 29px;
    height: 29px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 8px;

    color: #4e73df;
    background: #e9efff;

    font-size: .75rem;
}

.btn-upload {
    border-radius: 8px;

    padding: 6px 10px;

    font-size: .73rem;
    font-weight: 700;
}

.year-files {
    padding: 12px;
}


/* =========================================================
   FILE ITEM
========================================================= */

.file-item {
    display: flex;
    align-items: center;

    gap: 10px;

    padding: 10px;

    margin-bottom: 8px;

    border: 1px solid #e8eaf0;

    border-radius: 10px;

    background: #fff;

    transition: .15s ease;
}

.file-item:last-child {
    margin-bottom: 0;
}

.file-item:hover {
    border-color: #cfd7ed;

    box-shadow:
        0 3px 10px rgba(0,0,0,.035);
}

.file-icon {
    width: 37px;
    height: 37px;

    flex: 0 0 37px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 9px;

    font-size: .9rem;
}

.file-pdf {
    color: #dc3545;
    background: #fff0f1;
}

.file-excel {
    color: #198754;
    background: #eaf7ef;
}

.file-word {
    color: #0d6efd;
    background: #edf4ff;
}

.file-powerpoint {
    color: #e8590c;
    background: #fff3eb;
}

.file-image {
    color: #6f42c1;
    background: #f4efff;
}

.file-archive {
    color: #856404;
    background: #fff8dc;
}

.file-default {
    color: #6c757d;
    background: #f0f1f3;
}

.file-info {
    min-width: 0;
    flex: 1;
}

.file-name {
    display: block;

    overflow: hidden;

    color: #343a40;

    font-size: .78rem;
    font-weight: 700;

    line-height: 1.35;

    text-overflow: ellipsis;
    white-space: nowrap;
}

.file-meta {
    margin-top: 3px;

    color: #9a9caf;

    font-size: .68rem;
}

.file-actions {
    display: flex;

    flex: 0 0 auto;

    gap: 4px;
}

.file-action {
    width: 30px;
    height: 30px;

    padding: 0;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    border-radius: 8px;
}


/* =========================================================
   EMPTY
========================================================= */

.empty-file {
    padding: 17px 10px;

    text-align: center;

    color: #a0a3b1;

    font-size: .76rem;
}

.empty-file i {
    display: block;

    margin-bottom: 6px;

    font-size: 1rem;
}


/* =========================================================
   MODAL
========================================================= */

.upload-modal .modal-content {
    border: 0;

    border-radius: 18px;

    overflow: hidden;

    box-shadow:
        0 15px 50px rgba(0,0,0,.18);
}

.upload-modal .modal-header {
    padding: 17px 20px;

    border-bottom: 1px solid #edf0f5;

    background: #fff;
}

.upload-modal .modal-title {
    display: flex;
    align-items: center;

    gap: 10px;

    color: #343a40;

    font-size: 1rem;
    font-weight: 800;
}

.modal-upload-icon {
    width: 36px;
    height: 36px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 10px;

    color: #4e73df;
    background: #edf2ff;
}

.upload-modal .modal-body {
    padding: 20px;
}

.upload-point-preview {
    padding: 13px 15px;

    border: 1px solid #e3e7f0;

    border-radius: 11px;

    background: #f8f9fc;
}

.upload-point-label {
    margin-bottom: 3px;

    color: #858796;

    font-size: .68rem;
    font-weight: 700;

    text-transform: uppercase;
}

.upload-point-name {
    color: #343a40;

    font-size: .82rem;
    font-weight: 700;

    line-height: 1.45;
}

.upload-modal label {
    color: #5a5c69;

    font-size: .76rem;
    font-weight: 800;
}

.upload-file-input {
    width: 100%;

    padding: 8px;

    border: 1px solid #dfe3ee;

    border-radius: 9px;

    font-size: .8rem;

    background: #fff;
}

.upload-file-help {
    margin-top: 6px;

    color: #858796;

    font-size: .68rem;
    line-height: 1.5;
}

.upload-textarea {
    min-height: 100px;

    border-radius: 10px;

    border-color: #dfe3ee;

    resize: vertical;
}

.upload-textarea:focus {
    border-color: #8ca7f3;

    box-shadow:
        0 0 0 .15rem rgba(78,115,223,.12);
}

.upload-modal .modal-footer {
    padding: 14px 20px;

    border-top: 1px solid #edf0f5;

    background: #fbfcfe;
}

.btn-modal {
    border-radius: 9px;

    font-size: .78rem;
    font-weight: 700;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 991.98px) {

    .upload-hero {
        padding: 25px;
    }

    .upload-hero-title {
        font-size: 1.35rem;
    }

    .point-body {
        padding-bottom: 15px;
    }
}


@media (max-width: 767.98px) {

    .upload-page {
        padding-left: 10px;
        padding-right: 10px;
    }

    .upload-hero {
        padding: 21px;

        border-radius: 17px;
    }

    .upload-hero-title {
        font-size: 1.15rem;
    }

    .upload-hero-icon {
        width: 42px;
        height: 42px;
    }

    .upload-hero-action {
        margin-top: 17px;
    }

    .btn-hero {
        width: 100%;
    }

    .point-header {
        padding: 15px;
    }

    .point-body {
        padding: 0 15px 15px;
    }

    .point-actions {
        flex-direction: column;
    }

    .point-action {
        width: 32px;
        height: 32px;
    }

    .year-header {
        padding: 11px;
    }

    .year-files {
        padding: 10px;
    }

    .file-name {
        white-space: normal;
    }
}


/* =========================================================
   DARK MODE
========================================================= */

body.dark-mode .upload-stat,
body.dark-mode .upload-filter,
body.dark-mode .point-card {
    background: #1f2937;
}

body.dark-mode .upload-stat-number,
body.dark-mode .point-title,
body.dark-mode .file-name,
body.dark-mode .upload-point-name,
body.dark-mode .year-label {
    color: #f3f4f6;
}

body.dark-mode .upload-label,
body.dark-mode .upload-modal label {
    color: #d1d5db;
}

body.dark-mode .upload-input,
body.dark-mode .upload-select,
body.dark-mode .upload-file-input,
body.dark-mode .upload-textarea {
    color: #f3f4f6;

    background: #111827;

    border-color: #374151;
}

body.dark-mode .year-box {
    background: #111827;
    border-color: #374151;
}

body.dark-mode .year-header {
    background: #1f2937;
    border-color: #374151;
}

body.dark-mode .file-item {
    background: #1f2937;
    border-color: #374151;
}

body.dark-mode .file-name {
    color: #f3f4f6;
}

body.dark-mode .upload-point-preview {
    background: #1f2937;
    border-color: #374151;
}

body.dark-mode .upload-point-name {
    color: #f3f4f6;
}

</style>


<!-- =========================================================
     HEADER
========================================================= -->

<div class="upload-hero">

    <div class="upload-hero-content">

        <div class="row align-items-center">

            <div class="col-lg-9">

                <div class="upload-hero-title">

                    <div class="upload-hero-icon">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>

                    <div>
                        Berkas Inspektorat
                    </div>

                </div>

                <p class="upload-hero-desc">
                    Kelola dokumen permintaan pemeriksaan keuangan
                    tahun 2025 dan 2026.
                </p>

            </div>

            <div class="col-lg-3 upload-hero-action text-lg-right">

                <a href="<?= site_url('upload/tambah_point') ?>"
                   class="btn btn-hero">

                    <i class="fas fa-plus mr-1"></i>

                    Tambah Point

                </a>

            </div>

        </div>

    </div>

</div>


<!-- =========================================================
     FLASH MESSAGE
========================================================= -->

<?php if ($this->session->flashdata('success')): ?>

    <div class="alert alert-success alert-dismissible fade show upload-alert"
         role="alert">

        <i class="fas fa-check-circle mr-2"></i>

        <?= html_escape($this->session->flashdata('success')) ?>

        <button type="button"
                class="close"
                data-dismiss="alert">

            <span>&times;</span>

        </button>

    </div>

<?php endif; ?>


<?php if ($this->session->flashdata('error')): ?>

    <div class="alert alert-danger alert-dismissible fade show upload-alert"
         role="alert">

        <i class="fas fa-exclamation-circle mr-2"></i>

        <?= html_escape($this->session->flashdata('error')) ?>

        <button type="button"
                class="close"
                data-dismiss="alert">

            <span>&times;</span>

        </button>

    </div>

<?php endif; ?>


<!-- =========================================================
     STATISTIK
========================================================= -->

<div class="row mb-4">

    <!-- 2025 -->

    <div class="col-md-6 mb-3 mb-md-0">

        <div class="upload-stat">

            <div class="upload-stat-body">

                <div class="upload-stat-top">

                    <div>

                        <div class="upload-stat-label">
                            Kelengkapan Tahun 2025
                        </div>

                        <div class="upload-stat-number">
                            <?= (int) $stats_2025['point_terisi'] ?>
                            /
                            <?= $total_point ?>
                            Point
                        </div>

                    </div>

                    <div class="upload-stat-icon">

                        <i class="fas fa-calendar-alt"></i>

                    </div>

                </div>

                <div class="upload-progress">

                    <div class="upload-progress-bar"
                         style="width: <?= min(100, $persen_2025) ?>%;">
                    </div>

                </div>

                <div class="upload-stat-meta">

                    <span>
                        <?= $persen_2025 ?>% lengkap
                    </span>

                    <span>
                        <?= (int) $stats_2025['total_file'] ?> file
                    </span>

                </div>

            </div>

        </div>

    </div>


    <!-- 2026 -->

    <div class="col-md-6">

        <div class="upload-stat">

            <div class="upload-stat-body">

                <div class="upload-stat-top">

                    <div>

                        <div class="upload-stat-label">
                            Kelengkapan Tahun 2026
                        </div>

                        <div class="upload-stat-number">
                            <?= (int) $stats_2026['point_terisi'] ?>
                            /
                            <?= $total_point ?>
                            Point
                        </div>

                    </div>

                    <div class="upload-stat-icon">

                        <i class="fas fa-calendar-alt"></i>

                    </div>

                </div>

                <div class="upload-progress">

                    <div class="upload-progress-bar"
                         style="width: <?= min(100, $persen_2026) ?>%;">
                    </div>

                </div>

                <div class="upload-stat-meta">

                    <span>
                        <?= $persen_2026 ?>% lengkap
                    </span>

                    <span>
                        <?= (int) $stats_2026['total_file'] ?> file
                    </span>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- =========================================================
     FILTER
========================================================= -->

<div class="card upload-filter mb-4">

    <div class="card-body">

        <form method="get"
              action="<?= site_url('upload') ?>">

            <div class="row align-items-end">

                <div class="col-lg-7 col-md-6 mb-3 mb-md-0">

                    <label class="upload-label">
                        Cari Dokumen
                    </label>

                    <div class="input-group">

                        <div class="input-group-prepend">

                            <span class="input-group-text bg-white"
                                  style="border-radius:10px 0 0 10px;">

                                <i class="fas fa-search text-primary"></i>

                            </span>

                        </div>

                        <input type="text"
                               name="q"
                               value="<?= html_escape($keyword) ?>"
                               class="form-control upload-input"
                               style="border-left:0;border-radius:0 10px 10px 0;"
                               placeholder="Cari nomor atau nama point...">

                    </div>

                </div>


                <div class="col-lg-3 col-md-3 mb-3 mb-md-0">

                    <label class="upload-label">
                        Tahun
                    </label>

                    <select name="tahun"
                            class="form-control upload-select">

                        <option value="2025"
                            <?= $tahun == 2025 ? 'selected' : '' ?>>
                            Tahun 2025
                        </option>

                        <option value="2026"
                            <?= $tahun == 2026 ? 'selected' : '' ?>>
                            Tahun 2026
                        </option>

                    </select>

                </div>


                <div class="col-lg-2 col-md-3">

                    <button type="submit"
                            class="btn btn-primary btn-block btn-filter">

                        <i class="fas fa-filter mr-1"></i>

                        Filter

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>


<!-- =========================================================
     LIST POINT
========================================================= -->

<?php if (!empty($points)): ?>

    <?php foreach ($points as $point): ?>

        <?php
        $files_2025 = !empty($point->files_2025)
            ? $point->files_2025
            : array();

        $files_2026 = !empty($point->files_2026)
            ? $point->files_2026
            : array();
        ?>

        <div class="point-card">

            <!-- POINT HEADER -->

            <div class="point-header">

                <div class="point-number">
                    <?= sprintf('%02d', (int) $point->nomor) ?>
                </div>

                <div class="point-content">

                    <h3 class="point-title">
                        <?= html_escape($point->nama_point) ?>
                    </h3>

                    <?php if (!empty($point->keterangan)): ?>

                        <div class="point-description">

                            <?= nl2br(
                                html_escape($point->keterangan)
                            ) ?>

                        </div>

                    <?php endif; ?>

                </div>


                <div class="point-actions">

                    <a href="<?= site_url('upload/edit_point/' . $point->id) ?>"
                       class="btn btn-sm btn-outline-warning point-action"
                       title="Edit Point">

                        <i class="fas fa-edit"></i>

                    </a>

                    <!-- <a href="<?= site_url('upload/delete_point/' . $point->id) ?>"
                       class="btn btn-sm btn-outline-danger point-action"
                       title="Hapus Point"
                       onclick="return confirm('Hapus point ini beserta seluruh berkas di dalamnya?')">

                        <i class="fas fa-trash"></i>

                    </a> -->

                </div>

            </div>


            <!-- YEAR CONTENT -->

            <div class="point-body">

                <div class="row">

                    <!-- =================================================
                         TAHUN 2025
                    ================================================== -->

                    <div class="col-lg-6 mb-3 mb-lg-0">

                        <div class="year-box">

                            <div class="year-header">

                                <div class="year-label">

                                    <div class="year-icon">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>

                                    Tahun 2025

                                </div>

                                <button type="button"
                                        class="btn btn-primary btn-upload"
                                        onclick="openUploadModal(
                                            <?= (int) $point->id ?>,
                                            <?= htmlspecialchars(
                                                json_encode($point->nama_point),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>,
                                            2025
                                        )">

                                    <i class="fas fa-upload mr-1"></i>

                                    Upload

                                </button>

                            </div>


                            <div class="year-files">

                                <?php if (!empty($files_2025)): ?>

                                    <?php foreach ($files_2025 as $file): ?>

                                        <?php
                                        $file_icon = upload_file_icon(
                                            $file->ekstensi
                                        );
                                        ?>

                                        <div class="file-item">

                                            <div class="file-icon <?= $file_icon['class'] ?>">

                                                <i class="fas <?= $file_icon['icon'] ?>"></i>

                                            </div>


                                            <div class="file-info">

                                                <div class="file-name"
                                                     title="<?= html_escape($file->nama_file_asli) ?>">

                                                    <?= html_escape(
                                                        $file->nama_file_asli
                                                    ) ?>

                                                </div>

                                                <div class="file-meta">

                                                    <?= upload_format_size(
                                                        $file->ukuran_file
                                                    ) ?>

                                                    <span class="mx-1">
                                                        &bull;
                                                    </span>

                                                    <?= date(
                                                        'd/m/Y H:i',
                                                        strtotime($file->uploaded_at)
                                                    ) ?>

                                                </div>

                                            </div>


                                            <div class="file-actions">

                                                <a href="<?= site_url('upload/download/' . $file->id) ?>"
                                                   class="btn btn-sm btn-outline-primary file-action"
                                                   title="Download">

                                                    <i class="fas fa-download"></i>

                                                </a>

                                                <a href="<?= site_url('upload/delete_file/' . $file->id) ?>"
                                                   class="btn btn-sm btn-outline-danger file-action"
                                                   title="Hapus"
                                                   onclick="return confirm('Hapus berkas ini?')">

                                                    <i class="fas fa-trash"></i>

                                                </a>

                                            </div>

                                        </div>

                                    <?php endforeach; ?>

                                <?php else: ?>

                                    <div class="empty-file">

                                        <i class="far fa-folder-open"></i>

                                        Belum ada berkas untuk tahun 2025.

                                    </div>

                                <?php endif; ?>

                            </div>

                        </div>

                    </div>


                    <!-- =================================================
                         TAHUN 2026
                    ================================================== -->

                    <div class="col-lg-6">

                        <div class="year-box">

                            <div class="year-header">

                                <div class="year-label">

                                    <div class="year-icon">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>

                                    Tahun 2026

                                </div>

                                <button type="button"
                                        class="btn btn-primary btn-upload"
                                        onclick="openUploadModal(
                                            <?= (int) $point->id ?>,
                                            <?= htmlspecialchars(
                                                json_encode($point->nama_point),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>,
                                            2026
                                        )">

                                    <i class="fas fa-upload mr-1"></i>

                                    Upload

                                </button>

                            </div>


                            <div class="year-files">

                                <?php if (!empty($files_2026)): ?>

                                    <?php foreach ($files_2026 as $file): ?>

                                        <?php
                                        $file_icon = upload_file_icon(
                                            $file->ekstensi
                                        );
                                        ?>

                                        <div class="file-item">

                                            <div class="file-icon <?= $file_icon['class'] ?>">

                                                <i class="fas <?= $file_icon['icon'] ?>"></i>

                                            </div>


                                            <div class="file-info">

                                                <div class="file-name"
                                                     title="<?= html_escape($file->nama_file_asli) ?>">

                                                    <?= html_escape(
                                                        $file->nama_file_asli
                                                    ) ?>

                                                </div>

                                                <div class="file-meta">

                                                    <?= upload_format_size(
                                                        $file->ukuran_file
                                                    ) ?>

                                                    <span class="mx-1">
                                                        &bull;
                                                    </span>

                                                    <?= date(
                                                        'd/m/Y H:i',
                                                        strtotime($file->uploaded_at)
                                                    ) ?>

                                                </div>

                                            </div>


                                            <div class="file-actions">

                                                <a href="<?= site_url('upload/download/' . $file->id) ?>"
                                                   class="btn btn-sm btn-outline-primary file-action"
                                                   title="Download">

                                                    <i class="fas fa-download"></i>

                                                </a>

                                                <a href="<?= site_url('upload/delete_file/' . $file->id) ?>"
                                                   class="btn btn-sm btn-outline-danger file-action"
                                                   title="Hapus"
                                                   onclick="return confirm('Hapus berkas ini?')">

                                                    <i class="fas fa-trash"></i>

                                                </a>

                                            </div>

                                        </div>

                                    <?php endforeach; ?>

                                <?php else: ?>

                                    <div class="empty-file">

                                        <i class="far fa-folder-open"></i>

                                        Belum ada berkas untuk tahun 2026.

                                    </div>

                                <?php endif; ?>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    <?php endforeach; ?>

<?php else: ?>

    <div class="card border-0 shadow-sm"
         style="border-radius:17px;">

        <div class="card-body text-center py-5">

            <div class="text-muted mb-3">

                <i class="far fa-folder-open fa-3x"></i>

            </div>

            <h6 class="font-weight-bold text-gray-700">
                Point dokumen tidak ditemukan
            </h6>

            <p class="small text-muted mb-0">
                Coba gunakan kata pencarian yang berbeda.
            </p>

        </div>

    </div>

<?php endif; ?>


</div>


<!-- =========================================================
     MODAL UPLOAD
========================================================= -->

<div class="modal fade upload-modal"
     id="uploadModal"
     tabindex="-1"
     role="dialog"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered"
         role="document">

        <form method="post"
              action=""
              enctype="multipart/form-data"
              id="uploadForm">

            <input type="hidden"
                   name="<?= $csrf_name ?>"
                   value="<?= $csrf_hash ?>">

            <input type="hidden"
                   name="tahun"
                   id="modalTahun"
                   value="">

            <div class="modal-content">


                <!-- HEADER -->

                <div class="modal-header">

                    <div class="modal-title">

                        <div class="modal-upload-icon">

                            <i class="fas fa-cloud-upload-alt"></i>

                        </div>

                        Upload Berkas

                    </div>

                    <button type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close">

                        <span aria-hidden="true">
                            &times;
                        </span>

                    </button>

                </div>


                <!-- BODY -->

                <div class="modal-body">

                    <div class="upload-point-preview mb-4">

                        <div class="upload-point-label">
                            Point Dokumen
                        </div>

                        <div class="upload-point-name"
                             id="modalPoint">

                            -

                        </div>

                    </div>


                    <div class="form-group">

                        <label>
                            Tahun Dokumen
                        </label>

                        <input type="text"
                               id="modalTahunText"
                               class="form-control upload-input"
                               value=""
                               readonly>

                    </div>


                    <div class="form-group">

                        <label>
                            Berkas
                            <span class="text-danger">*</span>
                        </label>

                        <input type="file"
                               name="berkas"
                               id="uploadFile"
                               class="upload-file-input"
                               required>

                        <div class="upload-file-help">

                            Format yang didukung:
                            PDF, Word, Excel, PowerPoint,
                            JPG, JPEG, PNG, GIF, ZIP, RAR.

                            Maksimal 50 MB.

                        </div>

                    </div>


                    <div class="form-group mb-0">

                        <label>
                            Keterangan
                        </label>

                        <textarea name="keterangan"
                                  class="form-control upload-textarea"
                                  rows="4"
                                  placeholder="Contoh: BKU Semester II 2025, dokumen asli, PDF hasil scan, dan sebagainya."></textarea>

                    </div>

                </div>


                <!-- FOOTER -->

                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-light btn-modal"
                            data-dismiss="modal">

                        Batal

                    </button>

                    <button type="submit"
                            class="btn btn-primary btn-modal"
                            id="btnUploadSubmit">

                        <i class="fas fa-upload mr-1"></i>

                        Upload Berkas

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>


<script>

/* =========================================================
   OPEN UPLOAD MODAL
========================================================= */

function openUploadModal(pointId, pointName, tahun)
{
    /*
    |--------------------------------------------------------------------------
    | Debug
    |--------------------------------------------------------------------------
    */

    console.log('Upload Point:', pointId);
    console.log('Upload Nama:', pointName);
    console.log('Upload Tahun:', tahun);


    /*
    |--------------------------------------------------------------------------
    | Isi informasi point
    |--------------------------------------------------------------------------
    */

    $('#modalPoint').text(
        pointId + '. ' + pointName
    );


    /*
    |--------------------------------------------------------------------------
    | Isi tahun
    |--------------------------------------------------------------------------
    */

    $('#modalTahun').val(
        tahun
    );

    $('#modalTahunText').val(
        tahun
    );


    /*
    |--------------------------------------------------------------------------
    | Reset file
    |--------------------------------------------------------------------------
    */

    $('#uploadFile').val('');

    $('#uploadForm textarea[name="keterangan"]').val('');


    /*
    |--------------------------------------------------------------------------
    | URL controller
    |--------------------------------------------------------------------------
    */

    var uploadUrl =
        '<?= site_url('upload/upload_file/') ?>'
        + pointId;

    $('#uploadForm').attr(
        'action',
        uploadUrl
    );


    /*
    |--------------------------------------------------------------------------
    | Tampilkan modal
    |--------------------------------------------------------------------------
    */

    $('#uploadModal').modal('show');
}


/* =========================================================
   VALIDASI FORM
========================================================= */

$('#uploadForm').on('submit', function(e)
{
    var fileInput = $('#uploadFile')[0];

    if (!fileInput.files.length) {

        e.preventDefault();

        alert('Silakan pilih berkas terlebih dahulu.');

        return false;
    }


    /*
    |--------------------------------------------------------------------------
    | Ukuran maksimal 50 MB
    |--------------------------------------------------------------------------
    */

    var maxSize = 50 * 1024 * 1024;

    if (fileInput.files[0].size > maxSize) {

        e.preventDefault();

        alert(
            'Ukuran file terlalu besar. Maksimal 50 MB.'
        );

        return false;
    }


    /*
    |--------------------------------------------------------------------------
    | Disable button agar tidak double submit
    |--------------------------------------------------------------------------
    */

    $('#btnUploadSubmit')
        .prop('disabled', true)
        .html(
            '<i class="fas fa-spinner fa-spin mr-1"></i> Mengupload...'
        );

    return true;
});


/* =========================================================
   RESET MODAL SAAT DITUTUP
========================================================= */

$('#uploadModal').on(
    'hidden.bs.modal',
    function()
    {
        $('#uploadForm').attr(
            'action',
            ''
        );

        $('#modalPoint').text('-');

        $('#modalTahun').val('');

        $('#modalTahunText').val('');

        $('#uploadFile').val('');

        $('#uploadForm textarea[name="keterangan"]')
            .val('');

        $('#btnUploadSubmit')
            .prop('disabled', false)
            .html(
                '<i class="fas fa-upload mr-1"></i> Upload Berkas'
            );
    }
);

</script>