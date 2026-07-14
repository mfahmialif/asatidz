<template>
 <div class="flex flex-col gap-6">

  <!-- ═══ PROFILE HEADER CARD ═══ -->
  <div class="profile-header rounded-2xl p-8 relative overflow-hidden">
   <div class="absolute inset-0 opacity-10" style="background: linear-gradient(135deg, var(--color-accent) 0%, transparent 60%)"></div>
   <div class="relative z-10 flex flex-col sm:flex-row items-center sm:items-start gap-6">
    <!-- Avatar -->
    <div class="relative">
     <div class="w-28 h-28 rounded-full border-4 border-accent flex items-center justify-center shadow-[0_0_25px_rgba(37, 99, 235,0.3)]" style="background: var(--bg-input)">
      <span class="material-symbols-outlined text-accent text-6xl">person</span>
     </div>
     <button class="absolute bottom-0 right-0 w-9 h-9 rounded-full bg-accent flex items-center justify-center shadow-lg cursor-pointer hover:scale-110 transition-transform" style="color: var(--text-btn)">
      <span class="material-symbols-outlined text-[18px]">photo_camera</span>
     </button>
    </div>
    <!-- Info -->
    <div class="text-center sm:text-left flex-1">
     <h2 class="text-2xl font-bold" style="color: var(--text-heading)">{{ userData?.name || 'Administrator' }}</h2>
     <p class="text-sm mt-1" style="color: var(--text-muted)">{{ userData?.email || 'admin@asatidz.id' }}</p>
     <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 mt-3">
      <span class="app-badge app-badge--blue gap-1.5 flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-500/10 text-blue-500">
       <span class="material-symbols-outlined text-[14px]">shield</span>
       {{ userData?.role?.name || 'Super Admin' }}
      </span>
      <span class="app-badge app-badge--success gap-1.5 flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-500/10 text-green-500">
       <span class="w-1.5 h-1.5 rounded-full animate-pulse bg-green-500"></span>
       Online
      </span>
     </div>
    </div>
    <!-- Edit Button -->
    <button @click="toggleEdit" class="btn-edit flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-bold transition-all cursor-pointer shrink-0">
     <span class="material-symbols-outlined text-[18px]">{{ isEditing ? 'close' : 'edit' }}</span>
     {{ isEditing ? 'Batal' : 'Edit Profil' }}
    </button>
   </div>
  </div>

  <!-- ═══ INFO GRID ═══ -->
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

   <!-- Personal Info / Edit Form -->
   <div class="info-card rounded-xl overflow-hidden">
    <div class="card-header px-6 py-4 flex items-center justify-between">
     <h3 class="font-bold flex items-center gap-2" style="color: var(--text-heading)">
      <span class="material-symbols-outlined text-accent text-[20px]">person</span>
      {{ isEditing ? 'Edit Informasi Pribadi' : 'Informasi Pribadi' }}
     </h3>
    </div>
    <div class="px-6 py-4">
     <form @submit.prevent="submitProfile" class="flex flex-col gap-5">
      <div class="flex flex-col gap-1.5">
       <label class="text-sm font-medium" style="color: var(--text-body)">Nama Lengkap</label>
       <input v-if="isEditing" v-model="form.name" type="text" required class="filter-input w-full rounded-xl py-2 px-3 text-sm bg-[var(--bg-input)] text-[var(--text-heading)] border border-[var(--border)] focus:outline-none focus:ring-1 focus:ring-accent" />
       <div v-else class="py-1 text-sm font-bold" style="color: var(--text-heading)">{{ userData?.name }}</div>
       <div v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</div>
      </div>

      <div class="flex flex-col gap-1.5">
       <label class="text-sm font-medium" style="color: var(--text-body)">Email</label>
       <input v-if="isEditing" v-model="form.email" type="email" required class="filter-input w-full rounded-xl py-2 px-3 text-sm bg-[var(--bg-input)] text-[var(--text-heading)] border border-[var(--border)] focus:outline-none focus:ring-1 focus:ring-accent" />
       <div v-else class="py-1 text-sm font-bold" style="color: var(--text-heading)">{{ userData?.email }}</div>
       <div v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</div>
      </div>

      <Transition name="fade">
       <div v-if="isEditing" class="mt-2 flex justify-end">
        <button type="submit" :disabled="form.processing" class="btn-edit px-5 py-2.5 rounded-lg text-sm font-bold flex items-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed">
         <span v-if="form.processing" class="material-symbols-outlined animate-spin text-[18px]">progress_activity</span>
         <span v-else class="material-symbols-outlined text-[18px]">save</span>
         Simpan Perubahan
        </button>
       </div>
      </Transition>
     </form>
    </div>
   </div>

   <!-- Account Info & Password Update -->
   <div class="info-card rounded-xl overflow-hidden">
    <div class="card-header px-6 py-4 flex items-center justify-between">
     <h3 class="font-bold flex items-center gap-2" style="color: var(--text-heading)">
      <span class="material-symbols-outlined text-accent text-[20px]">security</span>
      Ubah Password
     </h3>
    </div>
    <div class="px-6 py-4">
     <form @submit.prevent="submitPassword" class="flex flex-col gap-5">
      <div class="flex flex-col gap-1.5">
       <label class="text-sm font-medium" style="color: var(--text-body)">Password Saat Ini</label>
       <input v-model="pwdForm.current_password" type="password" required class="filter-input w-full rounded-xl py-2 px-3 text-sm bg-[var(--bg-input)] text-[var(--text-heading)] border border-[var(--border)] focus:outline-none focus:ring-1 focus:ring-accent" />
       <div v-if="pwdForm.errors.current_password" class="text-red-500 text-xs mt-1">{{ pwdForm.errors.current_password }}</div>
      </div>
      <div class="flex flex-col gap-1.5">
       <label class="text-sm font-medium" style="color: var(--text-body)">Password Baru</label>
       <input v-model="pwdForm.password" type="password" required class="filter-input w-full rounded-xl py-2 px-3 text-sm bg-[var(--bg-input)] text-[var(--text-heading)] border border-[var(--border)] focus:outline-none focus:ring-1 focus:ring-accent" />
       <div v-if="pwdForm.errors.password" class="text-red-500 text-xs mt-1">{{ pwdForm.errors.password }}</div>
      </div>
      <div class="flex flex-col gap-1.5">
       <label class="text-sm font-medium" style="color: var(--text-body)">Konfirmasi Password</label>
       <input v-model="pwdForm.password_confirmation" type="password" required class="filter-input w-full rounded-xl py-2 px-3 text-sm bg-[var(--bg-input)] text-[var(--text-heading)] border border-[var(--border)] focus:outline-none focus:ring-1 focus:ring-accent" />
       <div v-if="pwdForm.errors.password_confirmation" class="text-red-500 text-xs mt-1">{{ pwdForm.errors.password_confirmation }}</div>
      </div>
      <div class="mt-2 flex items-center gap-4 justify-end">
       <Transition name="fade">
        <span v-if="pwdForm.recentlySuccessful" class="text-sm text-green-500 font-bold flex items-center gap-1">
         <span class="material-symbols-outlined text-[16px]">check_circle</span>
         Tersimpan
        </span>
       </Transition>
       <button type="submit" :disabled="pwdForm.processing" class="btn-secondary px-5 py-2.5 rounded-lg text-sm font-bold flex items-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed">
        <span v-if="pwdForm.processing" class="material-symbols-outlined animate-spin text-[18px]">progress_activity</span>
        <span v-else class="material-symbols-outlined text-[18px]">lock_reset</span>
        Update Password
       </button>
      </div>
     </form>
    </div>
   </div>

  </div>
 </div>
</template>

<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import AdminLayout from '../../../Layouts/AdminLayout.vue'

defineOptions({ layout: AdminLayout })

const props = defineProps({
 userData: { type: Object, default: () => ({}) }
})

const isEditing = ref(false)

const form = useForm({
 name: props.userData?.name || '',
 email: props.userData?.email || '',
})

function toggleEdit() {
 isEditing.value = !isEditing.value
 if (!isEditing.value) {
  form.reset()
  form.clearErrors()
 }
}

function submitProfile() {
 form.post(route('admin.profile.update'), {
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
.info-card { background: var(--bg-card); border: 1px solid var(--border); }
.card-header { border-bottom: 1px solid var(--border); }

.btn-edit {
 background: var(--color-accent);
 color: var(--text-btn);
 box-shadow: 0 0 15px rgba(37, 99, 235, 0.2);
 border: none;
}
.btn-edit:hover:not(:disabled) {
 box-shadow: 0 0 25px rgba(37, 99, 235, 0.4);
 transform: translateY(-1px);
}

.btn-secondary {
 background: var(--bg-input);
 color: var(--text-heading);
 border: 1px solid var(--border);
}
.btn-secondary:hover:not(:disabled) {
 border-color: var(--color-accent);
 color: var(--color-accent);
}

.fade-enter-active, .fade-leave-active { transition: opacity 0.3s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
