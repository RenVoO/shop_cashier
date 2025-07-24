<div class="modal fade" id="modalDelete" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah yakin akan dihapus?</p>
                <form action="?" method="post" style="display: none;" id="formDelete">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" id="yesDelete">Ya, Hapus!</button>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        $(function() {
            $('#modalDelete').on('show.bs.modal', function(event){
                var url = $(event.relatedTarget).data('url');
                $(this).find('#formDelete').attr('action', url);
            }); 

            $('#yesDelete').click(function() {
                $('#formDelete').submit();
            });

        })
    </script>
@endpush