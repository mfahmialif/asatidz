<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
});

const submit = () => {
  form.post(route('register'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  });
};
</script>

<template>
  <GuestLayout>
    <Head title="Register" />
    
    <h2>Create Account</h2>
    <p class="subtitle">Sign up to get started with Asatidz portal.</p>

    <form @submit.prevent="submit" class="space-y-4">
      <div>
        <InputLabel for="name" value="Full Name" />
        <TextInput
          id="name"
          type="text"
          v-model="form.name"
          required
          autofocus
          autocomplete="name"
          placeholder="John Doe"
        />
        <InputError class="mt-2 text-sm text-red-500" :message="form.errors.name" />
      </div>

      <div>
        <InputLabel for="email" value="Email Address" />
        <TextInput
          id="email"
          type="email"
          v-model="form.email"
          required
          autocomplete="username"
          placeholder="name@example.com"
        />
        <InputError class="mt-2 text-sm text-red-500" :message="form.errors.email" />
      </div>

      <div>
        <InputLabel for="password" value="Password" />
        <TextInput
          id="password"
          type="password"
          v-model="form.password"
          required
          autocomplete="new-password"
          placeholder="Create a strong password"
        />
        <InputError class="mt-2 text-sm text-red-500" :message="form.errors.password" />
      </div>

      <div>
        <InputLabel for="password_confirmation" value="Confirm Password" />
        <TextInput
          id="password_confirmation"
          type="password"
          v-model="form.password_confirmation"
          required
          autocomplete="new-password"
          placeholder="Repeat your password"
        />
        <InputError class="mt-2 text-sm text-red-500" :message="form.errors.password_confirmation" />
      </div>

      <button
        type="submit"
        :class="{ 'opacity-50': form.processing }"
        :disabled="form.processing"
      >
        <span v-if="form.processing" class="material-symbols-outlined animate-spin text-[20px] mr-2">progress_activity</span>
        {{ form.processing ? 'Creating account...' : 'Sign Up' }}
      </button>

      <div class="text-center mt-6">
        <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">
          Already have an account? 
          <Link :href="route('login')" class="ml-1">Sign in</Link>
        </p>
      </div>
    </form>
  </GuestLayout>
</template>
