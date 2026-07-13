<script setup>
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
  status: {
    type: String,
  },
});

const form = useForm({});

const submit = () => {
  form.post(route('verification.send'));
};

const verificationLinkSent = computed(
  () => props.status === 'verification-link-sent',
);
</script>

<template>
  <GuestLayout>
    <Head title="Email Verification" />
    
    <h2>Verify Email</h2>
    <p class="subtitle mb-6 text-[0.95rem]">
      Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
    </p>

    <div v-if="verificationLinkSent" class="mb-5 rounded-xl bg-green-50/50 p-4 text-sm font-bold text-green-600 dark:bg-green-500/10 dark:text-green-400 border border-green-200 dark:border-green-500/20">
      A new verification link has been sent to the email address you provided during registration.
    </div>

    <form @submit.prevent="submit">
      <button
        type="submit"
        :class="{ 'opacity-50': form.processing }"
        :disabled="form.processing"
      >
        <span v-if="form.processing" class="material-symbols-outlined animate-spin text-[20px] mr-2">progress_activity</span>
        {{ form.processing ? 'Sending...' : 'Resend Verification Email' }}
      </button>

      <div class="text-center mt-6 flex items-center justify-center gap-4">
        <Link
          :href="route('profile.show')"
          class="text-sm font-medium"
        >
          Edit Profile
        </Link>
        <div class="w-1 h-1 rounded-full bg-slate-300 dark:bg-slate-600"></div>
        <Link
          :href="route('logout')"
          method="post"
          as="button"
          class="text-sm font-medium hover:text-red-500 dark:hover:text-red-400 !text-slate-600 dark:!text-slate-400 transition-colors"
        >
          Log Out
        </Link>
      </div>
    </form>
  </GuestLayout>
</template>
