<div class="card card-orange card-outline shadow-sm">
    <div class="card-body bg-white">
        <div class="row">
            <div class="form-group col-md-6">
                <label>Kode Kupon</label>
                <input type="text" name="kode_kupon" value="{{ old('kode_kupon', $diskon->kode_kupon ?? '') }}" class="form-control">
            </div>
            <div class="form-group col-md-6">
                <label>Tipe Diskon</label>
                <select name="tipe_diskon" class="form-control">
                    <option value="persen" {{ old('tipe_diskon', $diskon->tipe_diskon ?? '') == 'persen' ? 'selected' : '' }}>Persen</option>
                    <option value="nominal" {{ old('tipe_diskon', $diskon->tipe_diskon ?? '') == 'nominal' ? 'selected' : '' }}>Nominal</option>
                </select>
            </div>

            <div class="form-group col-md-6">
                <label>Jumlah Diskon</label>
                <input type="number" name="jumlah_diskon" value="{{ old('jumlah_diskon', $diskon->jumlah_diskon ?? '') }}" class="form-control">
            </div>
            <div class="form-group col-md-6">
                <label>Minimal Belanja (Opsional)</label>
                <input type="number" name="minimal_belanja" value="{{ old('minimal_belanja', $diskon->minimal_belanja ?? '') }}" class="form-control">
            </div>

            <div class="form-group col-md-6">
                <label>Kategori (Opsional)</label>
                <select name="kategori_id" class="form-control">
                    <option value="">Pilih Kategori</option>
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id }}" {{ old('kategori_id', $diskon->kategori_id ?? '') == $kategori->id ? 'selected' : '' }}>{{ $kategori->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-6">
                <label>Produk (Opsional)</label>
                <select name="produk_id" class="form-control">
                    <option value="">Pilih Produk</option>
                    @foreach($produks as $produk)
                        <option value="{{ $produk->id }}" {{ old('produk_id', $diskon->produk_id ?? '') == $produk->id ? 'selected' : '' }}>{{ $produk->nama_produk }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-md-6">
                <label>Tanggal Kadaluarsa</label>
                <input type="date" name="tanggal_kadaluarsa" value="{{ old('tanggal_kadaluarsa', $diskon->tanggal_kadaluarsa ?? '') }}" class="form-control">
            </div>
        </div>

        <div class="text-right">
            <button class="btn btn-outline-success">
                <i class="fas fa-save mr-1"></i> Simpan
            </button>
        </div>
    </div>
</div>
