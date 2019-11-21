 <div class="block">
    <div class="block-content">
        <form action="be_forms_elements_material.php" method="post" onsubmit="return false;">
            <div class="form-group row">
                <div class="col-md-12">
                    <div class="form-material">
                        <input type="datetime" class="form-control" id="material-text" name="nama_dokter">
                        <label for="material-text" id="">Nama Dokter</label>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-12">
                    <div class="form-material">
                        <input type="date" class="form-control" id="material-text" name="jadwal_periksa">
                        <label for="material-text" id="">Jadwal Periksa</label>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-12">
                    <div class="form-material">
                        <input type="time" class="form-control" id="material-text" name="jadwal_jam">
                        <label for="material-text" id="">Jadwal Jam</label>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-9">
                    <div class="form-material">
                        <select class="form-control" id="material-select" name="nama_klinik">
                            <option>...</option>
                            <option value="Jantung">Jantung</option>
                            <option value="Paru-paru">Paru-paru</option>
                            <option value="Janin">Janin</option>
                        </select>
                        <label for="material-select">Spesialis</label>
                    </div>
                </div>

            </form>
        </div>
    </div>