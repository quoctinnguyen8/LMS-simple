@props(['type' => 'success', 'message' => '', 'show' => false])
@php
    $classes = [
        'success' => 'notify-success',
        'error' => 'notify-error',
        'warning' => 'notify-warning',
    ];
    
    $notifyClass = $classes[$type] ?? 'notify-success';
@endphp

<div class="notify {{ $notifyClass }} {{ $show ? 'show' : 'hidden' }}" 
     style="{{ $show ? 'display: block;' : 'display: none;' }}">
    {{ $message ?: $slot }}
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const notify = document.querySelector('.notify');
        if (notify && notify.style.display !== 'none') {
            setTimeout(() => {
                notify.style.display = 'none';
            }, 5000); // 5 seconds
        }
    });
</script>