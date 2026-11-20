@props(['status'])

@php
    $map = [
        'menunggu' => ['label' => 'Menunggu', 'tone' => 'warning', 'icon' => 'hourglass-split'],
        'diproses' => ['label' => 'Diproses', 'tone' => 'info', 'icon' => 'clock-history'],
        'selesai' => ['label' => 'Selesai', 'tone' => 'success', 'icon' => 'check-circle'],
    ];
    $item = $map[$status] ?? ['label' => ucfirst($status), 'tone' => 'neutral', 'icon' => 'dot'];
@endphp

<span {{ $attributes->merge(['class' => 'ka-badge ka-badge--'.$item['tone']]) }}>
    <x-icon :name="$item['icon']" style="font-size:12px" />
    {{ $item['label'] }}
</span>
