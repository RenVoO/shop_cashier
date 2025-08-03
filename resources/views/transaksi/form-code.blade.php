<form action="#" class="card card-orange card-outline" id="formBarcode">
    <div class="card-body">
        <div class="input-group">
            <input type="text" class="form-control" id="barcode" placeholder="Kode / Barcode">
            <div class="input-group-append">
                <button type="reset" class="btn btn-danger" id="resetBarcode">Clear</button>
            </div>
        </div>
        <div class="invalid-feedback" id="msgErrorBarcode"></div>
    </div>
</form>

@push('scripts')
<script>
    $(function () {
        $.ajaxSetup({
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        $('#barcode').focus();

        $('#resetBarcode').click(function () {
            $('#barcode').focus();
        });

        $('#formBarcode').submit(function (e) {
            e.preventDefault();
            let kode_produk = $('#barcode').val();
            if (kode_produk.length > 0) {
                addItem(kode_produk);
            }
        });
    });

    function addItem(kode_produk) {
        $('#msgErrorBarcode').removeClass('d-block').html('');
        $('#barcode').removeClass('is-invalid').prop('disabled', true);

        $.post('/cart', { 'kode_produk': kode_produk }, function (response) {
            if (response.status === 'error') {
                $('#msgErrorBarcode').addClass('d-block').html(response.message);
                $('#barcode').addClass('is-invalid');
                return;
            }

            fetchCart(); // Update cart
        }, "json").fail(function (error) {
            if (error.status == 422) {
                $('#msgErrorBarcode').addClass('d-block').html(error.responseJSON.errors.kode_produk[0]);
                $('#barcode').addClass('is-invalid');
            } else if (error.status == 400 && error.responseJSON.message) {
                $('#msgErrorBarcode').addClass('d-block').html(error.responseJSON.message);
                $('#barcode').addClass('is-invalid');
            }
        }).always(function () {
            $('#barcode').val('').prop('disabled', false).focus();
        });
    }
</script>
@endpush
