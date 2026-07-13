<template>
 <div class="flex flex-col gap-6 max-w-2xl mx-auto">
  <!-- PROFILE HEADER CARD -->
  <div class="profile-header rounded-3xl p-6 lg:p-8 relative overflow-hidden flex flex-col items-center text-center shadow-lg">
   <div class="absolute inset-0 opacity-15" style="background: linear-gradient(135deg, var(--color-accent) 0%, transparent 80%)"></div>
   <div class="relative z-10 w-full flex flex-col items-center">
    <!-- Avatar -->
    <div class="relative mb-4">
     <div class="w-24 h-24 lg:w-28 lg:h-28 rounded-full border-4 border-accent flex items-center justify-center shadow-[0_0_25px_rgba(37, 99, 235,0.3)] bg-[var(--bg-input)]">
      <span class="material-symbols-outlined text-accent text-[3rem] lg:text-[4rem]">person</span>
     </div>
    </div>
    <!-- Info -->
    <h2 class="text-2xl font-bold mb-1" style="color: var(--text-heading)">{{ userData.name }}</h2>
    <p class="text-sm mb-4" style="color: var(--text-muted)">{{ userData.email }}</p>
    
    <div class="flex flex-wrap items-center justify-center gap-2 mb-2">
     <span class="app-badge app-badge--blue gap-1.5 px-3 py-1.5 rounded-full bg-blue-500/10 text-blue-500 text-xs font-bold flex items-center border border-blue-500/20">
      <span class="material-symbols-outlined text-[14px]">school</span>
      Asatidz
     </span>
     <span class="app-badge app-badge--success gap-1.5 px-3 py-1.5 rounded-full bg-green-500/10 text-green-500 text-xs font-bold flex items-center border border-green-500/20">
      <span class="w-1.5 h-1.5 rounded-full animate-pulse bg-green-500"></span>
      Online
     </span>
    </div>
   </div>
  </div>

  <!-- INFO & EDIT FORM -->
  <div class="info-card rounded-2xl overflow-hidden shadow-lg border border-[var(--border)] bg-[var(--bg-card)]">
   <div class="card-header px-5 lg:px-6 py-4 flex items-center justify-between border-b border-[var(--border)]">
    <h3 class="font-bold flex items-center gap-2" style="color: var(--text-heading)">
     <span class="material-symbols-outlined text-accent text-[20px]">manage_accounts</span>
     Informasi Akun
    </h3>
    <button @click="toggleEdit" class="text-accent text-sm font-bold flex items-center gap-1 hover:underline">
     <span class="material-symbols-outlined text-[16px]">{{ isEditing ? 'close' : 'edit' }}</span>
     {{ isEditing ? 'Batal' : 'Edit Profil' }}
    </button>
   </div>

   <div class="p-5 lg:p-6">
    <form @submit.prevent="submitProfile" class="flex flex-col gap-5">
     <!-- Nama -->
     <div class="flex flex-col gap-1.5">
      <label class="text-sm font-medium" style="color: var(--text-body)">Nama Lengkap</label>
      <input v-if="isEditing" v-model="form.name" type="text" required class="filter-input w-full rounded-xl py-3 px-4 text-sm focus:outline-none focus:ring-1 focus:ring-accent bg-[var(--bg-input)] text-[var(--text-heading)] border border-[var(--border)]" />
      <div v-else class="py-3 text-sm font-bold" style="color: var(--text-heading)">{{ userData.name }}</div>
      <div v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</div>
     </div>

     <!-- Email -->
     <div class="flex flex-col gap-1.5">
      <label class="text-sm font-medium" style="color: var(--text-body)">Email</label>
      <input v-if="isEditing" v-model="form.email" type="email" required class="filter-input w-full rounded-xl py-3 px-4 text-sm focus:outline-none focus:ring-1 focus:ring-accent bg-[var(--bg-input)] text-[var(--text-heading)] border border-[var(--border)]" />
      <div v-else class="py-3 text-sm font-bold" style="color: var(--text-heading)">{{ userData.email }}</div>
      <div v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</div>
     </div>

     <!-- No Telepon -->
     <div class="flex flex-col gap-1.5">
      <label class="text-sm font-medium" style="color: var(--text-body)">Nomor Telepon</label>
      <input v-if="isEditing" v-model="form.no_telepon" type="text" placeholder="Contoh: 08123456789" class="filter-input w-full rounded-xl py-3 px-4 text-sm focus:outline-none focus:ring-1 focus:ring-accent bg-[var(--bg-input)] text-[var(--text-heading)] border border-[var(--border)]" />
      <div v-else class="py-3 text-sm font-bold" style="color: var(--text-heading)">{{ userData.asatidz?.no_telepon || '-' }}</div>
      <div v-if="form.errors.no_telepon" class="text-red-500 text-xs mt-1">{{ form.errors.no_telepon }}</div>
     </div>

     <!-- Alamat -->
     <div class="flex flex-col gap-1.5">
      <label class="text-sm font-medium" style="color: var(--text-body)">Alamat Lengkap</label>
      <textarea v-if="isEditing" v-model="form.alamat" rows="3" placeholder="Masukkan alamat lengkap..." class="filter-input w-full rounded-xl py-3 px-4 text-sm focus:outline-none focus:ring-1 focus:ring-accent bg-[var(--bg-input)] text-[var(--text-heading)] border border-[var(--border)] resize-none"></textarea>
      <div v-else class="py-3 text-sm font-bold" style="color: var(--text-heading)">{{ userData.asatidz?.alamat || '-' }}</div>
      <div v-if="form.errors.alamat" class="text-red-500 text-xs mt-1">{{ form.errors.alamat }}</div>
     </div>

     <!-- Submit Action -->
     <Transition name="fade">
      <div v-if="isEditing" class="mt-4 flex justify-end">
       <button type="submit" :disabled="form.processing" class="bg-accent text-white px-6 py-3 rounded-xl font-bold w-full sm:w-auto shadow-lg hover:shadow-xl transition-all hover:scale-[1.02] active:scale-[0.98] disabled:opacity-70 disabled:cursor-not-allowed flex items-center justify-center gap-2">
        <span v-if="form.processing" class="material-symbols-outlined animate-spin text-[18px]">progress_activity</span>
        <span v-else class="material-symbols-outlined text-[18px]">save</span>
        Simpan Perubahan
       </button>
      </div>
     </Transition>
    </form>
   </div>
  </div>

  <!-- UBAH PASSWORD -->
  <div class="info-card rounded-2xl overflow-hidden shadow-lg border border-[var(--border)] bg-[var(--bg-card)]">
   <div class="card-header px-5 lg:px-6 py-4 flex items-center justify-between border-b border-[var(--border)]">
    <h3 class="font-bold flex items-center gap-2" style="color: var(--text-heading)">
     <span class="material-symbols-outlined text-accent text-[20px]">lock</span>
     Ubah Password
    </h3>
   </div>
   <div class="p-5 lg:p-6">
    <form @submit.prevent="submitPassword" class="flex flex-col gap-5">
     <!-- Password Saat Ini -->
     <div class="flex flex-col gap-1.5">
      <label class="text-sm font-medium" style="color: var(--text-body)">Password Saat Ini</label>
      <input v-model="pwdForm.current_password" type="password" required class="filter-input w-full rounded-xl py-3 px-4 text-sm focus:outline-none focus:ring-1 focus:ring-accent bg-[var(--bg-input)] text-[var(--text-heading)] border border-[var(--border)]" />
      <div v-if="pwdForm.errors.current_password" class="text-red-500 text-xs mt-1">{{ pwdForm.errors.current_password }}</div>
     </div>

     <!-- Password Baru -->
     <div class="flex flex-col gap-1.5">
      <label class="text-sm font-medium" style="color: var(--text-body)">Password Baru</label>
      <input v-model="pwdForm.password" type="password" required class="filter-input w-full rounded-xl py-3 px-4 text-sm focus:outline-none focus:ring-1 focus:ring-accent bg-[var(--bg-input)] text-[var(--text-heading)] border border-[var(--border)]" />
      <div v-if="pwdForm.errors.password" class="text-red-500 text-xs mt-1">{{ pwdForm.errors.password }}</div>
     </div>

     <!-- Konfirmasi Password -->
     <div class="flex flex-col gap-1.5">
      <label class="text-sm font-medium" style="color: var(--text-body)">Konfirmasi Password</label>
      <input v-model="pwdForm.password_confirmation" type="password" required class="filter-input w-full rounded-xl py-3 px-4 text-sm focus:outline-none focus:ring-1 focus:ring-accent bg-[var(--bg-input)] text-[var(--text-heading)] border border-[var(--border)]" />
      <div v-if="pwdForm.errors.password_confirmation" class="text-red-500 text-xs mt-1">{{ pwdForm.errors.password_confirmation }}</div>
     </div>

     <div class="mt-4 flex items-center gap-4 justify-end">
      <Transition name="fade">
       <span v-if="pwdForm.recentlySuccessful" class="text-sm text-green-500 font-bold flex items-center gap-1">
        <span class="material-symbols-outlined text-[16px]">check_circle</span>
        Tersimpan
       </span>
      </Transition>
      <button type="submit" :disabled="pwdForm.processing" class="bg-[var(--bg-input)] text-[var(--text-heading)] border border-[var(--border)] hover:border-accent hover:text-accent px-6 py-3 rounded-xl font-bold w-full sm:w-auto transition-all disabled:opacity-70 disabled:cursor-not-allowed flex items-center justify-center gap-2">
       <span v-if="pwdForm.processing" class="material-symbols-outlined animate-spin text-[18px]">progress_activity</span>
       <span v-else class="material-symbols-outlined text-[18px]">key</span>
       Update Password
      </button>
     </div>
    </form>
   </div>
  </div>

  <!-- LOGOUT -->
  <div class="mt-2 mb-8">
   <button @click="handleLogout" class="flex items-center justify-center gap-3 w-full p-4 rounded-2xl bg-red-500/10 text-red-500 hover:bg-red-500/20 font-bold transition-colors border border-red-500/20 shadow-sm cursor-pointer">
    <span class="material-symbols-outlined">logout</span>
    Keluar Aplikasi
   </button>
  </div>
 </div>
</template>

<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import AsatidzLayout from '../../../Layouts/AsatidzLayout.vue'
import { useAuthStore } from '../../../stores/auth'

defineOptions({ layout: AsatidzLayout })

const props = defineProps({
 userData: { type: Object, required: true }
})

const authStore = useAuthStore()

function handleLogout() {
 authStore.logout()
}

const isEditing = ref(false)

const form = useForm({
 name: props.userData.name || '',
 email: props.userData.email || '',
 no_telepon: props.userData.asatidz?.no_telepon || '',
 alamat: props.userData.asatidz?.alamat || ''
})

function toggleEdit() {
 isEditing.value = !isEditing.value
 if (!isEditing.value) {
  form.reset()
  form.clearErrors()
 }
}

function submitProfile() {
 form.post(route('asatidz.profile.update'), {
  preserveScroll: true,
  onSuccess: () => {
   isEditing.value = false
  }
 })
}

const pwdForm = useForm({
 current_password: '',
 password: '',
 password_confirmation: '',
})

function submitPassword() {
 pwdForm.put(route('password.update'), {
  preserveScroll: true,
  onSuccess: () => pwdForm.reset(),
  onError: () => {
   if (pwdForm.errors.password) {
    pwdForm.reset('password', 'password_confirmation')
   }
   if (pwdForm.errors.current_password) {
    pwdForm.reset('current_password')
   }
  },
 })
}
</script>

<style scoped>
.profile-header { background: var(--bg-card); border: 1px solid var(--border); }
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s ease, transform 0.3s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; transform: translateY(-10px); }
</style>
