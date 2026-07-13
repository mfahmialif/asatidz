<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
  password: '',
});

const submit = () => {
  form.post(route('password.confirm'), {
    onFinish: () => form.reset(),
  });
};
</script>

<template>
  <GuestLayout>
    <Head title="Confirm Password" />
    
    <h2>Confirm Password</h2>
    <p class="subtitle mb-6 text-[0.95rem]">
      This is a secure area of the application. Please confirm your password before continuing.
    </p>

    <form @submit.prevent="submit" class="space-y-4">
      <div>
        <InputLabel for="password" value="Password" />
        <TextInput
          id="password"
          type="password"
          v-model="form.password"
          required
          autocomplete="current-password"
          autofocus
          placeholder="Enter your password"
        />
        <InputError class="mt-2 text-sm text-red-500" :message="form.errors.password" />
      </div>

      <button
        type="submit"
        :class="{ 'opacity-50': form.processing }"
        :disabled="form.processing"
      >
        <span v-if="form.processing" class="material-symbols-outlined animate-spin text-[20px] mr-2">progress_activity</span>
        {{ form.processing ? 'Confirming...' : 'Confirm' }}
      </button>
    </form>
  </GuestLayout>
</template>
