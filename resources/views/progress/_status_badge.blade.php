@php
    $statusClass = match ($status['key']) {
        'membaik' => 'badge-ringan',
        'perlu_perhatian' => 'badge-berat',
        'stabil' => 'badge-neutral',
        default => 'badge-muted',
    };

    $statusIcon = match ($status['key']) {
        'membaik' => 'fa-arrow-trend-up',
        'perlu_perhatian' => 'fa-triangle-exclamation',
        'stabil' => 'fa-equals',
        default => 'fa-hourglass-half',
    };
@endphp

@once
    @push('styles')
        <style>
            .badge-muted {
                background: #edf1f5;
                color: #647b8e;
            }

            .badge i {
                margin-right: 3px;
            }
        </style>
    @endpush
@endonce

<span class="badge {{ $statusClass }}">
    <i class="fa-solid {{ $statusIcon }}"></i>
    {{ $status['label'] }}
</span>
