<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Login" />

        <div class="min-h-screen flex items-center justify-center bg-[#0B1F4A] relative overflow-hidden">

            <!-- Background Accent -->
            <div class="absolute inset-0 opacity-10 bg-[radial-gradient(circle_at_center,_#C9A84C,_transparent_70%)]"></div>

            <!-- Card -->
            <div class="w-full max-w-md bg-white rounded-sm shadow-2xl p-8 relative z-10">

                <!-- Header -->
                <div class="mb-6 text-center">
                    <h1 class="text-3xl font-serif text-[#0B1F4A]">
                        Welcome Back
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">
                        Login to your account
                    </p>
                </div>

                <!-- Status -->
                <div v-if="status" class="mb-4 text-sm font-medium text-green-600 text-center">
                    {{ status }}
                </div>

                <!-- Form -->
                <form @submit.prevent="submit">

                    <!-- Email -->
                    <div>
                        <InputLabel for="email" value="Email" class="text-[#0B1F4A]" />
                        <TextInput
                            id="email"
                            type="email"
                            class="mt-1 block w-full border-gray-300 focus:border-[#C9A84C] focus:ring-[#C9A84C]"
                            v-model="form.email"
                            required
                            autofocus
                            autocomplete="username"
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <InputLabel for="password" value="Password" class="text-[#0B1F4A]" />
                        <TextInput
                            id="password"
                            type="password"
                            class="mt-1 block w-full border-gray-300 focus:border-[#C9A84C] focus:ring-[#C9A84C]"
                            v-model="form.password"
                            required
                            autocomplete="current-password"
                        />
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <!-- Remember -->
                    <div class="mt-4 flex items-center justify-between">
                        <label class="flex items-center">
                            <Checkbox name="remember" v-model:checked="form.remember" />
                            <span class="ms-2 text-sm text-gray-600">Remember me</span>
                        </label>

                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="text-sm text-[#0B1F4A] hover:text-[#C9A84C]"
                        >
                            Forgot?
                        </Link>
                    </div>

                    <!-- Button -->
                    <div class="mt-6">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full bg-[#C9A84C] text-[#0B1F4A] py-2 rounded-sm font-semibold hover:bg-[#E2C06A] transition duration-200 disabled:opacity-50"
                        >
                            Log in
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </GuestLayout>
</template>
