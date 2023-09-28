<?php extract($props); ?>
<div class="input-group mb-3">
    <input type="{{ $type ?? 'text' }}" name="{{ $name }}" class="form-control @error($name) is-invalid @enderror"
        value="{{ $type === 'password' ? '' : old($name, $value ?? '') }}"
        placeholder="{{ $placeholder ?? '' }}"
        @if(!empty($required)) required @endif @if(!empty($autofocus)) autofocus @endif>
    @error($name)
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>