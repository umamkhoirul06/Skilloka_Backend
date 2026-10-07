@props([
    'variant' => 'full', // 'full' atau 'icon-only'
    'class' => 'h-9',
    'textColor' => 'text-slate-900',
    'textSize' => 'text-2xl',
    'isDark' => false,
    'withSubtitle' => false,
    'subtitle' => 'Pusat Pelatihan LPK',
    'subtitleColor' => null,
    'badge' => null,
    'badgeColor' => null,
])

@php
    $primaryColor = $isDark ? '#FFFFFF' : '#0F172A';
    $subColor = $subtitleColor ?? ($isDark ? 'text-slate-400' : 'text-slate-500');
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center gap-3 select-none ' . $class]) }}>
    <!-- Simbol Vektor Flat S-Mark -->
    <svg
        viewBox="0 0 100 100"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
        class="h-full w-auto aspect-square flex-shrink-0"
    >
        <!-- Sudut Toga & Garis Atas Huruf S (Deep Navy / White) -->
        <path
            d="M50 10L82 25L50 40L18 25L50 10Z"
            fill="{{ $primaryColor }}"
        />
        <!-- Badan Utama Huruf S -->
        <path
            d="M32 36C24 42 22 52 26 62C30 72 40 78 50 82C62 86 74 84 80 76C84 70 82 62 76 56C70 50 58 46 48 42L36 38"
            stroke="{{ $primaryColor }}"
            stroke-width="14"
            stroke-linecap="round"
            stroke-linejoin="round"
        />
        <!-- Aksen Panah Upskilling / Pintu Peluang (Vibrant Gold) -->
        <path
            d="M38 78C48 74 60 64 68 50L78 40M78 40H62M78 40V56"
            stroke="#F59E0B"
            stroke-width="10"
            stroke-linecap="round"
            stroke-linejoin="round"
        />
    </svg>

    <!-- Wordmark Tipografi Skilloka -->
    @if($variant === 'full')
        <div class="flex flex-col text-left">
            <div class="flex items-center gap-2">
                <span class="{{ $textSize }} font-black tracking-tight font-sans leading-none {{ $textColor }}">
                    Skill<span class="text-[#F59E0B]">oka</span>
                </span>
                @if($badge)
                    <span class="inline-block px-1.5 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider {{ $badgeColor ?? ($isDark ? 'bg-slate-800 text-slate-300 border border-slate-700' : 'bg-brand-50 text-brand-600 border border-brand-200') }}">
                        {{ $badge }}
                    </span>
                @endif
            </div>
            @if($withSubtitle)
                <span class="block text-[9.5px] font-semibold uppercase tracking-wider {{ $subColor }} mt-1">
                    {{ $subtitle }}
                </span>
            @endif
        </div>
    @endif
</div>
