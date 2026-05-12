<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Link, useForm } from '@inertiajs/vue3'

const props = defineProps({
    client: Object,
})

const form = useForm({
    nama:  props.client.nama,
    email: props.client.email ?? '',
    no_hp: props.client.no_hp,
})

const submit = () => {
    form.patch(route('admin.clients.update', props.client.id))
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="space-y-6 max-w-xl">

            <!-- Back + Header -->
            <div>
                <Link
                    :href="route('admin.clients.index')"
                    class="text-sm text-gray-500 hover:text-[#0B1F4A] flex items-center gap-1 mb-2 transition-colors"
                >
                    ← Kembali ke Daftar Client
                </Link>
                <h1 class="text-2xl font-bold text-[#0B1F4A]">Edit Client</h1>
                <p class="text-sm text-gray-500 mt-0.5">Perbarui data klien</p>
            </div>

            <!-- Form -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-5 py-3.5 bg-[#0B1F4A] text-white text-sm font-semibold">
                    👤 Data Client
                </div>
                <form @submit.prevent="submit" class="p-5 space-y-4">

                    <!-- Nama -->
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">
                            Nama <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="form.nama"
                            type="text"
                            class="w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C9A84C]/40 focus:border-[#C9A84C]"
                            :class="form.errors.nama ? 'border-red-400' : 'border-gray-200'"
                        />
                        <p v-if="form.errors.nama" class="text-xs text-red-500 mt-1">{{ form.errors.nama }}</p>
                    </div>

                    <!-- No HP -->
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">
                            No. HP <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="form.no_hp"
                            type="text"
                            class="w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C9A84C]/40 focus:border-[#C9A84C]"
                            :class="form.errors.no_hp ? 'border-red-400' : 'border-gray-200'"
                        />
                        <p v-if="form.errors.no_hp" class="text-xs text-red-500 mt-1">{{ form.errors.no_hp }}</p>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Email</label>
                        <input
                            v-model="form.email"
                            type="email"
                            class="w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C9A84C]/40 focus:border-[#C9A84C]"
                            :class="form.errors.email ? 'border-red-400' : 'border-gray-200'"
                        />
                        <p v-if="form.errors.email" class="text-xs text-red-500 mt-1">{{ form.errors.email }}</p>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-3 pt-2">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-5 py-2.5 rounded-lg text-sm font-semibold bg-[#0B1F4A] text-white hover:bg-[#0d2660] transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                        </button>
                        <Link
                            :href="route('admin.clients.index')"
                            class="px-5 py-2.5 rounded-lg text-sm font-medium text-gray-600 border border-gray-200 hover:bg-gray-50 transition-colors"
                        >
                            Batal
                        </Link>
                    </div>

                </form>
            </div>

        </div>
    </AuthenticatedLayout>
</template>