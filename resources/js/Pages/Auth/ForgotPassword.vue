<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
  status: {
    type: String,
  },
});

const form = useForm({
  email: '',
});

const submit = () => {
  form.post(route('password.email'));
};
</script>

<template>
  <GuestLayout>
    <Head title="Forgot Password" />
    
    <h2>Reset Password</h2>
    <p class="subtitle mb-6 text-[0.95rem]">
      Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
    </p>

    <div v-if="status" class="mb-5 rounded-xl bg-green-50/50 p-4 text-sm font-bold text-green-600 dark:bg-green-500/10 dark:text-green-400 border border-green-200 dark:border-green-500/20">
      {{ status }}
    </div>

    <form @submit.prevent="submit" class="space-y-4">
      <div>
        <InputLabel for="email" value="Email Address" />
        <TextInput
          id="email"
          type="email"
          v-model="form.email"
          required
          autofocus
          autocomplete="username"
          placeholder="name@example.com"
        />
        <InputError class="mt-2 text-sm text-red-500" :message="form.errors.email" />
      </div>

      <button
        type="submit"
        :class="{ 'opacity-50': form.processing }"
        :disabled="form.processing"
      >
        <span v-if="form.processing" class="material-symbols-outlined animate-spin text-[20px] mr-2">progress_activity</span>
        {{ form.processing ? 'Sending Link...' : 'Email Password Reset Link' }}
      </button>

      <div class="text-center mt-6">
        <Link :href="route('login')" class="text-sm">Back to login</Link>
      </div>
    </form>
  </GuestLayout>
</template>
