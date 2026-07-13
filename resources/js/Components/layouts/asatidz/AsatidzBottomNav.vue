<template>
 <div class="fixed bottom-0 left-0 right-0 z-50 lg:hidden pointer-events-none">
  <!-- Profile Menu Overlay -->
  <Transition name="fade">
   <div v-if="profileOpen" class="fixed inset-0 bg-black/50 backdrop-blur-sm pointer-events-auto flex items-end justify-center pb-32 z-40" @click.self="profileOpen = false">
    <div class="bg-[var(--bg-card)] w-11/12 max-w-sm rounded-[2rem] p-6 shadow-2xl border border-[var(--border)] mb-4 profile-modal">
     <div class="flex items-center gap-4 border-b border-[var(--border-light)] pb-4 mb-4">
      <div class="w-12 h-12 rounded-full bg-[var(--bg-input)] flex items-center justify-center border-2 border-[var(--color-accent)] shrink-0">
       <span class="material-symbols-outlined text-[24px] text-[var(--color-accent)]">person</span>
      </div>
      <div class="min-w-0">
       <p class="font-bold text-[var(--text-heading)] text-lg truncate">{{ authStore.user?.name || 'User' }}</p>
       <p class="text-sm text-[var(--text-muted)] truncate">{{ authStore.user?.email || 'N/A' }}</p>
      </div>
     </div>
     <div class="flex flex-col gap-3">
      <Link href="/asatidz/profil-saya" class="flex items-center justify-center gap-3 w-full p-4 rounded-xl bg-[var(--bg-input)] text-[var(--text-heading)] hover:bg-[var(--hover-nav-bg)] font-bold transition-colors border border-[var(--border)]">
       <span class="material-symbols-outlined">manage_accounts</span>
       Profil Saya
      </Link>
      <button @click="handleLogout" class="flex items-center justify-center gap-3 w-full p-4 rounded-xl bg-red-500/10 text-red-500 hover:bg-red-500/20 font-bold transition-colors">
       <span class="material-symbols-outlined">logout</span>
       Keluar Aplikasi
      </button>
     </div>
    </div>
   </div>
  </Transition>

  <!-- Safe area / padding container -->
  <div class="px-4 pb-3 pt-6 pointer-events-auto relative z-50">
   <div class="bottom-nav-glass flex items-end justify-between px-6 py-2 rounded-full shadow-[0_10px_40px_rgba(0,0,0,0.15)]">
    
    <!-- Dashboard -->
    <Link href="/asatidz/dashboard" 
       class="flex flex-col items-center justify-center transition-all w-16"
       :class="isActive('/asatidz/dashboard') ? 'text-accent' : 'text-muted'">
     <span class="material-symbols-outlined text-[22px] mb-0.5" :class="{ 'font-fill scale-110': isActive('/asatidz/dashboard') }">dashboard</span>
     <span class="text-[10px] font-bold">Beranda</span>
    </Link>

    <!-- FAB Center Button -->
    <div class="relative -top-4 flex flex-col items-center justify-center w-16">
     <Link v-if="!isActive('/asatidz/kegiatan-asatidz')" href="/asatidz/kegiatan-asatidz"
        class="fab-btn flex items-center justify-center w-12 h-12 rounded-full shadow-[0_8px_20px_var(--color-accent-soft)] transition-all hover:scale-105 active:scale-95 mb-0.5 bg-accent text-white">
      <span class="material-symbols-outlined text-[24px]">add</span>
     </Link>
     <button v-else @click="handleFabClick"
        class="fab-btn flex items-center justify-center w-12 h-12 rounded-full shadow-[0_8px_20px_var(--color-accent-soft)] transition-all hover:scale-105 active:scale-95 mb-0.5 bg-accent text-white scale-110 cursor-pointer">
      <span class="material-symbols-outlined text-[24px]">add</span>
     </button>
     <span class="text-[10px] font-bold text-muted transition-colors" :class="{'text-accent': isActive('/asatidz/kegiatan-asatidz')}">Kegiatan</span>
    </div>

    <!-- Profile -->
    <button @click="profileOpen = !profileOpen" 
       class="flex flex-col items-center justify-center transition-all w-16"
       :class="profileOpen ? 'text-accent' : 'text-muted'">
     <span class="material-symbols-outlined text-[22px] mb-0.5" :class="{ 'font-fill scale-110': profileOpen }">person</span>
     <span class="text-[10px] font-bold">Profil</span>
    </button>
   </div>
  </div>
 </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import { usePage, Link } from '@inertiajs/vue3'
import { useAuthStore } from '../../../stores/auth'

const page = usePage()
const authStore = useAuthStore()
const profileOpen = ref(false)

function isActive(routePath) {
 const path = page.url.split('?')[0];
 return path === routePath
}

function handleFabClick(e) {
 if (isActive('/asatidz/kegiatan-asatidz')) {
  e.preventDefault()
  window.dispatchEvent(new CustomEvent('open-input-kegiatan'))
 }
}

function handleLogout() {
 authStore.logout()
}

// Close profile modal if route changes
watch(() => page.url, () => {
 profileOpen.value = false
})
</script>

<style scoped>
.bottom-nav-glass {
 background: var(--bg-header);
 backdrop-filter: blur(20px) saturate(1.5);
 border: 1px solid var(--border-light);
}

.text-muted { color: var(--text-muted); transition: color 0.2s; }
.text-heading { color: var(--text-heading); }
.text-accent { color: var(--color-accent); transition: color 0.2s; }
.bg-accent { background: var(--color-accent); }

.fab-btn {
 background: linear-gradient(135deg, var(--color-accent), var(--color-accent-strong, var(--color-accent)));
}

.font-fill {
 font-variation-settings: 'FILL' 1;
}

.fade-enter-active,
.fade-leave-active {
 transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
 opacity: 0;
}
.fade-enter-active .profile-modal {
 transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.fade-enter-from .profile-modal {
 transform: translateY(20px) scale(0.95);
}
.fade-leave-active .profile-modal {
 transition: transform 0.2s ease-in;
 transform: translateY(20px) scale(0.95);
}
</style>
