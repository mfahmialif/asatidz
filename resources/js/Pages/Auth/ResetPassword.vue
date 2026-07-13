<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
  email: {
    type: String,
    required: true,
  },
  token: {
    type: String,
    required: true,
  },
});

const form = useForm({
  token: props.token,
  email: props.email,
  password: '',
  password_confirmation: '',
});

const submit = () => {
  form.post(route('password.store'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  });
};
</script>

<template>
  <GuestLayout>
    <Head title="Reset Password" />
    
    <h2>Set New Password</h2>
    <p class="subtitle mb-6 text-[0.95rem]">
      Please enter your new password below.
    </p>

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
          readonly
          class="opacity-70 cursor-not-allowed"
        />
        <InputError class="mt-2 text-sm text-red-500" :message="form.errors.email" />
      </div>

      <div>
        <InputLabel for="password" value="New Password" />
        <TextInput
          id="password"
          type="password"
          v-model="form.password"
          required
          autocomplete="new-password"
          placeholder="Create new password"
        />
        <InputError class="mt-2 text-sm text-red-500" :message="form.errors.password" />
      </div>

      <div>
        <InputLabel for="password_confirmation" value="Confirm New Password" />
        <TextInput
          id="password_confirmation"
          type="password"
          v-model="form.password_confirmation"
          required
          autocomplete="new-password"
          placeholder="Repeat new password"
        />
        <InputError class="mt-2 text-sm text-red-500" :message="form.errors.password_confirmation" />
      </div>

      <button
        type="submit"
        :class="{ 'opacity-50': form.processing }"
        :disabled="form.processing"
      >
        <span v-if="form.processing" class="material-symbols-outlined animate-spin text-[20px] mr-2">progress_activity</span>
        {{ form.processing ? 'Resetting password...' : 'Reset Password' }}
      </button>
    </form>
  </GuestLayout>
</template>
