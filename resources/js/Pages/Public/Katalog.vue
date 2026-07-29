<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import SiteHeader from '@/Components/SiteHeader.vue';
import SiteFooter from '@/Components/SiteFooter.vue';

const props = defineProps({
    properties: Object,
    kategori: Array,
    filters: Object,
});

const search = ref(props.filters.search || '');
const kategoriId = ref(props.filters.kategori_id || '');
const hargaMin = ref(props.filters.harga_min || '');
const hargaMax = ref(props.filters.harga_max || '');

function applyFilter() {
    // buang key yang kosong biar query string bersih & filter beneran ke-apply
    const query = {};
    if (search.value) query.search = search.value;
    if (kategoriId.value) query.kategori_id = kategoriId.value;
    if (hargaMin.value) query.harga_min = hargaMin.value;
    if (hargaMax.value) query.harga_max = hargaMax.value;

    router.get(route('katalog.index'), query, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
}

// filter kategori & harga langsung apply begitu diubah, gak perlu klik tombol
watch(kategoriId, applyFilter);

function resetFilter() {
    search.value = '';
    kategoriId.value = '';
    hargaMin.value = '';
    hargaMax.value = '';
    applyFilter();
}

function formatRupiah(value) {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(value);
}
</script>

<template>
    <Head title="Katalog Properti — PT Insan Kapuas Khatulistiwa" />

    <div class="min-h-screen bg-[#F4F6FA]" style="font-family: 'DM Sans', sans-serif;">
        <component :is="'style'">
            @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,600;0,700;1,600&family=DM+Sans:wght@300;400;500;600&display=swap');
            .font-display { font-family: 'Cormorant Garamond', serif; }
        </component>

        <SiteHeader active="katalog" />

        <!-- PAGE TITLE BAND — samain ritme visual sama hero di Welcome -->
        <section class="relative overflow-hidden bg-[#0B1F4A]">
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-[#163472] rounded-full opacity-30 translate-x-1/3 -translate-y-1/3"></div>
            </div>
            <div class="relative max-w-7xl mx-auto px-8 py-16 lg:py-20">
                <div class="flex items-center gap-3 mb-4">
                    <div class="h-px w-10 bg-[#C9A84C]"></div>
                    <span class="text-[#C9A84C] text-xs font-semibold tracking-[3px] uppercase">Katalog</span>
                </div>
                <h1 class="font-display text-4xl lg:text-5xl font-bold text-white tracking-tight">
                    Jelajahi Properti Kami
                </h1>
                <p class="text-white/60 text-sm mt-3 max-w-md">
                    Temukan rumah subsidi yang sesuai kebutuhan Anda, lengkap dengan detail lokasi dan harga.
                </p>
            </div>
            <div class="relative h-12 bg-[#0B1F4A]">
                <svg class="absolute bottom-0 w-full" viewBox="0 0 1440 48" fill="none" preserveAspectRatio="none">
                    <path d="M0 48L1440 48L1440 15C1200 45 960 0 720 22C480 45 240 8 0 30L0 48Z" fill="#F4F6FA" />
                </svg>
            </div>
        </section>

        <!-- FILTER BAR -->
        <section class="max-w-7xl mx-auto px-8 py-10">
            <div class="bg-white border border-[#0B1F4A]/08 rounded-sm p-5 flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[200px]">
                    <label class="text-[11px] uppercase tracking-wider text-[#0B1F4A]/40 font-medium mb-1 block">Cari</label>
                    <input v-model="search" @keyup.enter="applyFilter" type="text" placeholder="Nama properti atau lokasi..."
                        class="w-full border border-[#0B1F4A]/15 rounded-sm px-3 py-2 text-sm focus:outline-none focus:border-[#C9A84C]" />
                </div>
                <div class="w-48">
                    <label class="text-[11px] uppercase tracking-wider text-[#0B1F4A]/40 font-medium mb-1 block">Kategori</label>
                    <select v-model="kategoriId"
                        class="w-full border border-[#0B1F4A]/15 rounded-sm px-3 py-2 text-sm focus:outline-none focus:border-[#C9A84C]">
                        <option value="">Semua Kategori</option>
                        <option v-for="k in kategori" :key="k.id" :value="String(k.id)">{{ k.nama_kategori }}</option>
                    </select>
                </div>
                <div class="w-36">
                    <label class="text-[11px] uppercase tracking-wider text-[#0B1F4A]/40 font-medium mb-1 block">Harga Min</label>
                    <input v-model="hargaMin" @keyup.enter="applyFilter" type="number" min="0" placeholder="0"
                        class="w-full border border-[#0B1F4A]/15 rounded-sm px-3 py-2 text-sm focus:outline-none focus:border-[#C9A84C]" />
                </div>
                <div class="w-36">
                    <label class="text-[11px] uppercase tracking-wider text-[#0B1F4A]/40 font-medium mb-1 block">Harga Max</label>
                    <input v-model="hargaMax" @keyup.enter="applyFilter" type="number" min="0" placeholder="0"
                        class="w-full border border-[#0B1F4A]/15 rounded-sm px-3 py-2 text-sm focus:outline-none focus:border-[#C9A84C]" />
                </div>
                <button @click="applyFilter"
                    class="px-6 py-2.5 bg-[#0B1F4A] text-white text-sm font-semibold rounded-sm hover:bg-[#163472] transition">
                    Terapkan
                </button>
                <button v-if="search || kategoriId || hargaMin || hargaMax" @click="resetFilter"
                    class="px-6 py-2.5 border border-[#0B1F4A]/15 text-[#0B1F4A]/60 text-sm font-semibold rounded-sm hover:bg-[#0B1F4A]/05 transition">
                    Reset
                </button>
            </div>
        </section>

        <!-- GRID PROPERTI -->
        <section class="max-w-7xl mx-auto px-8 pb-20">
            <div v-if="properties.data.length === 0" class="text-center py-20 text-[#0B1F4A]/40">
                Tidak ada properti yang cocok dengan pencarian Anda.
            </div>
            <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <Link v-for="p in properties.data" :key="p.id" :href="route('katalog.show', p.id)"
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

            <div v-if="properties.links.length > 3" class="flex flex-wrap gap-2 justify-center mt-12">
                <Link v-for="(link, i) in properties.links" :key="i" :href="link.url || '#'"
                    :as="link.url ? 'a' : 'span'"
                    v-html="link.label"
                    class="px-4 py-2 text-sm rounded-sm border"
                    :class="[
                        link.active ? 'bg-[#0B1F4A] text-white border-[#0B1F4A]' : 'bg-white text-[#0B1F4A]/60 border-[#0B1F4A]/10 hover:border-[#C9A84C]',
                        !link.url ? 'opacity-30 pointer-events-none' : ''
                    ]" />
            </div>
        </section>

        <SiteFooter />
    </div>
</template>