<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

defineProps({
    canLogin: { type: Boolean },
    canRegister: { type: Boolean },
    laravelVersion: { type: String, required: true },
    phpVersion: { type: String, required: true },
});

const visible = ref(false);
onMounted(() => setTimeout(() => (visible.value = true), 150));
</script>

<template>

    <Head title="IKK Properti — PT Insan Kapuas Khatulistiwa" />

    <div class="min-h-screen bg-[#F4F6FA] overflow-x-hidden" style="font-family: 'DM Sans', sans-serif;">

        <!-- Google Fonts -->
        <component :is="'style'">
            @import
            url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,600;0,700;1,600&family=DM+Sans:wght@300;400;500;600&display=swap');
            .font-display { font-family: 'Cormorant Garamond', serif; }
        </component>

        <!-- NAVBAR -->
        <header class="sticky top-0 z-50 bg-white/90 backdrop-blur-xl border-b border-[#0B1F4A]/08 shadow-sm">
            <div class="max-w-7xl mx-auto px-8 h-[68px] flex items-center justify-between">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-[#0B1F4A] flex items-center justify-center rounded-sm">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                            <rect x="1" y="8" width="7" height="9" fill="#C9A84C" />
                            <rect x="10" y="4" width="7" height="13" fill="white" />
                            <polygon points="0,8 9,1 18,4 18,4 10,4 9,2 1,8" fill="#C9A84C" />
                        </svg>
                    </div>
                    <div class="flex flex-col leading-none">
                        <span class="text-[11px] font-semibold text-[#C9A84C] tracking-[2px] uppercase">PT Insan
                            Kapuas</span>
                        <span class="text-[15px] font-semibold text-[#0B1F4A] tracking-tight">Khatulistiwa</span>
                    </div>
                </div>

                <!-- Nav links -->
                <nav v-if="canLogin" class="flex items-center gap-1">
                    <Link v-if="$page.props.auth.user" :href="route('dashboard')"
                        class="px-5 py-2 rounded text-sm font-medium text-[#0B1F4A] hover:bg-[#0B1F4A]/05 transition">
                        Dashboard
                    </Link>
                    <template v-else>
                        <!-- ✅ TAMBAHAN: Link Cek Booking -->
                        <Link href="/cek-booking"
                            class="px-5 py-2 rounded text-sm font-medium text-[#0B1F4A]/60 hover:text-[#0B1F4A] hover:bg-[#0B1F4A]/05 transition">
                            Cek Booking
                        </Link>
                        <Link :href="route('login')"
                            class="px-5 py-2 rounded text-sm font-medium text-[#0B1F4A] hover:bg-[#0B1F4A]/05 transition">
                            Masuk
                        </Link>
                        <Link :href="route('booking.create')"
                            class="ml-2 px-5 py-2.5 text-sm font-semibold bg-[#0B1F4A] text-white hover:bg-[#163472] transition rounded-sm shadow-md tracking-wide">
                            Booking Sekarang
                        </Link>
                    </template>
                </nav>
            </div>
        </header>

        <!-- HERO -->
        <section class="relative overflow-hidden bg-[#0B1F4A]">
            <!-- Geometric BG -->
            <div class="absolute inset-0 pointer-events-none">
                <div
                    class="absolute top-0 right-0 w-[600px] h-[600px] bg-[#163472] rounded-full opacity-30 translate-x-1/3 -translate-y-1/4">
                </div>
                <div
                    class="absolute bottom-0 left-0 w-96 h-96 bg-[#C9A84C]/10 rounded-full -translate-x-1/3 translate-y-1/3">
                </div>
                <!-- Grid lines -->
                <svg class="absolute inset-0 w-full h-full opacity-[0.04]" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="grid" width="60" height="60" patternUnits="userSpaceOnUse">
                            <path d="M 60 0 L 0 0 0 60" fill="none" stroke="white" stroke-width="0.5" />
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid)" />
                </svg>
                <!-- Diagonal accent -->
                <div
                    class="absolute bottom-0 right-[30%] w-[2px] h-full bg-gradient-to-b from-transparent via-[#C9A84C]/30 to-transparent">
                </div>
            </div>

            <div class="relative max-w-7xl mx-auto px-8 py-24 lg:py-32 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center transition-all duration-700"
                :class="visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'">
                <!-- Left -->
                <div>
                    <div class="flex items-center gap-3 mb-7">
                        <div class="h-px w-10 bg-[#C9A84C]"></div>
                        <span class="text-[#C9A84C] text-xs font-semibold tracking-[3px] uppercase">Sistem Katalog
                            Properti</span>
                    </div>
                    <h1
                        class="font-display text-5xl lg:text-[4.2rem] font-bold leading-[1.05] text-white mb-6 tracking-tight">
                        Solusi Properti<br />
                        <span class="italic text-[#C9A84C]">Terpercaya</span> untuk<br />
                        Kalimantan Barat
                    </h1>
                    <p class="text-white/60 text-base leading-relaxed max-w-md mb-10">
                        PT Insan Kapuas Khatulistiwa menghadirkan rumah subsidi
                        berkualitas dengan harga terjangkau. Konsultasikan kebutuhan
                        Anda kapan saja — kami siap membantu mewujudkan hunian impian.
                    </p>

                    <div class="flex flex-wrap gap-3 mb-12">
                        <Link v-if="$page.props.auth.user" :href="route('dashboard')"
                            class="inline-flex items-center gap-2 px-7 py-3.5 bg-[#C9A84C] text-[#0B1F4A] font-semibold text-sm rounded-sm hover:bg-[#E2C06A] transition shadow-lg">
                            Buka Dashboard
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M3 8H13M13 8L9 4M13 8L9 12" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" />
                            </svg>
                        </Link>
                        <template v-else>
                            <Link :href="route('booking.create')"
                                class="inline-flex items-center gap-2 px-7 py-3.5 bg-[#C9A84C] text-[#0B1F4A] font-semibold text-sm rounded-sm hover:bg-[#E2C06A] transition shadow-lg">
                                Booking Sekarang
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                    <path d="M3 8H13M13 8L9 4M13 8L9 12" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" />
                                </svg>
                            </Link>
                            <!-- ✅ TAMBAHAN: Cek Booking di Hero -->
                            <Link href="/cek-booking"
                                class="inline-flex items-center gap-2 px-7 py-3.5 border border-white/20 text-white font-semibold text-sm rounded-sm hover:bg-white/08 transition">
                                🔎 Cek Status Booking
                            </Link>
                        </template>
                    </div>

                    <div class="flex items-stretch gap-8 pt-8 border-t border-white/10">
                        <div>
                            <div class="font-display text-3xl font-bold text-white leading-none">2</div>
                            <div class="text-white/40 text-xs mt-1 uppercase tracking-wider font-medium">Tipe Rumah
                            </div>
                        </div>
                        <div class="w-px bg-white/10"></div>
                        <div>
                            <div class="font-display text-3xl font-bold text-white leading-none">KPR</div>
                            <div class="text-white/40 text-xs mt-1 uppercase tracking-wider font-medium">Subsidi FLPP
                            </div>
                        </div>
                        <div class="w-px bg-white/10"></div>
                        <div>
                            <div class="font-display text-3xl font-bold text-white leading-none">100%</div>
                            <div class="text-white/40 text-xs mt-1 uppercase tracking-wider font-medium">Legal &amp;
                                Bersertifikat</div>
                        </div>
                    </div>
                </div>

                <!-- Right: Property Cards -->
                <div class="relative h-[420px] hidden lg:block">
                    <!-- Card Tipe 36 -->
                    <div class="absolute top-4 left-4 w-64 bg-white rounded-sm shadow-2xl overflow-hidden hover:-translate-y-2 transition-transform duration-300 z-10"
                        style="transform: rotate(-2deg);">
                        <div class="h-40 bg-gradient-to-br from-[#163472] to-[#0B1F4A] flex items-end p-4 relative">
                            <div class="absolute inset-0 opacity-20 flex items-center justify-center text-6xl">🏠</div>
                            <span
                                class="relative z-10 bg-[#C9A84C] text-[#0B1F4A] text-[10px] font-bold px-2.5 py-1 uppercase tracking-widest">Subsidi</span>
                        </div>
                        <div class="p-4">
                            <div class="text-[11px] text-[#0B1F4A]/40 uppercase tracking-wider mb-1 font-medium">Rumah
                                Subsidi
                            </div>
                            <div class="font-semibold text-[#0B1F4A] text-sm mb-2">Tipe 36 — Griya IKK</div>
                            <div class="font-display font-bold text-[#C9A84C] text-lg mb-3">± Rp 166 Jt</div>
                            <div class="flex gap-3 text-[11px] text-[#0B1F4A]/50 border-t border-[#0B1F4A]/08 pt-3">
                                <span>🛏 2 KT</span><span>🚿 1 KM</span><span>📐 36 m²</span>
                            </div>
                        </div>
                    </div>
                    <!-- Card Tipe 46 -->
                    <div class="absolute bottom-4 right-0 w-64 bg-white rounded-sm shadow-2xl overflow-hidden hover:-translate-y-2 transition-transform duration-300 z-20"
                        style="transform: rotate(1.5deg);">
                        <div class="h-40 bg-gradient-to-br from-[#1a3a1a] to-[#0d2e0d] flex items-end p-4 relative">
                            <div class="absolute inset-0 opacity-20 flex items-center justify-center text-6xl">🏡</div>
                            <div class="relative z-10 flex items-center gap-2">
                                <span
                                    class="bg-emerald-600 text-white text-[10px] font-bold px-2.5 py-1 uppercase tracking-widest">Tersedia</span>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="text-[11px] text-[#0B1F4A]/40 uppercase tracking-wider mb-1 font-medium">Rumah
                                Subsidi
                            </div>
                            <div class="font-semibold text-[#0B1F4A] text-sm mb-2">Tipe 46/40 — Griya IKK</div>
                            <div class="font-display font-bold text-[#C9A84C] text-lg mb-3">± Rp 185 Jt</div>
                            <div class="flex gap-3 text-[11px] text-[#0B1F4A]/50 border-t border-[#0B1F4A]/08 pt-3">
                                <span>🛏 3 KT</span><span>🚿 1 KM</span><span>📐 46 m²</span>
                            </div>
                        </div>
                    </div>
                    <!-- Floating badge -->
                    <div
                        class="absolute top-1/2 right-6 -translate-y-1/2 z-30 bg-[#C9A84C] text-[#0B1F4A] rounded-sm px-4 py-3 shadow-xl text-center">
                        <div class="font-display text-2xl font-bold leading-none">KPR</div>
                        <div class="text-[10px] font-semibold uppercase tracking-wider mt-0.5">FLPP</div>
                    </div>
                </div>
            </div>

            <!-- Bottom wave divider -->
            <div class="relative h-16 bg-[#0B1F4A]">
                <svg class="absolute bottom-0 w-full" viewBox="0 0 1440 64" fill="none" preserveAspectRatio="none">
                    <path d="M0 64L1440 64L1440 20C1200 60 960 0 720 30C480 60 240 10 0 40L0 64Z" fill="#F4F6FA" />
                </svg>
            </div>
        </section>

        <!-- LAYANAN / FEATURES -->
        <section class="max-w-7xl mx-auto px-8 py-20">
            <div class="flex items-center gap-4 mb-3">
                <div class="h-px w-8 bg-[#C9A84C]"></div>
                <span class="text-[#C9A84C] text-xs font-semibold tracking-[3px] uppercase">Fitur Unggulan</span>
            </div>
            <h2 class="font-display text-4xl font-bold text-[#0B1F4A] mb-12 tracking-tight">
                Semua yang Anda Butuhkan<br />dalam Satu Platform
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div v-for="(f, i) in [
                    { icon: '🏘', title: 'Katalog Properti', desc: 'Lihat detail lengkap setiap unit — lokasi, harga, tipe, luas bangunan, dan fasilitas tersedia.', accent: false },
                    { icon: '📅', title: 'Booking Kunjungan', desc: 'Ajukan jadwal konsultasi atau kunjungan lokasi kapan pun Anda inginkan secara online.', accent: true },
                    { icon: '🔎', title: 'Cari by Kategori', desc: 'Saring properti berdasarkan tipe, lokasi, atau rentang harga untuk hasil yang relevan.', accent: false },
                    { icon: '📋', title: 'Pantau Status', desc: 'Cek status booking Anda secara real-time — menunggu konfirmasi, disetujui, atau ditolak.', accent: false },
                ]" :key="i"
                    class="rounded-sm p-6 transition-all duration-200 hover:-translate-y-1 hover:shadow-xl group"
                    :class="f.accent ? 'bg-[#0B1F4A]' : 'bg-white border border-[#0B1F4A]/08'">
                    <div class="w-11 h-11 rounded-sm flex items-center justify-center text-xl mb-5"
                        :class="f.accent ? 'bg-[#C9A84C]/20' : 'bg-[#0B1F4A]/06'">
                        {{ f.icon }}
                    </div>
                    <h3 class="font-semibold text-base mb-2 tracking-tight"
                        :class="f.accent ? 'text-white' : 'text-[#0B1F4A]'">
                        {{ f.title }}</h3>
                    <p class="text-sm leading-relaxed" :class="f.accent ? 'text-white/60' : 'text-[#0B1F4A]/50'">{{
                        f.desc }}
                    </p>
                    <div class="mt-5 h-px"
                        :class="f.accent ? 'bg-[#C9A84C]/30' : 'bg-[#0B1F4A]/06 group-hover:bg-[#C9A84C] transition-colors'">
                    </div>
                </div>
            </div>
        </section>

        <!-- SHOWCASE 2 TIPE PROPERTI -->
        <section class="bg-[#0B1F4A]/04 border-y border-[#0B1F4A]/06 py-20">
            <div class="max-w-7xl mx-auto px-8">
                <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4 mb-12">
                    <div>
                        <div class="flex items-center gap-4 mb-2">
                            <div class="h-px w-8 bg-[#C9A84C]"></div>
                            <span class="text-[#C9A84C] text-xs font-semibold tracking-[3px] uppercase">Pilihan
                                Properti</span>
                        </div>
                        <h2 class="font-display text-4xl font-bold text-[#0B1F4A] tracking-tight leading-tight">
                            Rumah Subsidi<br />Griya IKK
                        </h2>
                    </div>
                    <p class="text-[#0B1F4A]/50 text-sm leading-relaxed max-w-sm lg:text-right">
                        Dua tipe pilihan rumah subsidi KPR FLPP dengan cicilan ringan,
                        lokasi strategis di Kalimantan Barat, bersertifikat resmi.
                    </p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Tipe 36 -->
                    <div
                        class="bg-white border border-[#0B1F4A]/08 rounded-sm overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                        <div
                            class="h-52 bg-gradient-to-br from-[#0B1F4A] via-[#163472] to-[#0d2640] relative flex items-end p-6">
                            <div class="absolute inset-0 flex items-center justify-center text-8xl opacity-10">🏠</div>
                            <div class="relative z-10">
                                <span
                                    class="inline-block bg-[#C9A84C] text-[#0B1F4A] text-[10px] font-bold px-3 py-1 uppercase tracking-widest mb-3">KPR
                                    Subsidi FLPP</span>
                                <div class="font-display text-3xl font-bold text-white leading-none">Tipe 36</div>
                            </div>
                            <div
                                class="absolute top-4 right-4 bg-white/10 backdrop-blur-sm border border-white/20 rounded-sm px-3 py-2 text-center">
                                <div class="text-white text-[10px] font-semibold uppercase tracking-wider">Luas</div>
                                <div class="font-display text-xl font-bold text-[#C9A84C] leading-none">36 m²</div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-baseline justify-between mb-5">
                                <div>
                                    <div class="text-[11px] text-[#0B1F4A]/40 uppercase tracking-wider mb-1">Harga</div>
                                    <div class="font-display text-2xl font-bold text-[#C9A84C]">± Rp 166.000.000</div>
                                </div>
                                <div class="text-right">
                                    <div class="text-[11px] text-[#0B1F4A]/40 uppercase tracking-wider mb-1">Cicilan
                                    </div>
                                    <div class="font-semibold text-[#0B1F4A] text-sm">± Rp 900 Rb/bln</div>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-3 mb-5 py-4 border-y border-[#0B1F4A]/06">
                                <div class="text-center">
                                    <div class="text-xl mb-1">🛏</div>
                                    <div class="text-xs font-semibold text-[#0B1F4A]">2 Kamar Tidur</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-xl mb-1">🚿</div>
                                    <div class="text-xs font-semibold text-[#0B1F4A]">1 Kamar Mandi</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-xl mb-1">📐</div>
                                    <div class="text-xs font-semibold text-[#0B1F4A]">LT 60 m²</div>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-2 mb-5">
                                <span v-for="f in ['Listrik 1300W', 'Air PDAM', 'IMB Lengkap', 'SHM']" :key="f"
                                    class="text-[11px] bg-[#0B1F4A]/05 text-[#0B1F4A]/60 px-2.5 py-1 rounded-sm font-medium">{{
                                    f }}</span>
                            </div>
                            <Link :href="route('booking.create')"
                                class="w-full flex items-center justify-center gap-2 py-3 bg-[#0B1F4A] text-white text-sm font-semibold rounded-sm hover:bg-[#C9A84C] hover:text-[#0B1F4A] transition-all group-hover:bg-[#163472]">
                                Konsultasi Sekarang
                                <svg width="14" height="14" viewBox="0 0 16 16" fill="none">
                                    <path d="M3 8H13M13 8L9 4M13 8L9 12" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" />
                                </svg>
                            </Link>
                        </div>
                    </div>

                    <!-- Tipe 46/40 -->
                    <div
                        class="bg-white border-2 border-[#C9A84C]/40 rounded-sm overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group relative">
                        <!-- Recommended badge -->
                        <div
                            class="absolute top-4 left-4 z-30 bg-[#C9A84C] text-[#0B1F4A] text-[10px] font-bold px-3 py-1 uppercase tracking-widest rounded-sm">
                            ★ Paling Diminati
                        </div>
                        <div
                            class="h-52 bg-gradient-to-br from-[#1a3a1a] via-[#163016] to-[#0d2010] relative flex items-end p-6">
                            <div class="absolute inset-0 flex items-center justify-center text-8xl opacity-10">🏡</div>
                            <div class="relative z-10">
                                <span
                                    class="inline-block bg-[#C9A84C] text-[#0B1F4A] text-[10px] font-bold px-3 py-1 uppercase tracking-widest mb-3">KPR
                                    Subsidi FLPP</span>
                                <div class="font-display text-3xl font-bold text-white leading-none">Tipe 46/40</div>
                            </div>
                            <div
                                class="absolute top-4 right-4 bg-white/10 backdrop-blur-sm border border-white/20 rounded-sm px-3 py-2 text-center">
                                <div class="text-white text-[10px] font-semibold uppercase tracking-wider">Luas</div>
                                <div class="font-display text-xl font-bold text-[#C9A84C] leading-none">46 m²</div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-baseline justify-between mb-5">
                                <div>
                                    <div class="text-[11px] text-[#0B1F4A]/40 uppercase tracking-wider mb-1">Harga</div>
                                    <div class="font-display text-2xl font-bold text-[#C9A84C]">± Rp 185.000.000</div>
                                </div>
                                <div class="text-right">
                                    <div class="text-[11px] text-[#0B1F4A]/40 uppercase tracking-wider mb-1">Cicilan
                                    </div>
                                    <div class="font-semibold text-[#0B1F4A] text-sm">± Rp 1,1 Jt/bln</div>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-3 mb-5 py-4 border-y border-[#0B1F4A]/06">
                                <div class="text-center">
                                    <div class="text-xl mb-1">🛏</div>
                                    <div class="text-xs font-semibold text-[#0B1F4A]">3 Kamar Tidur</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-xl mb-1">🚿</div>
                                    <div class="text-xs font-semibold text-[#0B1F4A]">1 Kamar Mandi</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-xl mb-1">📐</div>
                                    <div class="text-xs font-semibold text-[#0B1F4A]">LT 72 m²</div>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-2 mb-5">
                                <span v-for="f in ['Listrik 1300W', 'Air PDAM', 'IMB Lengkap', 'SHM', 'Carport']"
                                    :key="f"
                                    class="text-[11px] bg-[#0B1F4A]/05 text-[#0B1F4A]/60 px-2.5 py-1 rounded-sm font-medium">{{
                                    f }}</span>
                            </div>
                            <Link :href="route('booking.create')"
                                class="w-full flex items-center justify-center gap-2 py-3 bg-[#C9A84C] text-[#0B1F4A] text-sm font-semibold rounded-sm hover:bg-[#E2C06A] transition-all">
                                Konsultasi Sekarang
                                <svg width="14" height="14" viewBox="0 0 16 16" fill="none">
                                    <path d="M3 8H13M13 8L9 4M13 8L9 12" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" />
                                </svg>
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Info KPR -->
                <div
                    class="mt-6 bg-[#0B1F4A] rounded-sm px-7 py-5 flex flex-col sm:flex-row items-start sm:items-center gap-4 justify-between">
                    <div class="flex items-center gap-3">
                        <div class="text-2xl">ℹ️</div>
                        <p class="text-white/70 text-sm leading-relaxed">
                            Harga bersifat estimasi dan dapat berubah sesuai kebijakan pemerintah.
                            <span class="text-white font-medium">Persyaratan KPR FLPP berlaku.</span>
                        </p>
                    </div>
                    <Link :href="route('login')"
                        class="flex-shrink-0 text-[#C9A84C] text-sm font-semibold hover:text-[#E2C06A] transition whitespace-nowrap">
                        Cek Persyaratan →
                    </Link>
                </div>
            </div>
        </section>

        <!-- HOW IT WORKS -->
        <section class="max-w-7xl mx-auto px-8 py-20">
            <div class="flex items-center gap-4 mb-3">
                <div class="h-px w-8 bg-[#C9A84C]"></div>
                <span class="text-[#C9A84C] text-xs font-semibold tracking-[3px] uppercase">Alur Layanan</span>
            </div>
            <h2 class="font-display text-4xl font-bold text-[#0B1F4A] mb-14 tracking-tight">Proses yang Mudah &amp;
                Transparan
            </h2>
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 relative">
                <!-- connector line -->
                <div class="hidden lg:block absolute top-7 left-[12.5%] right-[12.5%] h-px bg-[#0B1F4A]/10 z-0">
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-[#C9A84C]/40 to-transparent">
                    </div>
                </div>
                <div v-for="(step, i) in [
                    { num: '01', title: 'Registrasi Akun', desc: 'Daftarkan diri Anda secara gratis melalui platform kami.' },
                    { num: '02', title: 'Pilih Properti', desc: 'Jelajahi katalog dan temukan properti sesuai kebutuhan.' },
                    { num: '03', title: 'Ajukan Booking', desc: 'Isi formulir konsultasi dan pilih jadwal yang tersedia.' },
                    { num: '04', title: 'Tindak Lanjut', desc: 'Tim marketing kami akan menghubungi dan memandu proses.' },
                ]" :key="i" class="relative z-10 text-center">
                    <div
                        class="w-14 h-14 rounded-sm bg-white border-2 border-[#0B1F4A]/10 flex items-center justify-center mx-auto mb-5 shadow-md group-hover:border-[#C9A84C] transition">
                        <span class="font-display text-xl font-bold text-[#C9A84C]">{{ step.num }}</span>
                    </div>
                    <h3 class="font-semibold text-[#0B1F4A] mb-2 text-sm">{{ step.title }}</h3>
                    <p class="text-[#0B1F4A]/50 text-sm leading-relaxed">{{ step.desc }}</p>
                </div>
            </div>
        </section>

        <!-- ✅ TAMBAHAN: CEK BOOKING SECTION -->
        <section class="max-w-7xl mx-auto px-8 pb-16">
            <div class="bg-white border border-[#0B1F4A]/08 rounded-sm overflow-hidden shadow-sm">
                <div class="flex flex-col lg:flex-row items-stretch">
                    <!-- Left: Info -->
                    <div class="bg-[#F8F5EE] border-r border-[#C9A84C]/20 px-8 py-10 lg:w-64 flex-shrink-0 flex flex-col justify-center">
                        <div class="w-12 h-12 bg-[#C9A84C]/15 rounded-sm flex items-center justify-center text-2xl mb-4">
                            🔎
                        </div>
                        <h3 class="font-display text-2xl font-bold text-[#0B1F4A] mb-2 leading-tight">
                            Sudah Booking?
                        </h3>
                        <p class="text-[#0B1F4A]/50 text-sm leading-relaxed">
                            Masukkan nomor booking untuk melihat status dan detail jadwal konsultasi Anda.
                        </p>
                    </div>

                    <!-- Right: CTA -->
                    <div class="flex-1 px-8 py-10 flex flex-col sm:flex-row items-center gap-6 justify-between">
                        <div>
                            <p class="text-[#0B1F4A] font-semibold text-base mb-1">
                                Pantau Status Booking Anda
                            </p>
                            <p class="text-[#0B1F4A]/40 text-sm">
                                Tidak perlu login — cukup masukkan kode booking yang Anda terima.
                            </p>
                        </div>
                        <Link
                            href="/cek-booking"
                            class="flex-shrink-0 inline-flex items-center gap-2.5 px-7 py-3.5 bg-[#0B1F4A] text-white font-semibold text-sm rounded-sm hover:bg-[#163472] transition shadow-md whitespace-nowrap"
                        >
                            Cek Status Booking
                            <svg width="14" height="14" viewBox="0 0 16 16" fill="none">
                                <path d="M3 8H13M13 8L9 4M13 8L9 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA BANNER -->
        <section class="max-w-7xl mx-auto px-8 pb-20">
            <div class="bg-[#0B1F4A] rounded-sm overflow-hidden relative">
                <div class="absolute right-0 top-0 w-96 h-full bg-[#163472] opacity-50 skew-x-[-15deg] translate-x-24">
                </div>
                <div
                    class="absolute right-8 top-1/2 -translate-y-1/2 text-[10rem] opacity-[0.06] leading-none select-none pointer-events-none text-white">
                    IKK</div>
                <div
                    class="relative px-10 py-12 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-8">
                    <div>
                        <div class="flex items-center gap-3 mb-3">
                            <div class="h-px w-8 bg-[#C9A84C]"></div>
                            <span class="text-[#C9A84C] text-xs font-semibold tracking-[3px] uppercase">Mulai
                                Sekarang</span>
                        </div>
                        <h2 class="font-display text-3xl lg:text-4xl font-bold text-white tracking-tight leading-tight">
                            Wujudkan Investasi Properti<br />
                            <span class="italic text-[#C9A84C]">Anda Bersama Kami</span>
                        </h2>
                        <p class="text-white/50 mt-3 max-w-lg text-sm leading-relaxed">
                            Hubungi tim profesional PT Insan Kapuas Khatulistiwa dan dapatkan
                            konsultasi properti yang tepat sasaran.
                        </p>
                    </div>
                    <!-- <div class="flex flex-col sm:flex-row gap-3 flex-shrink-0">
                        <Link v-if="!$page.props.auth.user && canRegister" :href="route('register')"
                            class="inline-flex items-center gap-2 px-7 py-3.5 bg-[#C9A84C] text-[#0B1F4A] font-semibold text-sm rounded-sm hover:bg-[#E2C06A] transition whitespace-nowrap shadow-lg">
                            Daftar Gratis
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M3 8H13M13 8L9 4M13 8L9 12" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" />
                            </svg>
                        </Link>
                        <Link v-if="!$page.props.auth.user" :href="route('login')"
                            class="inline-flex items-center px-7 py-3.5 border border-white/20 text-white font-semibold text-sm rounded-sm hover:bg-white/08 transition whitespace-nowrap">
                            Masuk Akun
                        </Link>
                        <Link v-if="$page.props.auth.user" :href="route('dashboard')"
                            class="inline-flex items-center gap-2 px-7 py-3.5 bg-[#C9A84C] text-[#0B1F4A] font-semibold text-sm rounded-sm hover:bg-[#E2C06A] transition whitespace-nowrap shadow-lg">
                            Buka Dashboard →
                        </Link>
                    </div> -->
                </div>
            </div>
        </section>

        <!-- FOOTER -->
        <footer class="bg-[#080F22] border-t border-white/05">
            <div class="max-w-7xl mx-auto px-8 py-10">
                <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-8 mb-8">
                    <div>
                        <div class="flex items-center gap-3 mb-3">
                            <div
                                class="w-8 h-8 bg-[#0B1F4A] border border-white/10 flex items-center justify-center rounded-sm">
                                <svg width="16" height="16" viewBox="0 0 18 18" fill="none">
                                    <rect x="1" y="8" width="7" height="9" fill="#C9A84C" />
                                    <rect x="10" y="4" width="7" height="13" fill="white" opacity="0.7" />
                                    <polygon points="0,8 9,1 18,4 18,4 10,4 9,2 1,8" fill="#C9A84C" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-[10px] text-[#C9A84C] font-semibold tracking-[2px] uppercase">PT Insan
                                    Kapuas
                                </div>
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
                            <div class="text-white/60 font-semibold mb-3 text-xs uppercase tracking-wider">Platform
                            </div>
                            <div class="flex flex-col gap-2">
                                <Link href="/booking" class="hover:text-[#C9A84C] transition">Booking Konsultasi</Link>
                                <!-- ✅ TAMBAHAN: Link cek booking di footer -->
                                <Link href="/cek-booking" class="hover:text-[#C9A84C] transition">Cek Status Booking</Link>
                                <span>Katalog Properti</span>
                            </div>
                        </div>
                        <div>
                            <div class="text-white/60 font-semibold mb-3 text-xs uppercase tracking-wider">Perusahaan
                            </div>
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
                    <p class="text-white/15 text-xs font-mono">Laravel v{{ laravelVersion }} · PHP v{{ phpVersion }}</p>
                </div>
            </div>
        </footer>

    </div>
</template>