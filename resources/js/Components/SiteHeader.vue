<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    active: { type: String, default: '' }, // 'katalog' | 'booking' | ''
});
</script>

<template>
    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur-xl border-b border-[#0B1F4A]/08 shadow-sm">
        <div class="max-w-7xl mx-auto px-8 h-[68px] flex items-center justify-between">
            <Link href="/" class="flex items-center gap-3">
                <div class="w-9 h-9 bg-[#0B1F4A] flex items-center justify-center rounded-sm">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                        <rect x="1" y="8" width="7" height="9" fill="#C9A84C" />
                        <rect x="10" y="4" width="7" height="13" fill="white" />
                        <polygon points="0,8 9,1 18,4 18,4 10,4 9,2 1,8" fill="#C9A84C" />
                    </svg>
                </div>
                <div class="flex flex-col leading-none">
                    <span class="text-[11px] font-semibold text-[#C9A84C] tracking-[2px] uppercase">PT Insan Kapuas</span>
                    <span class="text-[15px] font-semibold text-[#0B1F4A] tracking-tight">Khatulistiwa</span>
                </div>
            </Link>
            <nav class="flex items-center gap-1">
                <Link href="/cek-booking"
                    class="px-5 py-2 rounded text-sm font-medium text-[#0B1F4A]/60 hover:text-[#0B1F4A] hover:bg-[#0B1F4A]/05 transition">
                    Cek Booking
                </Link>
                <Link :href="route('katalog.index')"
                    class="px-5 py-2 rounded text-sm font-medium transition"
                    :class="active === 'katalog' ? 'text-[#0B1F4A] bg-[#0B1F4A]/05' : 'text-[#0B1F4A]/60 hover:text-[#0B1F4A] hover:bg-[#0B1F4A]/05'">
                    Katalog Properti
                </Link>
                <Link :href="route('booking.create')"
                    class="ml-2 px-5 py-2.5 text-sm font-semibold bg-[#0B1F4A] text-white hover:bg-[#163472] transition rounded-sm shadow-md tracking-wide">
                    Booking Sekarang
                </Link>
            </nav>
        </div>
    </header>
</template>