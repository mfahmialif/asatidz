<template>
 <header class="top-header relative z-[60] flex items-center justify-between px-4 sm:px-6 lg:px-8 py-4 shrink-0 transition-colors duration-500">
  <div class="flex items-center gap-3">
   <h2 class="text-lg sm:text-xl font-bold text-heading tracking-tight ml-2">{{ pageTitle }}</h2>
  </div>
  <div class="flex items-center gap-2 sm:gap-4">


   <div class="relative" ref="profileDropdownRef">
    <div @click="profileOpen = !profileOpen" class="avatar-ring w-9 h-9 sm:w-10 sm:h-10 rounded-full border-2 border-accent overflow-hidden shrink-0 cursor-pointer hover:opacity-80 transition-opacity shadow-[0_0_10px_rgba(37, 99, 235,0.3)] flex items-center justify-center">
     <span class="material-symbols-outlined text-accent text-xl sm:text-2xl">person</span>
    </div>
    <Transition name="dropdown">
     <div v-if="profileOpen" class="profile-dropdown absolute right-0 mt-2 w-64 rounded-xl overflow-hidden z-[70]">
      <div class="px-4 py-3 border-b profile-dropdown-border">
       <p class="text-sm font-bold text-heading truncate">{{ authStore.user?.name || 'User' }}</p>
       <p class="text-xs text-muted truncate mt-0.5">{{ authStore.user?.email || '' }}</p>
      </div>
      <div class="py-1.5">
       <Link href="/asatidz/profil-saya" class="profile-dropdown-item flex items-center gap-3 px-4 py-2.5 text-sm transition-colors w-full text-left cursor-pointer">
        <span class="material-symbols-outlined text-[20px]">manage_accounts</span>
        <span>Profil Saya</span>
       </Link>
       <button @click="$emit('toggle-theme')" class="profile-dropdown-item flex items-center gap-3 px-4 py-2.5 text-sm transition-colors w-full text-left cursor-pointer">
        <span class="material-symbols-outlined text-[20px]">{{ isDark ? 'light_mode' : 'dark_mode' }}</span>
        <span>{{ isDark ? 'Mode Terang' : 'Mode Gelap' }}</span>
       </button>
       <button @click="handleLogout" class="profile-dropdown-item flex items-center gap-3 px-4 py-2.5 text-sm transition-colors w-full text-left cursor-pointer logout-item">
        <span class="material-symbols-outlined text-[20px]">logout</span>
        <span>Logout</span>
       </button>
      </div>
     </div>
    </Transition>
   </div>
  </div>
 </header>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import { Link } from '@inertiajs/vue3'
import { useAuthStore } from '../../../stores/auth'

defineProps({
 pageTitle: { type: String, default: 'Dashboard' },
 isDark: { type: Boolean, default: true },
})

defineEmits(['toggle-theme', 'toggle-sidebar'])

const profileOpen = ref(false)
const profileDropdownRef = ref(null)
const authStore = useAuthStore()

function handleLogout() {
 profileOpen.value = false
 authStore.logout()
}

function handleClickOutside(e) {
 if (profileDropdownRef.value && !profileDropdownRef.value.contains(e.target)) {
  profileOpen.value = false
 }
}

onMounted(() => {
 document.addEventListener('click', handleClickOutside)
})
onBeforeUnmount(() => {
 document.removeEventListener('click', handleClickOutside)
})
</script>

<style scoped>
.top-header { background: var(--bg-header); backdrop-filter: blur(12px); }
.avatar-ring { background: var(--bg-input); }
.text-heading { color: var(--text-heading); }
.text-muted { color: var(--text-muted); }
.hamburger-btn { color: var(--text-heading); background: transparent; }
.hamburger-btn:hover { background: var(--bg-input); color: var(--color-accent); }
.theme-toggle { background: var(--toggle-bg); color: var(--toggle-text); }
.theme-toggle:hover { background: var(--color-accent); color: var(--text-btn); box-shadow: 0 0 20px rgba(37, 99, 235, 0.4); transform: rotate(15deg) scale(1.1); }
.theme-toggle:active { transform: rotate(0deg) scale(0.95); }
.profile-dropdown { background: var(--bg-card); border: 1px solid var(--border); box-shadow: 0 10px 40px rgba(0, 0, 0, 0.4), 0 2px 8px rgba(0, 0, 0, 0.2); }
.profile-dropdown-border { border-color: var(--border); }
.profile-dropdown-item { color: var(--text-body); }
.profile-dropdown-item:hover { background: var(--hover-nav-bg); color: var(--color-accent); }
.profile-dropdown-item.logout-item:hover { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
.dropdown-enter-active { transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); }
.dropdown-leave-active { transition: all 0.15s ease-in; }
.dropdown-enter-from { opacity: 0; transform: translateY(-8px) scale(0.95); }
.dropdown-leave-to { opacity: 0; transform: translateY(-4px) scale(0.98); }
.icon-swap-enter-active { transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1); }
.icon-swap-leave-active { transition: all 0.2s ease; }
.icon-swap-enter-from { opacity: 0; transform: rotate(-90deg) scale(0.5); }
.icon-swap-leave-to { opacity: 0; transform: rotate(90deg) scale(0.5); }
</style>
