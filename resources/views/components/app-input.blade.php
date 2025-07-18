@php
    $_name = $attributes['name'] ?? '';
    $_id = $attributes['id'] ?? $_name;
    $_type = $attributes['type'] ?? 'text';
    $_placeholder = $attributes['placeholder'] ?? '';
    $_label = $attributes['label'];
    $_old_value = old($_name);
    $_value = $attributes['value'] ?? '';
    $_value = empty($_old_value) ? $_value : $_old_value;
    $_isrequired = isset($attributes['required']) ? 'required' : '';
    $_title = $attributes['title'] ?? '';
@endphp
<label for="{{ $_id }}">
    {{ $_label }}
    @if($_isrequired)
        <span style="color: red;">*</span>
    @endif
</label>
<input type="{{ $_type }}" name="{{ $_name }}" id="{{ $_id }}" placeholder="{{ $_placeholder }}"
    value="{{ $_value }}" {{ $_isrequired }} title="{{ $_title }}" />

@error($_name)
    <div class="alert alert-danger">{{ $message }}</div>
@enderror
