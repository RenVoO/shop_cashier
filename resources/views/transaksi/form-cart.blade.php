{{-- Total Atas --}}
<div class="card card-orange card-outline mb-3 shadow-sm">
    <div class="card-body bg-white">
        <h3 class="m-0 text-right">Rp <span id="totalJumlah">0</span> ,-</h3>
    </div>
</div>

{{-- Form Transaksi --}}
<form action="{{ route('transaksi.store') }}" method="POST" class="card card-orange card-outline shadow-sm">
    @csrf
    <div class="card-body bg-white">
        {{-- Informasi Tanggal --}}
        <div class="mb-3 text-right">
            <strong>Tanggal :</strong> {{ $tanggal }}
        </div>

        {{-- Pelanggan dan Kasir --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Nama Pelanggan</label>
                <input type="text" id="namaPelanggan" class="form-control @error('pelanggan_id') is-invalid @enderror" disabled>
                <input type="hidden" name="pelanggan_id" id="pelangganId">
                @error('pelanggan_id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="col-md-6">
                <label>Nama Kasir</label>
                <input type="text" class="form-control bg-light" value="{{ $nama_kasir }}" disabled>
            </div>
        </div>

        {{-- Tabel Produk --}}
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead class="thead-light">
                    <tr>
                        <th>Nama Produk</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th>Sub Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="resultCart">
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Rincian Total --}}
        <div class="row mt-4">
            <div class="col-md-6 offset-md-6">
                <table class="table table-sm table-bordered">
                    <tr>
                        <th>Total</th>
                        <td class="text-right" id="subtotal">0</td>
                    </tr>
                    <tr>
                        <th>Pajak 10%</th>
                        <td class="text-right" id="taxAmount">0</td>
                    </tr>
                    <tr>
                        <th>Diskon Kupon</th>
                        <td class="text-right" id="discount">0</td>
                    </tr>
                    <tr>
                        <th>Total Bayar</th>
                        <td class="text-right font-weight-bold text-success" id="total">0</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Input Kupon & Cash --}}
        <div class="row mt-2 mb-4">
            <div class="col-md-6 offset-md-6">
                <div class="input-group mb-2">
                    <input type="text" id="kodeKupon" class="form-control" placeholder="Kode Kupon">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-outline-info" onclick="terapkanKupon()">
                            <i class="fas fa-tag mr-1"></i> Terapkan Kupon
                        </button>
                    </div>
                </div>
                <div id="alertKupon" class="text-danger mb-2"></div>

                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-light">Cash</span>
                    </div>
                    <input type="text" name="cash" class="form-control @error('cash') is-invalid @enderror" placeholder="Jumlah Cash" value="{{ old('cash') }}">
                    @error('cash')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <input type="hidden" name="total_bayar" id="totalBayar" />
            </div>
        </div>

        {{-- Tombol Aksi --}}
        <div class="d-flex justify-content-between">
            <div>
                <a href="{{ route('transaksi.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Ke Transaksi
                </a>
                <a href="{{ route('cart.clear') }}" class="btn btn-outline-danger ml-2">
                    <i class="fas fa-trash mr-1"></i> Kosongkan
                </a>
            </div>
            <button type="submit" class="btn btn-outline-success">
                <i class="fas fa-money-bill-wave mr-1"></i> Bayar Transaksi
            </button>
        </div>
    </div>
</form>



@push('scripts')
<script>
    $(function() {
        fetchCart();

        $('#formCariPelanggan').submit(function(e) {
            e.preventDefault();
            const search = $('#searchPelanggan').val();
            if (search.length >= 3) {
                fetchCariPelanggan(search);
            }
        });
    });

    function terapkanKupon() {
    const kode = $('#kodeKupon').val();

        $.ajax({
            type: "POST",
            url: "/cart/apply-coupon",
            data: {
                kode_kupon: kode,
                _token: "{{ csrf_token() }}"
            },
            dataType: "json",
            success: function(res) {
                $('#alertKupon').html('<span class="text-success">' + res.message + '</span>');
                fetchCart();
            },
            error: function(err) {
                const msg = err.responseJSON?.error || 'Gagal menerapkan kupon.';
                $('#alertKupon').html(msg);
            }
        });
    }

    function fetchCart() {
        $.getJSON("/cart", function(response) {
            $('#resultCart').empty();

            const {
                items,
                subtotal,
                tax_amount,
                total,
                diskon,
                extra_info
            } = response;

            $('#subtotal').html(rupiah(subtotal));
            $('#taxAmount').html(rupiah(tax_amount));
            $('#discount').html(rupiah(diskon ?? 0));
            $('#total, #totalJumlah').html(rupiah(total));
            $('#totalBayar').val(total);

            if (Object.keys(items).length === 0) {
                $('#resultCart').html('<tr><td colspan="6" class="text-center">Tidak ada data.</td></tr>');
            } else {
                for (const key in items) {
                    addRow(items[key]);
                }
            }

            if (!Array.isArray(extra_info)) {
                const { id, nama } = extra_info.pelanggan;
                $('#namaPelanggan').val(nama);
                $('#pelangganId').val(id);
            }
        });
    }


    function fetchCariPelanggan(search) {
        $.getJSON("/transaksi/pelanggan", { search: search }, function(response) {
            $('#resultPelanggan').html('');
            response.forEach(item => {
                addResultPelanggan(item);
            });
        });
    }

    function addResultPelanggan(item) {
        const { id, nama } = item;
        const btn = `<button type="button" class="btn btn-xs btn-success" onclick="addPelanggan(${id})">Pilih</button>`;
        const row = `<tr>
            <td>${nama}</td>
            <td class="text-right">${btn}</td>
        </tr>`;
        $('#resultPelanggan').append(row);
    }

    function addPelanggan(id) {
        $.post("/transaksi/pelanggan", { id: id }, function(response) {
            fetchCart();
        }, "json");
    }

    function addRow(item) {
        const {
            hash,
            title,
            quantity,
            price,
            total_price,
            options
        } = item;

        const { diskon, harga_produk } = options;
        const nilai_diskon = diskon ? `(-${diskon}%)`:'';

        const btn = `
            <button type="button" class="btn btn-xs btn-success mr-2" onclick="ePut('${hash}', 1)">
                <i class="fas fa-plus"></i>
            </button>
            <button type="button" class="btn btn-xs btn-primary mr-2" onclick="ePut('${hash}', -1)">
                <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-xs btn-danger" onclick="eDel('${hash}')">
                <i class="fas fa-times"></i>
            </button>
        `;


        const row = `<tr>
            <td>${title}</td>
            <td>${quantity}</td>
            <td>${rupiah(price)}${nilai_diskon}</td>
            <td>${rupiah(total_price)}</td>
            <td>${btn}</td>
        </tr>`;

        $('#resultCart').append(row);
    }

    function rupiah(number) {
        return new Intl.NumberFormat("id-ID").format(number);
    }

    function ePut(hash, qty) {
        $.ajax({
            type: "PUT",
            url: "/cart/" + hash,
            data: { qty: qty },
            dataType: "json",
            success: function(response) {
                fetchCart();
            }
        });
    }

    function eDel(hash) {
        $.ajax({
            type: "DELETE",
            url: "/cart/" + hash,
            dataType: "json",
            success: function(response) {
                fetchCart();
            }
        });
    }
</script>
@endpush
