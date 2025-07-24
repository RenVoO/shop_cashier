@props(['type', 'name', 'value'])
<input type="{{ $type }}" name="{{ $name }}" class="form-control @error($name) is-invalid @enderror"
    value="<?= old($name, isset($value) ? $value : '') ?>" />
@error($name)
    <div class="invalid-feedback">
        {{ $message }}
    </div>
@enderror