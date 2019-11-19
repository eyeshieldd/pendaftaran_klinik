 <div class="block">
    <div class="block-content">
        <form action="be_forms_elements_material.php" method="post" onsubmit="return false;">
            <div class="form-group row">
                <div class="col-md-12">
                    <div class="form-material floating">
                        <input type="datetime" class="form-control" id="material-text" name="nama_do">
                        <label for="material-text" id="">Nama Akun</label>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-6">
                    <div class="form-material floating">
                        <select class="form-control" id="material-select" name="material-select">
                            <option></option>
                            <option value="1">Akun Piutang</option>
                            <option value="2">Aktiva Lancar Lainya</option>
                            <option value="3">Kas & Bank</option>
                            <option value="4">Persediaan</option>
                            <option value="5">Aktiva Tetap</option>
                            <option value="6">Aktiva Lainya</option>
                            <option value="7">Depresiasi & Amortisasi</option>
                            <option value="8">Akun Hutang</option>
                            <option value="9">Kewajiban Lancar Lainya</option>
                            <option value="10">Kewajiban Jangka Panjang</option>
                            <option value="11">Ekuitas</option>
                            <option value="12">Pendapatan</option>
                            <option value="13">Pendapatan Lainya</option>
                            <option value="14">Harga Pokok Penjualan</option>
                            <option value="15">Beban</option>
                            <option value="16">Beban Lainya</option>
                            
                        </select>
                        <label for="material-select">Kategori Akun</label>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-material floating">
                        <input type="text" class="form-control" id="material-gridl" name="material-gridl" placeholder="">
                        <label for="material-gridl">Kode Akun</label>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-12">
                    <div class="form-material floating">
                        <textarea class="form-control" id="material-textarea-small" name="material-textarea-small" rows="3" placeholder=""></textarea>
                        <label for="material-textarea-small">Deskripsi</label>
                    </div>
                </div>
            </div>
        </form>
    </div>
 </div>