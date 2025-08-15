<form action="" method="get" id="formCariProduk">
    <div class="input-group">
        <input type="text" class="form-control" placeholder="Nama Produk" id="searchProduk">
        <div class="input-group-append">
            <button type="submit" class="btn btn-primary">
                Cari
            </button>
        </div>
    </div>
</form>

<!-- Modal Input Quantity -->
<div class="modal fade" id="modalQuantity" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title">Masukkan Quantity</h6>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <input type="number" id="inputQuantity" class="form-control" min="1" value="1">
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary btn-sm" id="btnAddToCart">Tambah</button>
            </div>
        </div>
    </div>
</div>

<table class="table table-sm mt-3">
    <thead>
        <tr>
            <th colspan="2" class="border-0">Hasil Pencarian :</th>
        </tr>
    </thead>
    <tbody id="resultProduk"></tbody>
</table>

@push('scripts')
<script>
    let selectedKodeProduk = null;

    $(function() {
        $('#formCariProduk').submit(function(e) {
            e.preventDefault();
            const search = $('#searchProduk').val();
            if (search.length >= 3) {
                fetchCariProduk(search);
            }
        });

        // Klik tombol "Tambah" di modal
        $('#btnAddToCart').click(function() {
            const qty = parseInt($('#inputQuantity').val());
            if (!qty || qty < 1) {
                alert('Quantity minimal 1');
                return;
            }
            if (!selectedKodeProduk) return;

            $.ajax({
                type: "POST",
                url: "/cart", // Pastikan route ini menuju CartController@store
                data: {
                    kode_produk: selectedKodeProduk,
                    quantity: qty,
                    _token: "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(res) {
                    $('#modalQuantity').modal('hide');
                    fetchCart(); // refresh keranjang kalau ada fungsi ini
                },
                error: function(err) {
                    alert(err.responseJSON?.message || 'Gagal menambahkan produk');
                }
            });
        });
    });

    function fetchCariProduk(search) {
        $.getJSON("/transaksi/produk", { search: search }, function(response) {
            $('#resultProduk').html('');
            response.forEach(item => {
                addResultProduk(item);
            });
        });
    }

    function addResultProduk(item) {
        const { nama_produk, kode_produk } = item;

        const btn = `<button type="button"
            class="btn btn-xs btn-success" onclick="showQuantityModal('${kode_produk}')">
            Add
        </button>`;

        const row = `<tr>
            <td>${nama_produk}</td>
            <td class="text-right">${btn}</td>
        </tr>`;

        $('#resultProduk').append(row);
    }

    function showQuantityModal(kodeProduk) {
        selectedKodeProduk = kodeProduk;
        $('#inputQuantity').val(1);
        $('#modalQuantity').modal('show');
    }
</script>
@endpush
