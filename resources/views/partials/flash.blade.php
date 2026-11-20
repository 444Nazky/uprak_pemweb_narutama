@if (session('status'))
    <div class="ka-flash ka-flash--success" role="status">
        <x-icon name="check-circle" />
        <span>{{ session('status') }}</span>
        <button type="button" class="ka-flash-close" data-flash-close aria-label="Tutup notifikasi">
            <x-icon name="x-lg" />
        </button>
    </div>
@endif

@if (session('error'))
    <div class="ka-flash ka-flash--error" role="alert">
        <x-icon name="exclamation-triangle" />
        <span>{{ session('error') }}</span>
        <button type="button" class="ka-flash-close" data-flash-close aria-label="Tutup notifikasi">
            <x-icon name="x-lg" />
        </button>
    </div>
@endif

@if ($errors->any() && ! request()->routeIs('login') && ! request()->routeIs('register'))
    <div class="ka-flash ka-flash--error" role="alert">
        <x-icon name="exclamation-triangle" />
        <span>Ada {{ $errors->count() }} isian yang perlu diperbaiki. Periksa tanda merah pada formulir di bawah.</span>
    </div>
@endif
