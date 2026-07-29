<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    laravelVersion: { type: String, default: '' },
    phpVersion: { type: String, default: '' },
});
</script>

<template>
    <footer class="bg-[#080F22] border-t border-white/05">
        <div class="max-w-7xl mx-auto px-8 py-10">
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-8 mb-8">
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 bg-[#0B1F4A] border border-white/10 flex items-center justify-center rounded-sm">
                            <svg width="16" height="16" viewBox="0 0 18 18" fill="none">
                                <rect x="1" y="8" width="7" height="9" fill="#C9A84C" />
                                <rect x="10" y="4" width="7" height="13" fill="white" opacity="0.7" />
                                <polygon points="0,8 9,1 18,4 18,4 10,4 9,2 1,8" fill="#C9A84C" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-[10px] text-[#C9A84C] font-semibold tracking-[2px] uppercase">PT Insan Kapuas</div>
                            <div class="text-white text-sm font-semibold tracking-tight">Khatulistiwa</div>
                        </div>
                    </div>
                    <p class="text-white/30 text-xs leading-relaxed max-w-xs">
                        Perusahaan properti terpercaya di Kalimantan Barat.<br />
                        Melayani dengan profesionalisme dan integritas.
                    </p>
                </div>
                <div class="flex gap-10 text-sm text-white/30">
                    <div>
                        <div class="text-white/60 font-semibold mb-3 text-xs uppercase tracking-wider">Platform</div>
                        <div class="flex flex-col gap-2">
                            <Link :href="route('booking.create')" class="hover:text-[#C9A84C] transition">Booking Konsultasi</Link>
                            <Link href="/cek-booking" class="hover:text-[#C9A84C] transition">Cek Status Booking</Link>
                            <Link :href="route('katalog.index')" class="hover:text-[#C9A84C] transition">Katalog Properti</Link>
                        </div>
                    </div>
                    <div>
                        <div class="text-white/60 font-semibold mb-3 text-xs uppercase tracking-wider">Perusahaan</div>
                        <div class="flex flex-col gap-2">
                            <span>Tentang Kami</span>
                            <span>Kontak</span>
                            <span>Lokasi</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-t border-white/05 pt-6 flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-white/20 text-xs">© 2025 PT Insan Kapuas Khatulistiwa. Hak Cipta Dilindungi.</p>
                <p v-if="laravelVersion" class="text-white/15 text-xs font-mono">Laravel v{{ laravelVersion }} · PHP v{{ phpVersion }}</p>
            </div>
        </div>
    </footer>
</template>