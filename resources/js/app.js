import '../css/app.css';
import './bootstrap';

import { createInertiaApp, Link } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

import { createPinia } from 'pinia';
import { applyCachedAccentColor } from './utils/appearance';

applyCachedAccentColor();

window.addEventListener('auth:redirect-login', () => {
  if (window.location.pathname !== '/login') {
    window.location.href = '/login';
  }
});

window.addEventListener('auth:unauthorized', (event) => {
  if (event.detail?.redirect !== false && window.location.pathname !== '/login') {
    window.location.href = '/login';
  }
});

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) =>
    resolvePageComponent(
      `./Pages/${name}.vue`,
      import.meta.glob('./Pages/**/*.vue'),
    ),
  setup({ el, App, props, plugin }) {
    return createApp({ render: () => h(App, props) })
      .use(plugin)
      .component('Link', Link)
      .use(createPinia())
      .use(ZiggyVue)
      .mount(el);
  },
  progress: {
    color: '#4B5563',
  },
});
