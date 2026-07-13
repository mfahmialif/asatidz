import { ref } from 'vue'
import api from '../axios'

export const fallbackAppName = import.meta.env.VITE_APP_NAME || document.title || 'App'
export const appName = ref(fallbackAppName)

let appNameLoaded = false
let appNameRequest = null

export function getAppName() {
 return appName.value || fallbackAppName
}

export function setAppName(value) {
 const normalized = String(value || '').trim()
 appName.value = normalized || fallbackAppName
 return getAppName()
}

export async function loadAppNameFromSettings() {
 if (appNameLoaded) return getAppName()

 if (!appNameRequest) {
  appNameRequest = api.get('/settings/general')
   .then(({ data }) => setAppName(data?.system_name))
   .catch(() => getAppName())
   .finally(() => {
    appNameLoaded = true
    appNameRequest = null
   })
 }

 return appNameRequest
}

export function titleWithAppName(suffix = '') {
 return suffix ? `${getAppName()} - ${suffix}` : getAppName()
}
