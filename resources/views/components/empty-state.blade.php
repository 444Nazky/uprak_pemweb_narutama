@props(['icon' => 'inbox', 'title', 'description' => null])

<div {{ $attributes->merge(['class' => 'ka-empty']) }}>
    <div class="ka-empty-icon">
        <x-icon :name="$icon" />
    </div>
    <h3>{{ $title }}</h3>
    @if ($description)
        <p>{{ $description }}</p>
    @endif
    {{ $slot }}
</div>
