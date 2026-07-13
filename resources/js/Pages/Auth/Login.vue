<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
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
    <Head title="Log in" />
    
    <h2>Welcome Back!</h2>
    <p class="subtitle">Please sign in to access your account dashboard.</p>

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

      <div>
        <div class="flex items-center justify-between">
            <InputLabel for="password" value="Password" />
            <Link
              v-if="canResetPassword"
              :href="route('password.request')"
              class="text-xs mb-[0.4rem] tracking-wide"
            >
              Forgot password?
            </Link>
        </div>
        <TextInput
          id="password"
          type="password"
          v-model="form.password"
          required
          autocomplete="current-password"
          placeholder="Enter your password"
        />
        <InputError class="mt-2 text-sm text-red-500" :message="form.errors.password" />
      </div>

      <div class="flex items-center justify-between pt-2 pb-1">
        <label class="flex items-center gap-2 cursor-pointer group !m-0 !text-sm">
          <input type="checkbox" name="remember" v-model="form.remember" class="transition-shadow" />
          <span class="text-slate-600 dark:text-slate-300 font-medium group-hover:text-sky-600 dark:group-hover:text-sky-400 transition-colors">Remember me</span>
        </label>
      </div>

      <button
        type="submit"
        :class="{ 'opacity-50': form.processing }"
        :disabled="form.processing"
      >
        <span v-if="form.processing" class="material-symbols-outlined animate-spin text-[20px] mr-2">progress_activity</span>
        {{ form.processing ? 'Signing in...' : 'Sign In' }}
      </button>

      <div class="text-center mt-6">
        <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">
          Don't have an account? 
          <Link :href="route('register')" class="ml-1">Sign up</Link>
        </p>
      </div>
    </form>
  </GuestLayout>
</template>
