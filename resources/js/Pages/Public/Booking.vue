<script setup>
import flatpickr from 'flatpickr'
import 'flatpickr/dist/flatpickr.css'
import { Head, useForm, usePage } from '@inertiajs/vue3'
import { ref, watch, onMounted } from 'vue'
import axios from 'axios'

const page = usePage()

const props = defineProps({
    jamList: Array
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
        minDate: "today",
        dateFormat: "Y-m-d",
    })
})

const submit = () => {
    console.log(form) // 👈 cek ini
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
                <div v-if="page.props.flash.booking_code">
                    Kode: <b>{{ page.props.flash.booking_code }}</b>
                </div>
            </div>

            <!-- FORM -->
            <div class="bg-white shadow-lg rounded-xl p-8">

                <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Nama -->
                    <div>
                        <label>Nama</label>
                        <input v-model="form.nama" class="input" />
                    </div>

                    <!-- HP -->
                    <div>
                        <label>No HP</label>
                        <input v-model="form.no_hp" class="input" />
                    </div>

                    <!-- Email -->
                    <div class="md:col-span-2">
                        <label>Email</label>
                        <input v-model="form.email" class="input" />
                    </div>

                    <!-- Properti -->
                    <div>
                        <label>Properti</label>
                        <select v-model="form.properti_id" class="input">
                            <option value="">-- pilih --</option>
                            <option value="1">Tipe 36</option>
                            <option value="2">Tipe 46</option>
                        </select>
                    </div>

                    <!-- Tanggal -->
                    <div>
                        <label>Tanggal</label>
                        <input id="tanggal" v-model="form.tanggal_konsultasi"
                            class="w-full mt-1 border rounded-lg p-2" />
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