<script setup>
import flatpickr from 'flatpickr'
import 'flatpickr/dist/flatpickr.css'
import { Head, useForm, usePage } from '@inertiajs/vue3'
import { ref, watch, onMounted, computed } from 'vue'
import axios from 'axios'

const page = usePage()

const props = defineProps({
    jamList: Array,
    categories: Array,
    properties: Array,
})

const selectedCategory = ref(null)
const filteredProperties = computed(() => {

    if (!selectedCategory.value)
        return []

    return props.properties.filter(item =>
        item.kategori_id == selectedCategory.value
    )

})

const bookedSlots = ref([])

const form = useForm({
    nama: '',
    email: '',
    no_hp: '',
    properti_id: '',
    tanggal_konsultasi: '',
    jam_konsultasi: '',
    catatan: ''
})

/*
|--------------------------------------------------------------------------
| FETCH SLOT SAAT TANGGAL DIPILIH
|--------------------------------------------------------------------------
*/
watch(() => form.tanggal_konsultasi, async (val) => {
    if (!val) return

    const res = await axios.get('/booking-slots', {
        params: { tanggal: val }
    })

    bookedSlots.value = res.data.booked
})

onMounted(() => {
    flatpickr("#tanggal", {
        dateFormat: "Y-m-d",
    })
})

const submit = () => {
    form.post(route('booking.store'), {
        onSuccess: () => {
            form.reset()
            bookedSlots.value = []
            window.scrollTo({ top: 0, behavior: 'smooth' })
        }
    })
}
</script>

<template>

    <Head title="Booking Konsultasi" />

    <div class="min-h-screen bg-[#F4F6FA]">

        <!-- HEADER -->
        <div class="bg-[#0B1F4A] text-white py-12 px-6">
            <div class="max-w-4xl mx-auto">
                <h1 class="text-3xl font-bold">
                    Booking Konsultasi
                </h1>
                <p class="text-white/60 mt-2 text-sm">
                    Pilih tanggal & jam yang tersedia
                </p>
            </div>
        </div>

        <div class="max-w-4xl mx-auto px-6 py-10">

            <!-- SUCCESS -->
            <div v-if="page.props.flash?.success" class="mb-6 bg-green-100 text-green-700 p-4 rounded-lg">
                <b>{{ page.props.flash.success }}</b>
                <div v-if="page.props.flash.booking_code" class="mt-2 text-sm space-y-1">
                    <p>Kode Booking: <b>{{ page.props.flash.booking_code }}</b></p>
                    <p v-if="page.props.flash.booking_data">
                        Nama: {{ page.props.flash.booking_data.nama }} —
                        {{ page.props.flash.booking_data.tanggal }},
                        {{ page.props.flash.booking_data.jam }}
                    </p>
                </div>
            </div>

            <!-- ERROR VALIDASI (GENERIK) -->
            <div v-if="Object.keys(form.errors).length" class="mb-6 bg-red-100 text-red-700 p-4 rounded-lg">
                <b>Booking gagal, cek kembali data berikut:</b>
                <ul class="list-disc list-inside text-sm mt-1">
                    <li v-for="(error, key) in form.errors" :key="key">{{ error }}</li>
                </ul>
            </div>

            <!-- FORM -->
            <div class="bg-white shadow-lg rounded-xl p-8">

                <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Nama -->
                    <div>
                        <label>Nama</label>
                        <input v-model="form.nama" class="input" :class="{ 'border-red-500': form.errors.nama }" />
                        <div v-if="form.errors.nama" class="text-red-500 text-sm mt-1">
                            {{ form.errors.nama }}
                        </div>
                    </div>

                    <!-- HP -->
                    <div>
                        <label>No HP</label>
                        <input v-model="form.no_hp" class="input" :class="{ 'border-red-500': form.errors.no_hp }" />
                        <div v-if="form.errors.no_hp" class="text-red-500 text-sm mt-1">
                            {{ form.errors.no_hp }}
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="md:col-span-2">
                        <label>Email</label>
                        <input v-model="form.email" type="email" class="input"
                            :class="{ 'border-red-500': form.errors.email }" />
                        <div v-if="form.errors.email" class="text-red-500 text-sm mt-1">
                            {{ form.errors.email }}
                        </div>
                    </div>

                    <!-- Properti -->
                    <div class="md:col-span-2">

                        <label class="font-semibold">
                            Pilih Tipe Rumah
                        </label>

                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-3">

                            <div v-for="category in categories" :key="category.id"
                                @click="selectedCategory = category.id"
                                class="cursor-pointer border rounded-xl p-5 transition" :class="selectedCategory == category.id
                                    ? 'border-[#C9A84C] bg-yellow-50'
                                    : 'hover:border-[#C9A84C]'">

                                <h3 class="font-bold">

                                    {{ category.nama_kategori }}

                                </h3>

                                <p class="text-sm text-gray-500">

                                    {{ category.deskripsi }}

                                </p>

                            </div>

                        </div>

                    </div>
                    <div v-if="selectedCategory" class="md:col-span-2">

                        <label class="font-semibold">
                            Pilih Properti
                        </label>

                        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5 mt-4">

                            <div v-for="property in filteredProperties" :key="property.id"
                                @click="form.properti_id = property.id"
                                class="cursor-pointer border rounded-xl overflow-hidden bg-white transition" :class="form.properti_id == property.id
                                    ? 'border-[#C9A84C] ring-2 ring-[#C9A84C]'
                                    : 'hover:shadow-lg'">

                                <img v-if="property.gambar" :src="'/storage/' + property.gambar"
                                    class="w-full h-44 object-cover">

                                <div v-else class="w-full h-44 bg-gray-100 flex items-center justify-center">

                                    No Image

                                </div>

                                <div class="p-4">

                                    <h3 class="font-bold">

                                        {{ property.nama_properti }}

                                    </h3>

                                    <p class="text-sm text-gray-500 mt-2">

                                        📍 {{ property.lokasi }}

                                    </p>

                                    <div class="mt-3 font-bold text-[#C9A84C]">

                                        Rp {{ Number(property.harga).toLocaleString('id-ID') }}

                                    </div>

                                    <div v-if="form.properti_id == property.id"
                                        class="mt-3 text-green-600 font-semibold">

                                        ✓ Properti Dipilih

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- Tanggal -->
                    <div>
                        <label>Tanggal</label>
                        <input id="tanggal" v-model="form.tanggal_konsultasi" class="w-full mt-1 border rounded-lg p-2"
                            :class="{ 'border-red-500': form.errors.tanggal_konsultasi }" />
                        <div v-if="form.errors.tanggal_konsultasi" class="text-red-500 text-sm mt-1">
                            {{ form.errors.tanggal_konsultasi }}
                        </div>
                    </div>

                    <!-- JAM SLOT -->
                    <div class="md:col-span-2">
                        <label>Pilih Jam</label>

                        <div class="grid grid-cols-3 gap-2 mt-2">

                            <button v-for="jam in jamList" :key="jam" type="button" @click="form.jam_konsultasi = jam"
                                :disabled="bookedSlots.includes(jam)" class="p-2 rounded border text-sm" :class="[
                                    form.jam_konsultasi === jam
                                        ? 'bg-[#0B1F4A] text-white'
                                        : 'bg-white',

                                    bookedSlots.includes(jam)
                                        ? 'opacity-30 cursor-not-allowed'
                                        : 'hover:bg-[#C9A84C]'
                                ]">
                                {{ jam }}
                            </button>

                        </div>

                        <div v-if="form.errors.jam_konsultasi" class="text-red-500 text-sm mt-2">
                            {{ form.errors.jam_konsultasi }}
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div class="md:col-span-2">
                        <textarea v-model="form.catatan" class="input"></textarea>
                    </div>

                    <!-- BUTTON -->
                    <div class="md:col-span-2">
                        <button :disabled="form.processing" class="w-full bg-[#C9A84C] py-3 rounded">
                            Booking Sekarang
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</template>

<style>
.input {
    @apply w-full mt-1 border rounded-lg p-2;
}
</style>