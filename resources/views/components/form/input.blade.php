<?php extract($props); ?>
<div class="form-group mb-3">
    @if(!empty($label))
    <label for="{{ $name }}">{{ $label }}</label>
    @endif
    <input type="{{ $type ?? 'text' }}" name="{{ $name }}" class="form-control @error($name) is-invalid @enderror"
        value="{{ $type === 'password' ? '' : old($name, $value ?? '') }}"
        placeholder="{{ $placeholder ?? '' }}"
        @if(!empty($required)) required @endif @if(!empty($autofocus)) autofocus @endif>
    @error($name)
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>