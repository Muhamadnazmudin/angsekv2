<?php
?>

<?php if ($this->session->flashdata('success')): ?>
<div class="container-fluid">
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <?= $this->session->flashdata('success') ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
</div>
<?php endif; ?>

<?php if ($this->session->flashdata('error')): ?>
<div class="container-fluid">
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?= $this->session->flashdata('error') ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
</div>
<?php endif; ?>


<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800">Data Anggaran</h1>

  <div class="card shadow mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h6 class="m-0 font-weight-bold text-primary">Daftar Anggaran</h6>

      <div>

        <!-- Tombol Download Template -->
        <a href="<?= site_url('anggaran/download_template') ?>" 
           class="btn btn-info btn-sm mr-2">
          <i class="fas fa-download"></i> Download Template
        </a>

        <!-- Tombol Import Anggaran -->
        <button class="btn btn-success btn-sm mr-2" data-toggle="modal" data-target="#importModal">
          <i class="fas fa-upload"></i> Import Anggaran
        </button>

        <!-- Tombol Tambah -->
        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addModal">
          <i class="fas fa-plus"></i> Tambah Anggaran
        </button>

      </div>
    </div>

    <div class="card-body">

      <!-- responsive horizontal scroll -->
      <div class="table-responsive" style="overflow-x:auto;">
        <table class="table table-bordered table-striped" id="anggaranTable" style="min-width:1200px;">
          <thead>
            <tr>
              <th width="40">#</th>
              <th>Jurusan</th>
              <th>Kegiatan</th>
              <th>Kodering</th>
              <th>Jenis Belanja</th>
              <th>Uraian</th>
              <th>Volume</th>
              <th>Satuan</th>
              <th>Harga Satuan</th>
              <th>Total</th>
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
              <th>Catatan</th>
              <th width="120">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($anggaran)): $no=1; foreach($anggaran as $a): ?>
              <tr>
                <td><?= $no++; ?></td>
                <td><?= html_escape($a->jurusan_nama) ?></td>
                <td><?= html_escape($a->uraian_kegiatan) ?></td>
                <td><?= html_escape($a->kode_nama) ?></td>
                <td><?= html_escape($a->jenis_belanja_nama) ?></td>
                <td><?= html_escape($a->uraian) ?></td>
                <td class="text-right"><?= (float)$a->volume ?></td>
                <td><?= html_escape($a->satuan) ?></td>
                <td class="text-right"><?= number_format($a->harga_satuan,0,',','.') ?></td>
                <td class="text-right"><?= number_format(($a->volume*$a->harga_satuan),0,',','.') ?></td>
                <td class="text-right"><?= number_format($a->jan,0,',','.') ?></td>
                <td class="text-right"><?= number_format($a->feb,0,',','.') ?></td>
                <td class="text-right"><?= number_format($a->mar,0,',','.') ?></td>
                <td class="text-right"><?= number_format($a->apr,0,',','.') ?></td>
                <td class="text-right"><?= number_format($a->mei,0,',','.') ?></td>
                <td class="text-right"><?= number_format($a->jun,0,',','.') ?></td>
                <td class="text-right"><?= number_format($a->jul,0,',','.') ?></td>
                <td class="text-right"><?= number_format($a->agu,0,',','.') ?></td>
                <td class="text-right"><?= number_format($a->sep,0,',','.') ?></td>
                <td class="text-right"><?= number_format($a->okt,0,',','.') ?></td>
                <td class="text-right"><?= number_format($a->nov,0,',','.') ?></td>
                <td class="text-right"><?= number_format($a->des,0,',','.') ?></td>
                <td><?= html_escape($a->catatan) ?></td>
                <td>
                  <a class="btn btn-warning btn-sm" href="<?= site_url('anggaran/edit/'.$a->id) ?>"><i class="fas fa-edit"></i></a>
                  <a class="btn btn-danger btn-sm" href="<?= site_url('anggaran/delete/'.$a->id) ?>" onclick="return confirm('Hapus item anggaran?')"><i class="fas fa-trash"></i></a>
                </td>
              </tr>
            <?php endforeach; else: ?>
              <tr><td colspan="24" class="text-center">Belum ada data anggaran.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

    </div>
  </div>
</div>


<!-- ============================
     Modal: Tambah (3-tab)
     ============================ -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <form method="post" action="<?= site_url('anggaran/tambah') ?>">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Item Anggaran</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body">
          <!-- Nav tabs -->
          <ul class="nav nav-tabs" id="anggaranTab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="tab-basic-tab" data-toggle="tab" href="#tab-basic" role="tab">1. Informasi</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="tab-detail-tab" data-toggle="tab" href="#tab-detail" role="tab">2. Rincian</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="tab-alokasi-tab" data-toggle="tab" href="#tab-alokasi" role="tab">3. Alokasi Bulanan</a>
            </li>
          </ul>

          <div class="tab-content pt-3">
            <!-- TAB 1 INFORMASI -->
            <div class="tab-pane fade show active" id="tab-basic" role="tabpanel">
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label>Jurusan</label>
                  <select name="jurusan_id" class="form-control" required>
                    <option value="">-- Pilih Jurusan --</option>
                    <?php foreach($jurusan as $j): ?>
                      <option value="<?= $j->id ?>"><?= html_escape($j->nama) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <!-- SNP -->
<div class="form-group col-md-2">
  <label>SNP</label>
  <input type="text" id="snp_show" class="form-control" readonly>
</div>

<!-- Komponen -->
<div class="form-group col-md-3">
  <label>Komponen</label>
  <input type="text" id="komponen_show" class="form-control" readonly>
</div>

<!-- Kegiatan dari ref_snp -->
<div class="form-group col-md-5">
  <label>Kegiatan / Uraian Kegiatan (berdasarkan SNP)</label>
  <select name="ref_snp_id" id="ref_snp_id" class="form-control" required>
    <option value="">-- Pilih Kegiatan --</option>
    <?php foreach($ref_snp as $r): ?>
      <option 
        value="<?= $r->id ?>"
        data-snp="<?= html_escape($r->snp) ?>"
        data-komponen="<?= html_escape($r->komponen) ?>"
      >
        <?= html_escape($r->uraian_kegiatan) ?>
      </option>
    <?php endforeach; ?>
  </select>
</div>


                <div class="form-group col-md-4">
                  <label>Kodering</label>
                  <select name="kodering_id" id="kodering_id" class="form-control" required>
                    <option value="">-- Pilih Kodering --</option>
                    <?php foreach($kodering as $kd): ?>
                      <option 
    value="<?= $kd->id ?>" 
    data-kategori-id="<?= $kd->kategori_id ?>"
>
    <?= $kd->kode ?> - <?= $kd->nama ?>
</option>

                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="form-group col-md-4">
  <label>Jenis Belanja</label>
  <input type="text" id="jenis_belanja" name="jenis_belanja" class="form-control" readonly>

</div>

              </div>
            </div>

            <!-- TAB 2 RINCIAN -->
            <div class="tab-pane fade" id="tab-detail" role="tabpanel">
              <div class="form-row">
                <div class="form-group col-md-8">
                  <label>Uraian</label>
                  <input name="uraian" class="form-control" required>
                </div>

                <div class="form-group col-md-2">
                  <label>Volume</label>
                  <input name="volume" id="volume" class="form-control text-right" value="1" required>
                </div>

                <div class="form-group col-md-2">
                  <label>Satuan</label>
                  <input name="satuan" id="satuan" class="form-control" value="pcs">
                </div>
              </div>

              <div class="form-row">
                <div class="form-group col-md-4">
                  <label>Harga Satuan</label>
                  <input name="harga_satuan" id="harga_satuan" class="form-control text-right" value="0" required>
                </div>

                <div class="form-group col-md-4">
                  <label>Total (Volume x Harga)</label>
                  <input name="total_show" id="total_show" class="form-control text-right" readonly>
                </div>

                <div class="form-group col-md-4">
                  <label>Catatan</label>
                  <input name="catatan" class="form-control">
                </div>
              </div>
            </div>

            <!-- TAB 3 ALOKASI -->
            <div class="tab-pane fade" id="tab-alokasi" role="tabpanel">
              <div class="table-responsive">
                <table class="table table-sm table-bordered" style="min-width:900px;">
                  <thead class="thead-light">
                    <tr class="text-center">
                      <th>Jan</th><th>Feb</th><th>Mar</th><th>Apr</th><th>Mei</th><th>Jun</th>
                      <th>Jul</th><th>Agu</th><th>Sep</th><th>Okt</th><th>Nov</th><th>Des</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <?php
                        $months = ['jan','feb','mar','apr','mei','jun','jul','agu','sep','okt','nov','des'];
                        foreach ($months as $m) {
                          echo '<td><input name="'.$m.'" class="form-control text-right" value="0"></td>';
                        }
                      ?>
                    </tr>
                  </tbody>
                </table>
              </div>

              <div class="form-group mt-2">
                <label>Jumlah Total Alokasi (otomatis)</label>
                <input id="sum_alokasi" class="form-control text-right" readonly>
              </div>
            </div>

          </div> <!-- END tab-content -->
        </div> <!-- END modal-body -->

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- IMPORT Modal (simple) -->
<div class="modal fade" id="importModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post" enctype="multipart/form-data" action="<?= site_url('anggaran/import') ?>">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
        <div class="modal-header"><h5 class="modal-title">Import Anggaran (.xlsx)</h5></div>
        <div class="modal-body">
          <div class="form-group">
            <label>Pilih File Excel (.xlsx)</label>
            <input type="file" name="file_excel" class="form-control" accept=".xls,.xlsx" required>
          </div>
          <p class="small text-muted">Gunakan template import resmi (download di menu Kodering / atau saya buatkan).</p>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary">Import</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- JS: otomatis hitung total dan jumlah alokasi -->
<script>
(function(){
  function numberOnly(val){
    if (val === null || val === undefined) return 0;
    val = String(val).replace(/[^0-9\.\-]/g,'');
    return parseFloat(val) || 0;
  }

  // hitung total saat volume/harga berubah
  function recalcTotal(){
    var v = numberOnly(document.getElementById('volume').value);
    var h = numberOnly(document.getElementById('harga_satuan').value);
    var tot = v * h;
    document.getElementById('total_show').value = tot.toLocaleString('en-US', {maximumFractionDigits:2});
  }

  document.getElementById('volume').addEventListener('input', recalcTotal);
  document.getElementById('harga_satuan').addEventListener('input', recalcTotal);

  // hitung sum alokasi
  function recalcAlokasi(){
    var sum = 0;
    var months = ['jan','feb','mar','apr','mei','jun','jul','agu','sep','okt','nov','des'];
    months.forEach(function(m){
      var el = document.getElementsByName(m)[0];
      if (!el) return;
      sum += numberOnly(el.value);
      // format number while typing (optional)
      // el.value = numberOnly(el.value).toLocaleString('en-US', {maximumFractionDigits:2});
    });
    var s = document.getElementById('sum_alokasi');
    if (s) s.value = sum.toLocaleString('en-US', {maximumFractionDigits:2});
  }

  var monthsInputs = ['jan','feb','mar','apr','mei','jun','jul','agu','sep','okt','nov','des'];
  monthsInputs.forEach(function(name){
    var el = document.getElementsByName(name)[0];
    if (el) el.addEventListener('input', recalcAlokasi);
  });

  // inisialisasi
  recalcTotal();
  recalcAlokasi();

})();
</script>
<script>
document.getElementById('kodering_id').addEventListener('change', function() {
    let kategoriId = this.selectedOptions[0].getAttribute('data-kategori-id');

    if (!kategoriId) {
        document.getElementById('jenis_belanja').value = "";
        return;
    }

    fetch("<?= site_url('Anggaran/get_jenis_belanja/') ?>" + kategoriId)
        .then(res => res.json())
        .then(data => {
            document.getElementById('jenis_belanja').value = data.jenis_belanja || "";
        });
});
</script>
<script>
// Autofill SNP & Komponen
document.getElementById('ref_snp_id').addEventListener('change', function () {
    let opt = this.selectedOptions[0];

    document.getElementById('snp_show').value =
        opt.getAttribute('data-snp') || '';

    document.getElementById('komponen_show').value =
        opt.getAttribute('data-komponen') || '';
});
</script>



