
<x-layouts.app.sidebar :title="$title ?? null">
    <flux:main>
        <div rel="stylesheet" href="{{ asset('css/banner.css') }}"
            class="moved-banner rounded-lg">
            <h2 class="text-4xl font-semibold flex justify-center text-black bg-cyan-500">
                {{ $title ?? 'Kalonde Julius POS' }}
            </h2>
        </div>

        {{ $slot }}
    </flux:main>
</x-layouts.app.sidebar>
