@props(['type'])
<div class="alert alert-dismissble fade show alert-{{ $type }}" role="alert">
    <?= $slot ?>
    <button type="button" class="close" data-dismiss="alert">
        <span>&times;</span>
    </button>
</div>