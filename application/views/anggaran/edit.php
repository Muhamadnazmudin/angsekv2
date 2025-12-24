<div class="container-fluid">

<h1 class="h3 mb-4 text-gray-800">Edit Anggaran</h1>

<div class="card shadow mb-4">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-primary">Form Edit Anggaran</h6>
    </div>

    <div class="card-body">

        <form method="post" action="<?= site_url('anggaran/update/'.$anggaran->id) ?>">

            <!-- CSRF -->
            <input type="hidden"
                    name="<?= $this->security->get_csrf_token_name(); ?>"
                    value="<?= $this->security->get_csrf_hash(); ?>">

            <ul class="nav nav-tabs" id="anggaranTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#tab1">1. Informasi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tab2">2. Rincian</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tab3">3. Alokasi Bulan</a>
                </li>
            </ul>

            <div class="tab-content pt-3">

                <!-- =======================
     TAB 1 — INFORMASI
     ======================= -->
<div class="tab-pane fade show active" id="tab1">

    <div class="form-row">

    <div class="form-group col-md-4">
        <label>Jurusan</label>
        <select name="jurusan_id" class="form-control" required>
            <?php foreach($jurusan as $j): ?>
                <option value="<?= $j->id ?>"
                    <?= $j->id == $anggaran->jurusan_id ? 'selected' : '' ?>>
                    <?= $j->nama ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group col-md-4">
        <label>SNP</label>
        <input class="form-control" id="snp" value="<?= $anggaran->snp ?>" readonly>
    </div>

    <div class="form-group col-md-4">
        <label>Komponen</label>
        <input class="form-control" id="komponen" value="<?= $anggaran->komponen ?>" readonly>
    </div>

</div>

<div class="form-row">
    <div class="form-group col-md-8">
        <label>Kegiatan / Uraian Kegiatan (SNP)</label>
        <select name="ref_snp_id" id="ref_snp_id" class="form-control" required>
            <option value="">-- Pilih Kegiatan --</option>
            <?php foreach ($ref_snp as $r): ?>
                <option value="<?= $r->id ?>"
                    data-snp="<?= $r->snp ?>"
                    data-komponen="<?= $r->komponen ?>"
                    <?= $r->id == $anggaran->ref_snp_id ? 'selected' : '' ?>>
                    <?= $r->uraian_kegiatan ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group col-md-4">
        <label>Kodering</label>
        <select name="kodering_id" id="kodering_id" class="form-control" required>
            <option value="">-- Pilih Kodering --</option>
            <?php foreach($kodering as $kd): ?>
                <option value="<?= $kd->id ?>"
                        <?= $kd->id == $anggaran->kodering_id ? 'selected' : '' ?>
                        data-kategori-id="<?= $kd->kategori_id ?>">
                    <?= $kd->kode ?> - <?= $kd->nama ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

<div class="form-group">
    <label>Jenis Belanja</label>
    <input type="text" id="jenis_belanja" class="form-control"
           value="<?= $anggaran->jenis_belanja_nama ?>" readonly>
</div>


                <!-- =======================
                     TAB 2 — RINCIAN
                     ======================= -->
                <div class="tab-pane fade" id="tab2">

                    <div class="form-row">

                        <div class="form-group col-md-8">
                            <label>Uraian</label>
                            <input name="uraian" class="form-control"
                                   value="<?= $anggaran->uraian ?>" required>
                        </div>

                        <div class="form-group col-md-2">
                            <label>Volume</label>
                            <input id="volume" name="volume"
                                   class="form-control text-right"
                                   value="<?= $anggaran->volume ?>" required>
                        </div>

                        <div class="form-group col-md-2">
                            <label>Satuan</label>
                            <input name="satuan" class="form-control"
                                   value="<?= $anggaran->satuan ?>">
                        </div>

                    </div>

                    <div class="form-row">

                        <div class="form-group col-md-4">
                            <label>Harga Satuan</label>
                            <input id="harga_satuan"
                                   name="harga_satuan"
                                   class="form-control text-right"
                                   value="<?= $anggaran->harga_satuan ?>" required>
                        </div>

                        <div class="form-group col-md-4">
                            <label>Total (otomatis)</label>
                            <input id="total_show"
                                   class="form-control text-right"
                                   readonly>
                        </div>

                        <div class="form-group col-md-4">
                            <label>Catatan</label>
                            <input name="catatan" class="form-control"
                                   value="<?= $anggaran->catatan ?>">
                        </div>

                    </div>

                </div>

                <!-- =======================
                     TAB 3 — ALOKASI
                     ======================= -->
                <div class="tab-pane fade" id="tab3">

                    <div class="table-responsive">
                        <table class="table table-bordered" style="min-width:900px;">
                            <thead class="thead-light">
                                <tr class="text-center">
                                    <th>Jan</th><th>Feb</th><th>Mar</th><th>Apr</th><th>Mei</th><th>Jun</th>
                                    <th>Jul</th><th>Agu</th><th>Sep</th><th>Okt</th><th>Nov</th><th>Des</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <?php
                                        $arr = ['jan','feb','mar','apr','mei','jun','jul','agu','sep','okt','nov','des'];
                                        foreach ($arr as $m):
                                    ?>
                                        <td><input class="form-control text-right"
                                                   name="<?= $m ?>"
                                                   value="<?= $anggaran->$m ?>"></td>
                                    <?php endforeach; ?>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="form-group">
                        <label>Total Alokasi</label>
                        <input id="sum_alokasi" class="form-control text-right" readonly>
                    </div>

                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-primary">Update</button>
                <a href="<?= site_url('anggaran') ?>" class="btn btn-secondary">Kembali</a>
            </div>
            
<script>
// Ketika dropdown kegiatan berubah → isi SNP & komponen otomatis
// Autofill SNP & Komponen saat ganti kegiatan
document.getElementById('ref_snp_id').addEventListener('change', function() {
    let opt = this.selectedOptions[0];
    document.getElementById('snp').value = opt.getAttribute('data-snp') || '';
    document.getElementById('komponen').value = opt.getAttribute('data-komponen') || '';
});

// Autofill Jenis Belanja dari Kodering
document.getElementById('kodering_id').addEventListener('change', function() {
    let kategoriId = this.selectedOptions[0].getAttribute('data-kategori-id');

    if (!kategoriId) {
        document.getElementById('jenis_belanja').value = "";
        return;
    }

    fetch("<?= site_url('Anggaran/get_jenis_belanja/') ?>" + kategoriId)
        .then(r => r.json())
        .then(res => {
            document.getElementById('jenis_belanja').value = res.jenis_belanja || '';
        });
});

</script>

<script>
document.querySelector("form").addEventListener("submit", function(e) {

    function num(v){
        v = String(v).replace(/[^0-9.\-]/g,'');
        return parseFloat(v) || 0;
    }

    let volume = num(document.getElementById('volume').value);
    let harga = num(document.getElementById('harga_satuan').value);
    let total = volume * harga;

    let months = ['jan','feb','mar','apr','mei','jun','jul','agu','sep','okt','nov','des'];
    let alokasi_total = 0;

    months.forEach(m=>{
        let el = document.getElementsByName(m)[0];
        alokasi_total += num(el.value);
    });

    if (alokasi_total > total) {
        e.preventDefault();

        Swal.fire({
            icon: 'error',
            title: 'Total Alokasi Melebihi Batas!',
            html:
                "<b>Total Alokasi:</b> " + alokasi_total.toLocaleString() + "<br>" +
                "<b>Total Anggaran:</b> " + total.toLocaleString(),
            confirmButtonColor: '#d33',
            confirmButtonText: 'Mengerti'
        });

        return false;
    }

});
</script>

        </form>

    </div>
</div>

</div>


<script>
(function(){
    function num(v) {
        v = String(v).replace(/[^0-9.\-]/g,'');
        return parseFloat(v) || 0;
    }

    function calcTotal() {
        let v = num(document.getElementById('volume').value);
        let h = num(document.getElementById('harga_satuan').value);
        let tot = v * h;
        document.getElementById('total_show').value = tot.toLocaleString();
    }

    function calcAlokasi() {
        let total = 0;
        const arr = ['jan','feb','mar','apr','mei','jun','jul','agu','sep','okt','nov','des'];
        arr.forEach(m=>{
            let el = document.getElementsByName(m)[0];
            total += num(el.value);
        });
        document.getElementById('sum_alokasi').value = total.toLocaleString();
    }

    document.getElementById('volume').addEventListener('input', calcTotal);
    document.getElementById('harga_satuan').addEventListener('input', calcTotal);

    ['jan','feb','mar','apr','mei','jun','jul','agu','sep','okt','nov','des'].forEach(m=>{
        document.getElementsByName(m)[0].addEventListener('input', calcAlokasi);
    });

    calcTotal();
    calcAlokasi();
})();
</script>
