<script setup>
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    property: Object,
    related: Array,
});

function formatRupiah(value) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0
    }).format(value);
}
</script>

<template>
    <Head :title="`${property.nama_properti} — PT Insan Kapuas Khatulistiwa`" />

    <div class="min-h-screen bg-[#F4F6FA]" style="font-family: 'DM Sans', sans-serif;">
        <component :is="'style'">
            @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,600;0,700;1,600&family=DM+Sans:wght@300;400;500;600&display=swap');
            .font-display { font-family: 'Cormorant Garamond', serif; }
        </component>

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
                    <Link :href="route('booking.create')"
                        class="ml-2 px-5 py-2.5 text-sm font-semibold bg-[#0B1F4A] text-white hover:bg-[#163472] transition rounded-sm shadow-md tracking-wide">
                        Booking Sekarang
                    </Link>
                </nav>
            </div>
        </header>

        <!-- BREADCRUMB -->
        <section class="max-w-7xl mx-auto px-8 pt-8">
            <nav class="flex items-center gap-2 text-xs text-[#0B1F4A]/40">
                <Link :href="route('katalog.index')" class="hover:text-[#C9A84C] transition">Katalog</Link>
                <span>/</span>
                <span class="text-[#0B1F4A]/60">{{ property.nama_properti }}</span>
            </nav>
        </section>

        <!-- DETAIL UTAMA -->
        <section class="max-w-7xl mx-auto px-8 pt-6 pb-16">
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-10">
                <!-- Gambar -->
                <div class="lg:col-span-3">
                    <div class="h-[420px] bg-gradient-to-br from-[#0B1F4A] to-[#163472] rounded-sm overflow-hidden relative">
                        <img v-if="property.gambar" :src="`/storage/${property.gambar}`" :alt="property.nama_properti"
                            class="w-full h-full object-cover" />
                        <div v-else class="w-full h-full flex items-center justify-center text-8xl opacity-20">🏠</div>
                        <span v-if="property.kategori"
                            class="absolute top-4 left-4 bg-[#C9A84C] text-[#0B1F4A] text-[10px] font-bold px-3 py-1.5 uppercase tracking-widest">
                            {{ property.kategori.nama_kategori }}
                        </span>
                    </div>
                </div>

                <!-- Info -->
                <div class="lg:col-span-2">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="h-px w-8 bg-[#C9A84C]"></div>
                        <span class="text-[#C9A84C] text-xs font-semibold tracking-[3px] uppercase">
                            {{ property.lokasi }}
                        </span>
                    </div>
                    <h1 class="font-display text-3xl lg:text-4xl font-bold text-[#0B1F4A] tracking-tight mb-4">
                        {{ property.nama_properti }}
                    </h1>
                    <div class="font-display font-bold text-[#C9A84C] text-3xl mb-6">
                        {{ formatRupiah(property.harga) }}
                    </div>

                    <p v-if="property.deskripsi" class="text-sm text-[#0B1F4A]/70 leading-relaxed mb-8">
                        {{ property.deskripsi }}
                    </p>

                    <dl class="grid grid-cols-2 gap-4 mb-8 text-sm">
                        <div v-if="property.luas_tanah">
                            <dt class="text-[11px] uppercase tracking-wider text-[#0B1F4A]/40 font-medium mb-1">Luas Tanah</dt>
                            <dd class="text-[#0B1F4A] font-semibold">{{ property.luas_tanah }} m²</dd>
                        </div>
                        <div v-if="property.luas_bangunan">
                            <dt class="text-[11px] uppercase tracking-wider text-[#0B1F4A]/40 font-medium mb-1">Luas Bangunan</dt>
                            <dd class="text-[#0B1F4A] font-semibold">{{ property.luas_bangunan }} m²</dd>
                        </div>
                        <div v-if="property.kamar_tidur">
                            <dt class="text-[11px] uppercase tracking-wider text-[#0B1F4A]/40 font-medium mb-1">Kamar Tidur</dt>
                            <dd class="text-[#0B1F4A] font-semibold">{{ property.kamar_tidur }}</dd>
                        </div>
                        <div v-if="property.kamar_mandi">
                            <dt class="text-[11px] uppercase tracking-wider text-[#0B1F4A]/40 font-medium mb-1">Kamar Mandi</dt>
                            <dd class="text-[#0B1F4A] font-semibold">{{ property.kamar_mandi }}</dd>
                        </div>
                    </dl>

                    <Link :href="route('booking.create', { properti_id: property.id })"
                        class="block text-center px-6 py-3.5 bg-[#0B1F4A] text-white text-sm font-semibold rounded-sm hover:bg-[#163472] transition shadow-md tracking-wide">
                        Booking Properti Ini
                    </Link>
                </div>
            </div>
        </section>

        <!-- PROPERTI TERKAIT -->
        <section v-if="related && related.length" class="max-w-7xl mx-auto px-8 pb-20">
            <div class="flex items-center gap-4 mb-6">
                <div class="h-px w-8 bg-[#C9A84C]"></div>
                <span class="text-[#C9A84C] text-xs font-semibold tracking-[3px] uppercase">Properti Terkait</span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <Link v-for="p in related" :key="p.id" :href="route('katalog.show', p.id)"
                    class="bg-white border border-[#0B1F4A]/08 rounded-sm overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                    <div class="h-48 bg-gradient-to-br from-[#0B1F4A] to-[#163472] relative overflow-hidden">
                        <img v-if="p.gambar" :src="`/storage/${p.gambar}`" :alt="p.nama_properti"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                        <div v-else class="w-full h-full flex items-center justify-center text-6xl opacity-20">🏠</div>
                        <span v-if="p.kategori" class="absolute top-3 left-3 bg-[#C9A84C] text-[#0B1F4A] text-[10px] font-bold px-2.5 py-1 uppercase tracking-widest">
                            {{ p.kategori.nama_kategori }}
                        </span>
                    </div>
                    <div class="p-5">
                        <div class="text-[11px] text-[#0B1F4A]/40 uppercase tracking-wider mb-1 font-medium">{{ p.lokasi }}</div>
                        <div class="font-semibold text-[#0B1F4A] text-base mb-2">{{ p.nama_properti }}</div>
                        <div class="font-display font-bold text-[#C9A84C] text-xl">{{ formatRupiah(p.harga) }}</div>
                    </div>
                </Link>
            </div>
        </section>
    </div>
</template>