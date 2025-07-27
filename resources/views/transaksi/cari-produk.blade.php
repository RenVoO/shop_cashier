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
    $(function() {
        $('#formCariProduk').submit(function(e) {
            e.preventDefault();
            const search = $('#searchProduk').val();
            if (search.length >= 3) {
                fetchCariProduk(search);
            }
        });
    });

    function fetchCariProduk(search) {
        $.getJSON("/transaksi/produk", {
            search: search
        },
        function(response) {
            $('#resultProduk').html('');
            response.forEach(item => {
                addResultProduk(item);
            });
        });
    }

    function addResultProduk(item) {
        const { nama_produk, kode_produk } = item;

        const btn = `<button type="button"
            class="btn btn-xs btn-success" onclick="addItem('${kode_produk}')">
            Add
        </button>`;

        const row = `<tr>
            <td>${nama_produk}</td>
            <td class="text-right">${btn}</td>
        </tr>`;

        $('#resultProduk').append(row);
    }
</script>
@endpush
