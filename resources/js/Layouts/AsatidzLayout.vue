<template>
 <div :data-theme="isDark ? 'dark' : 'light'" class="admin-root relative flex h-screen w-screen font-display overflow-hidden transition-colors duration-500">
  <Transition name="fade">
   <div v-if="sidebarOpen" class="sidebar-overlay fixed inset-0 bg-black/60 backdrop-blur-sm z-30 lg:hidden" @click="sidebarOpen = false"></div>
  </Transition>

  <div class="hidden lg:block relative group/sidebar shrink-0 transition-all duration-300" style="width: 256px">
   <AsatidzSidebar :collapsed="false" class="h-full" @close-sidebar="sidebarOpen = false" />
  </div>

  <AsatidzSidebar :collapsed="false" :class="['sidebar-mobile fixed z-40 lg:hidden transition-transform duration-300', sidebarOpen ? 'translate-x-0' : '-translate-x-full']" style="width: 256px" @close-sidebar="sidebarOpen = false" />

  <main class="flex flex-1 flex-col h-screen overflow-hidden transition-colors duration-500 min-w-0" :style="{ background: 'var(--bg-main)' }">
   <AsatidzNavbar :page-title="pageTitle" :is-dark="isDark" @toggle-theme="toggleTheme" @toggle-sidebar="sidebarOpen = !sidebarOpen" />
   <div class="flex-1 min-h-0">
    <simplebar class="h-full content-scroll" :auto-hide="true">
     <div class="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto w-full">
      <Transition name="page" mode="out-in">
       <slot />
      </Transition>
     </div>
    </simplebar>
   </div>
  </main>
 </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'
import simplebar from 'simplebar-vue'
import 'simplebar-vue/dist/simplebar.min.css'
import AsatidzSidebar from '../components/layouts/asatidz/AsatidzSidebar.vue'
import AsatidzNavbar from '../components/layouts/asatidz/AsatidzNavbar.vue'
import api from '../axios'
import { readThemePreference, resolveThemePreference, writeThemePreference } from '../composables/useThemePreference'
import { applyAccentColor, applyCachedAccentColor } from '../utils/appearance'

const page = usePage()
const pageTitle = computed(() => page.props.pageTitle || 'Dashboard')
const sidebarOpen = ref(false)

onMounted(() => {
 applyCachedAccentColor()
 loadAppearanceSettings()
})

watch(() => page.url, () => {
 sidebarOpen.value = false
})

const isDark = ref(true)

onMounted(() => {
 const saved = readThemePreference({ allowSystem: true, fallback: 'system', legacyKeys: ['admin-theme'] })
 isDark.value = resolveThemePreference(saved) === 'dark'
})

function toggleTheme() {
 isDark.value = !isDark.value
 writeThemePreference(isDark.value ? 'dark' : 'light', { legacyKeys: ['admin-theme'] })
}

async function loadAppearanceSettings() {
 try {
  const { data } = await api.get('/settings/general')
  if (data.accent_color) applyAccentColor(data.accent_color)
 } catch {
 }
}
</script>

<style scoped>
.admin-root[data-theme="dark"] {
 --bg-main: #0B1120; --bg-sidebar: #0B1120; --bg-header: rgba(11, 17, 32, 0.92); --bg-card: #141d38; --bg-input: #141d38;
 --bg-table-head: #141d38; --bg-table-body: #111a33; --border: #1c2850; --border-light: #243362;
 --text-heading: #ffffff; --text-body: #cbd5e1; --text-muted: #64748b; --text-btn: #ffffff;
 --hover-nav-bg: rgba(37, 99, 235, 0.08); --shadow-card: 0 4px 24px rgba(0,0,0,0.4), 0 1px 3px rgba(0,0,0,0.2);
 --toggle-bg: #141d38; --toggle-text: #2563eb; --color-scheme: dark;
}
.admin-root[data-theme="light"] {
 --bg-main: #eef2f7; --bg-sidebar: #eef2f7; --bg-header: rgba(238, 242, 247, 0.92); --bg-card: #ffffff; --bg-input: #ffffff;
 --bg-table-head: #e8ecf1; --bg-table-body: #ffffff; --border: #dce3ec; --border-light: #cbd5e1;
 --text-heading: #0f172a; --text-body: #475569; --text-muted: #94a3b8; --text-btn: #ffffff;
 --hover-nav-bg: rgba(37, 99, 235, 0.12); --shadow-card: 0 4px 24px rgba(0,0,0,0.07), 0 1px 3px rgba(0,0,0,0.04);
 --toggle-bg: #e2e8f0; --toggle-text: #475569; --color-scheme: light;
}
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
.page-enter-active { transition: opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1), transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
.page-leave-active { transition: opacity 0.15s cubic-bezier(0.4, 0, 1, 1); }
.page-enter-from { opacity: 0; transform: translateY(12px); }
.page-leave-to { opacity: 0; }
.sidebar-mobile { height: 100vh; }
.content-scroll :deep(.simplebar-scrollbar::before) { background: var(--border-light); border-radius: 999px; opacity: 0; transition: opacity 0.4s ease; }
.content-scroll :deep(.simplebar-scrollbar.simplebar-visible::before) { opacity: 0.6; }
.content-scroll:hover :deep(.simplebar-scrollbar::before) { opacity: 0.4; }
.content-scroll :deep(.simplebar-scrollbar:hover::before) { background: var(--text-muted); opacity: 1; }
.content-scroll :deep(.simplebar-track.simplebar-vertical) { width: 8px; right: 0; }
.content-scroll :deep(.simplebar-track.simplebar-horizontal) { display: none; }
.admin-root { background: var(--bg-main); color: var(--text-body); }
.admin-root :deep(.table-wrapper) { background: var(--bg-table-body); border: 1px solid var(--border); }
.admin-root :deep(.table-head) { background: var(--bg-table-head); border-bottom: 1px solid var(--border-light); }
.admin-root :deep(.table-body > tr) { border-bottom: 1px solid var(--border); }
.admin-root :deep(.table-body > tr:last-child) { border-bottom: none; }
.admin-root :deep(.table-row-hover) { transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); border-left: 3px solid transparent; }
.admin-root :deep(.table-row-hover:hover) { background: var(--hover-nav-bg); border-left-color: var(--color-accent); }
.admin-root :deep(.table-row-hover:hover td) { color: var(--text-heading); }
.admin-root :deep(.multiselect) { min-height: 36px; border-radius: 0.5rem; cursor: pointer; font-family: inherit; }
.admin-root :deep(.multiselect__tags) { background: var(--bg-input); border: 1px solid var(--border); border-radius: 0.5rem; min-height: 36px; padding: 6px 40px 0 10px; font-size: 0.875rem; transition: border-color 0.2s ease, box-shadow 0.2s ease; }
.admin-root :deep(.multiselect--active .multiselect__tags) { border-color: var(--color-accent); box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.15); }
.admin-root :deep(.multiselect__single) { background: transparent; color: var(--text-heading); font-size: 0.875rem; font-weight: 500; margin-bottom: 0; padding: 0; line-height: 1.6; }
.admin-root :deep(.multiselect__input) { background: transparent; color: var(--text-heading); font-size: 0.875rem; margin-bottom: 0; padding: 0; line-height: 1.6; border: none; }
.admin-root :deep(.multiselect__input::placeholder) { color: var(--text-muted); }
.admin-root :deep(.multiselect__placeholder) { color: var(--text-muted); font-size: 0.875rem; margin-bottom: 0; padding: 0; }
.admin-root :deep(.multiselect__select) { height: 36px; width: 36px; }
.admin-root :deep(.multiselect__select::before) { border-color: var(--text-muted) transparent transparent; border-width: 5px 5px 0; }
.admin-root :deep(.multiselect--active .multiselect__select::before) { border-color: var(--color-accent) transparent transparent; }
.admin-root :deep(.multiselect__content-wrapper) { background: var(--bg-card); border: 1px solid var(--border); border-radius: 0.75rem; box-shadow: 0 8px 24px rgba(0, 0, 0, 0.35); margin-top: 4px; z-index: 100; }
.admin-root :deep(.multiselect__option) { color: var(--text-body); font-size: 0.875rem; padding: 10px 14px; min-height: unset; line-height: 1.4; transition: background 0.15s ease, color 0.15s ease; }
.admin-root :deep(.multiselect__option--highlight) { background: rgba(37, 99, 235, 0.12); color: var(--color-accent); }
.admin-root :deep(.multiselect__option--selected) { background: rgba(37, 99, 235, 0.18); color: var(--color-accent); font-weight: 700; }
.admin-root :deep(.multiselect__option--selected.multiselect__option--highlight) { background: rgba(37, 99, 235, 0.25); color: var(--color-accent); }
.admin-root :deep(.multiselect__option--disabled) { background: transparent; color: var(--text-muted); }
.admin-root :deep(.multiselect__spinner) { background: var(--bg-input); }
</style>
