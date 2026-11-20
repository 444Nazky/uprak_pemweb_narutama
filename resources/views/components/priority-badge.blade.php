@props(['prioritas'])

@php
    $map = [
        'tinggi' => ['label' => 'Prioritas tinggi', 'tone' => 'danger'],
        'sedang' => ['label' => 'Prioritas sedang', 'tone' => 'warning'],
        'rendah' => ['label' => 'Prioritas rendah', 'tone' => 'neutral'],
    ];
    $item = $map[$prioritas] ?? ['label' => ucfirst($prioritas), 'tone' => 'neutral'];
@endphp

<span {{ $attributes->merge(['class' => 'ka-badge ka-badge--'.$item['tone']]) }}>
    {{ $item['label'] }}
</span>
