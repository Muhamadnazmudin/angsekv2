<style>
   /* ===== Sidebar Scroll Independent ===== */
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 250px; /* Default SB Admin 2 width */
    height: 100vh;
    overflow-y: auto !important;
    overflow-x: hidden;
    z-index: 999;
}

/* ===== Content geser ke kanan agar tidak tertutup sidebar ===== */
#content-wrapper {
    margin-left: 250px;  /* match sidebar width */
    height: 100vh;
    overflow-y: auto;     /* content scroll */
    overflow-x: hidden;
}

#content {
    padding-bottom: 80px; /* biar ada ruang di bawah */
}

/* ===== Body tidak ikut scroll ===== */
body {
    overflow: hidden !important;
}
</style>

<?php $role = $this->session->userdata('role_id'); ?>

<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Logo / Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= site_url('dashboard') ?>">
        <div class="sidebar-brand-text mx-3">ANGSEK</div>
    </a>

    <?php
    // ===========================
    //  SIDEBAR UNTUK JURUSAN
    // ===========================
    if ($role == 3): ?>

        <hr class="sidebar-divider my-0">

        <!-- DASHBOARD -->
        <li class="nav-item">
            <a class="nav-link" href="<?= site_url('dashboard') ?>">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <hr class="sidebar-divider">

        <!-- ANGGARAN -->
        <div class="sidebar-heading">Anggaran</div>

        <li class="nav-item">
            <a class="nav-link" href="<?= site_url('anggaran') ?>">
                <i class="fas fa-file-invoice-dollar"></i>
                <span>Input Anggaran</span>
            </a>
        </li>
     
        <hr class="sidebar-divider">

        <!-- LAPORAN -->
        <div class="sidebar-heading">Laporan</div>

        <li class="nav-item">
            <a class="nav-link" href="<?= site_url('laporan_rkas') ?>">
                <i class="fas fa-book"></i>
                <span>Laporan RKAS</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="<?= site_url('laporan/bulan') ?>">
                <i class="fas fa-calendar-alt"></i>
                <span>Rekap Per Bulan</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="<?= site_url('laporan/tahun') ?>">
                <i class="fas fa-chart-line"></i>
                <span>Rekap Tahunan</span>
            </a>
        </li>

        <hr class="sidebar-divider">

        <!-- LOGOUT -->
        <li class="nav-item">
            <a class="nav-link" href="<?= site_url('auth/logout') ?>">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </li>

        <hr class="sidebar-divider d-none d-md-block">

</ul>

<?php return; endif; ?>




<!-- ============================================================
     SIDEBAR UNTUK ADMIN / OPERATOR (ROLE != JURUSAN)
=============================================================== -->

<hr class="sidebar-divider my-0">

<!-- DASHBOARD -->
<li class="nav-item">
    <a class="nav-link" href="<?= site_url('dashboard') ?>">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Dashboard</span>
    </a>
</li>

<hr class="sidebar-divider">

<!-- PENGGUNA -->
<div class="sidebar-heading">Manajemen Sistem</div>

<li class="nav-item">
    <a class="nav-link" href="<?= site_url('pengguna') ?>">
        <i class="fas fa-users"></i>
        <span>Pengguna</span>
    </a>
</li>

<hr class="sidebar-divider">

<!-- MASTER DATA -->
<div class="sidebar-heading">Master Data</div>

<li class="nav-item">
    <a class="nav-link" href="<?= site_url('jurusan') ?>">
        <i class="fas fa-school"></i>
        <span>Jurusan</span>
    </a>
</li>

<?php if ($role == 1): ?>
<li class="nav-item">
    <a class="nav-link" href="<?= site_url('users'); ?>">
        <i class="fas fa-users-cog"></i>
        <span>Users</span>
    </a>
</li>
<?php endif; ?>

<li class="nav-item">
    <a class="nav-link" href="<?= site_url('kegiatan') ?>">
        <i class="fas fa-tasks"></i>
        <span>Kegiatan</span>
    </a>
</li>

<li class="nav-item">
    <a class="nav-link" href="<?= site_url('kategori') ?>">
        <i class="fas fa-tags"></i>
        <span>Kategori Kodering</span>
    </a>
</li>

<li class="nav-item">
    <a class="nav-link" href="<?= site_url('kodering') ?>">
        <i class="fas fa-list-alt"></i>
        <span>Kodering</span>
    </a>
</li>

<hr class="sidebar-divider">

<!-- DATA ANGGARAN -->
<div class="sidebar-heading">Anggaran</div>
<li class="nav-item">
    <a class="nav-link" href="<?= site_url('ref_snp'); ?>">
        <i class="fas fa-book"></i>
        <span>Referensi SNP</span>
    </a>
</li>

<li class="nav-item">
    <a class="nav-link" href="<?= site_url('anggaran') ?>">
        <i class="fas fa-file-invoice-dollar"></i>
        <span>Input Anggaran</span>
    </a>
</li>
   <li class="nav-item">
    <a class="nav-link" href="<?= site_url('pagu') ?>">
        <i class="fas fa-wallet"></i>
        <span>Pagu Anggaran</span>
    </a>
</li>

<li class="nav-item">
    <a class="nav-link" href="<?= site_url('anggaran_import') ?>">
        <i class="fas fa-upload"></i>
        <span>Import CSV</span>
    </a>
</li>

<hr class="sidebar-divider">

<!-- LAPORAN -->
<div class="sidebar-heading">Laporan</div>

<li class="nav-item">
    <a class="nav-link" href="<?= site_url('laporan_rkas') ?>">
        <i class="fas fa-book"></i>
        <span>Laporan RKAS</span>
    </a>
</li>

<li class="nav-item">
    <a class="nav-link" href="<?= site_url('laporan/bulan') ?>">
        <i class="fas fa-calendar-alt"></i>
        <span>Rekap Per Bulan</span>
    </a>
</li>

<li class="nav-item">
    <a class="nav-link" href="<?= site_url('laporan/tahun') ?>">
        <i class="fas fa-chart-line"></i>
        <span>Rekap Tahunan</span>
    </a>
</li>

<hr class="sidebar-divider">

<!-- LOGOUT -->
<li class="nav-item">
    <a class="nav-link" href="<?= site_url('auth/logout') ?>">
        <i class="fas fa-sign-out-alt"></i>
        <span>Logout</span>
    </a>
</li>

<hr class="sidebar-divider d-none d-md-block">

</ul>
