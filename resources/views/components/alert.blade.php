<div class="alert alert-{{ $type ?? 'info' }} {{ $attributes->get('class') }}">
    {{ $message ?? $slot }}
</div>