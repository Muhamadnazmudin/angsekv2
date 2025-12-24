<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Pagu Anggaran</h1>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
    <?php endif; ?>

    <!-- FORM TAMBAH -->
    <div class="card shadow mb-4">
        <div class="card-header font-weight-bold">Tambah Pagu Anggaran</div>
        <div class="card-body">
            <form method="post" action="<?= site_url('pagu/tambah') ?>">
    <input type="hidden"
           name="<?= $this->security->get_csrf_token_name(); ?>"
           value="<?= $this->security->get_csrf_hash(); ?>">

                <div class="row">
                    <div class="col-md-3">
                        <input type="number" name="tahun" class="form-control" placeholder="Tahun" required>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="nominal" class="form-control" placeholder="Nominal (Rp)" required>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-primary btn-block">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- TABEL -->
    <div class="card shadow">
        <div class="card-body table-responsive">
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr class="text-center">
                        <th width="50">No</th>
                        <th>Tahun</th>
                        <th>Nominal</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no=1; foreach ($pagu as $p): ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td class="text-center"><?= $p->tahun ?></td>
                        <td class="text-right">Rp <?= number_format($p->nominal,0,",",".") ?></td>
                        <td class="text-center">
                            <a href="#" data-toggle="modal" data-target="#edit<?= $p->id ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="<?= site_url('pagu/hapus/'.$p->id) ?>" class="btn btn-sm btn-danger"
                               onclick="return confirm('Hapus pagu anggaran?')">Hapus</a>
                        </td>
                    </tr>

                    <!-- MODAL EDIT -->
                    <div class="modal fade" id="edit<?= $p->id ?>">
                        <div class="modal-dialog">
                            <form method="post" action="<?= site_url('pagu/edit/'.$p->id) ?>" class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Pagu <?= $p->tahun ?></h5>
                                    <button class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <input type="text" name="nominal" class="form-control"
                                           value="<?= number_format($p->nominal,0,'','.') ?>" required>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
