<div class="container-fluid">

    <div class="card shadow-sm border-0">

        <div class="card-header bg-primary text-white">

            <h5 class="mb-0">
                <i class="fas fa-folder-plus mr-2"></i>
                <?= isset($point) ? 'Edit Point Dokumen' : 'Tambah Point Dokumen' ?>
            </h5>

        </div>

        <div class="card-body">

            <form method="post"
      action="<?= isset($point)
          ? site_url('upload/update_point/' . $point->id)
          : site_url('upload/simpan_point') ?>">

    <input type="hidden"
           name="<?= $this->security->get_csrf_token_name() ?>"
           value="<?= $this->security->get_csrf_hash() ?>">

                <div class="form-group">

                    <label class="font-weight-bold">
                        Nomor Point
                    </label>

                    <input type="number"
                           name="nomor"
                           class="form-control"
                           min="1"
                           value="<?= isset($point) ? $point->nomor : '' ?>"
                           required>

                </div>

                <div class="form-group">

                    <label class="font-weight-bold">
                        Nama Point
                    </label>

                    <textarea name="nama_point"
                              class="form-control"
                              rows="3"
                              required><?= isset($point) ? html_escape($point->nama_point) : '' ?></textarea>

                </div>

                <div class="form-group">

                    <label class="font-weight-bold">
                        Keterangan
                    </label>

                    <textarea name="keterangan"
                              class="form-control"
                              rows="4"
                              placeholder="Keterangan tambahan jika diperlukan..."><?= isset($point) ? html_escape($point->keterangan) : '' ?></textarea>

                </div>

                <div class="d-flex justify-content-between">

                    <a href="<?= site_url('upload') ?>"
                       class="btn btn-secondary">

                        <i class="fas fa-arrow-left mr-1"></i>
                        Kembali

                    </a>

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="fas fa-save mr-1"></i>
                        Simpan

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>